<template>
  <Head title="Branches" />

  <AppLayout :breadcrumbs="breadcrumbs">

    <div class="flex h-full flex-1 flex-col p-6">
      <!-- Header Section -->
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-3xl font-bold text-foreground">Branches</h1>
          <p class="text-muted-foreground mt-2">Manage branches and their settings</p>
        </div>
        <div>
          <Button @click="router.visit(route('branches.create'))">
            <Plus class="h-4 w-4 mr-2" />
            Create Branch
          </Button>
        </div>
      </div>

      <Card>
        <CardContent>
          <!-- Search and Filters -->
          <div class="mb-4 space-y-4">
            <div class="flex flex-col sm:flex-row gap-4">
              <div class="flex-1">
                <Input v-model="search" placeholder="Search by name or principal..." />
              </div>
              <Button 
                variant="outline" 
                @click="showFilters = !showFilters"
                class="sm:w-auto w-full"
              >
                <Filter class="h-4 w-4 mr-2" />
                Filters
                <ChevronDown class="h-4 w-4 ml-2" :class="{ 'rotate-180': showFilters }" />
              </Button>
            </div>
            
            <!-- Advanced Filters -->
            <div v-if="showFilters" class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4 bg-muted/50 rounded-lg">
              <div class="space-y-2">
                <Label for="status-filter">Status</Label>
                <SimpleCombobox
                  v-model="statusFilter"
                  :options="props.statusOptions || []"
                  placeholder="All Status"
                  class="w-full"
                />
              </div>
              
              <div class="flex gap-2 items-end">
                <Button variant="outline" @click="clearFilters">
                  <X class="h-4 w-4 mr-2" />
                  Clear Filters
                </Button>
              </div>
            </div>
          </div>
          
          <Table>
              <TableHeader>
                <TableRow>
                  <TableHead>Name</TableHead>
                  <TableHead>Principal</TableHead>
                  <TableHead>Status</TableHead>
                  <TableHead class="text-center">Actions</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody v-if="branches.data.length">
                <TableRow v-for="branch in branches.data" :key="branch.id">
                  <TableCell>{{ branch.name }}</TableCell>
                  <TableCell>{{ branch.principal?.name }}</TableCell>
                  <TableCell>
                    <Badge :variant="branch.status ? 'default' : 'destructive'">
                      {{ branch.status ? 'Active' : 'Inactive' }}
                    </Badge>
                  </TableCell>
                  <TableCell class="flex gap-1 justify-center">
                    <!-- View Branch -->
                    <Tooltip>
                      <TooltipTrigger asChild>
                        <Button 
                          variant="outline" 
                          size="sm"
                          asChild
                        >
                          <Link :href="route('branches.show', branch.id)">
                            <Eye class="h-4 w-4" />
                          </Link>
                        </Button>
                      </TooltipTrigger>
                      <TooltipContent>
                        <p>View branch details</p>
                      </TooltipContent>
                    </Tooltip>

                    <!-- Edit Branch -->
                    <Tooltip>
                      <TooltipTrigger asChild>
                        <Button 
                          variant="outline" 
                          size="sm" 
                          asChild
                        >
                          <Link :href="route('branches.edit', branch.id)">
                            <Edit class="h-4 w-4" />
                          </Link>
                        </Button>
                      </TooltipTrigger>
                      <TooltipContent>
                        <p>Edit branch information</p>
                      </TooltipContent>
                    </Tooltip>
                    
                    <!-- Delete Branch -->
                    <ConfirmationModal
                      title="Are you sure?"
                      :description="getDeleteDescription(branch)"
                      confirm-text="Delete"
                      tooltip="Delete branch permanently"
                      @confirm="destroy(branch.id)"
                    >
                      <template #trigger>
                        <Button 
                          variant="destructive" 
                          size="sm"
                        >
                          <Trash2 class="h-4 w-4" />
                        </Button>
                      </template>
                    </ConfirmationModal>
                  </TableCell>
                </TableRow>
              </TableBody>
              <TableBody v-else>
                <TableRow>
                  <TableCell colspan="4" class="text-center py-4">
                      No records found
                  </TableCell>
                </TableRow>
              </TableBody>
            </Table>

          <div v-if="totalPages >= 1" class="mt-6">
            <Pagination
              :total="totalPages * 10"
              :items-per-page="10"
              :sibling-count="1"
              show-edges
              :default-page="currentPage"
              @update:page="handlePageChange"
            >
              <PaginationContent>
                <PaginationFirst />

                <template v-for="(item, index) in totalPages" :key="index">
                  <PaginationItem
                    :value="item"
                    :is-active="currentPage === item"
                    @click="handlePageChange(item)"
                  >
                    {{ item }}
                  </PaginationItem>
                </template>

                <PaginationLast />
              </PaginationContent>
            </Pagination>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, watch, computed, onMounted } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import {
  Pagination,
  PaginationEllipsis,
  PaginationFirst,
  PaginationItem,
  PaginationLast,
  PaginationContent,
} from '@/components/ui/pagination'
import { Button } from '@/components/ui/button'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Card, CardContent } from '@/components/ui/card'
import { Table, TableHeader, TableBody, TableHead, TableRow, TableCell } from '@/components/ui/table'
import { Badge } from '@/components/ui/badge/index'
import { Plus, Edit, Trash2, Filter, ChevronDown, X, Eye } from 'lucide-vue-next'
import { Tooltip, TooltipTrigger, TooltipContent } from '@/components/ui/tooltip'
import SimpleCombobox from '@/components/ui/SimpleCombobox.vue'
import ConfirmationModal from '@/components/ConfirmationModal.vue'
import { type BreadcrumbItem } from '@/types'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Branches',
        href: '/branches',
    },
]

interface Principal {
    id: string
    name: string
}

interface Branch {
    id: string
    name: string
    status: boolean
    principal?: Principal
}

interface PaginatedData<T> {
  data: T[]
  links: {
    url: string | null
    label: string
    active: boolean
  }[]
  current_page: number
  total: number
  per_page: number
}

interface Props {
    branches: {
        data: Branch[];
        links: {
            url: string | null;
            label: string;
            active: boolean;
        }[];
    };
    filters?: {
        search?: string;
        status?: string;
    };
    statusOptions?: Array<{ value: string; label: string }>;
}

const props = defineProps<Props>()

const search = ref(props.filters?.search || '')
const statusFilter = ref(props.filters?.status || '')
const showFilters = ref((props.filters?.search && props.filters.search !== '') || (props.filters?.status && props.filters.status !== ''))

let isInitialized = false

const applyFilters = () => {
  showFilters.value = true
  router.get(route('branches.index'), {
    search: search.value,
    status: statusFilter.value,
  }, {
    preserveScroll: true,
    preserveState: true,
    replace: true,
  })
}

watch([search, statusFilter], () => {
  if (isInitialized) {
    applyFilters()
  }
})

onMounted(() => {
  isInitialized = true
})

const clearFilters = () => {
  search.value = ''
  statusFilter.value = ''
}

const totalPages = computed(() => {
  if (!props.branches?.links) return 0
  return props.branches.links.filter(link => !isNaN(parseInt(link.label))).length
})

const currentPage = computed(() => {
  const activePage = props.branches.links.find(link => link.active && !isNaN(parseInt(link.label)))
  return activePage ? parseInt(activePage.label) : 1
})

const handlePageChange = (page: number) => {
  router.get(route('branches.index'), {
    page,
    search: search.value,
    status: statusFilter.value,
  }, {
    preserveScroll: true,
    preserveState: true,
  })
}

const destroy = (id: string) => {
  router.delete(route('branches.destroy', id))
}

const getDeleteDescription = (branch: Branch) => {
  return `This action cannot be undone. This will permanently delete the branch "${branch.name}" and remove its data from our servers.`
}
</script> 