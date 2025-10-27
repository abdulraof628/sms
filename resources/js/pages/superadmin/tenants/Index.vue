<script setup lang="ts">
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, watch, computed, onMounted } from 'vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import ConfirmationModal from '@/components/ConfirmationModal.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import { Eye, Edit, Trash2, Plus, UserX, UserCheck, Filter, ChevronDown, X } from 'lucide-vue-next';
import { Card, CardContent } from '@/components/ui/card';
import { Tooltip, TooltipTrigger, TooltipContent } from '@/components/ui/tooltip';
import { SimpleCombobox } from '@/components/ui';
import {
  Pagination,
  PaginationEllipsis,
  PaginationFirst,
  PaginationItem,
  PaginationLast,
  PaginationContent,
} from '@/components/ui/pagination'
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Tenants',
        href: '/tenants',
    },
];

interface Tenant {
    id: string;
    name: string;
    email: string;
    phone: string | null;
    address: string | null;
    is_active: boolean;
    created_at: string;
    updated_at: string;
}

interface Props {
    tenants: {
        data: Tenant[];
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

const props = defineProps<Props>();

const search = ref(props.filters?.search || '');
const statusFilter = ref(props.filters?.status || '');
const showFilters = ref((props.filters?.search && props.filters.search !== '') || (props.filters?.status && props.filters.status !== ''));

let isInitialized = false;

const applyFilters = () => {
    showFilters.value = true;
    router.get(route('superadmin.tenants.index'), { 
        search: search.value,
        status: statusFilter.value,
    }, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
};

watch([search, statusFilter], () => {
    if (isInitialized) {
        applyFilters();
    }
});

onMounted(() => {
    isInitialized = true;
});

const clearFilters = () => {
    search.value = '';
    statusFilter.value = '';
};

const totalPages = computed(() => {
    if (!props.tenants?.links) return 0
    // Laravel pagination includes prev, pages, and next
    return props.tenants.links.filter(link => !isNaN(parseInt(link.label))).length
})

const currentPage = computed(() => {
    if (!props.tenants?.links) return 1
    const activePage = props.tenants.links.find(link => link.active && !isNaN(parseInt(link.label)))
    return activePage ? parseInt(activePage.label) : 1
})

const handlePageChange = (page: number) => {
    router.get(route('superadmin.tenants.index'), { 
        page, 
        search: search.value,
        status: statusFilter.value,
    }, {
        preserveScroll: true,
        preserveState: true,
    })
}

const deleteTenant = (id: string) => {
    router.delete(route('superadmin.tenants.destroy', id));
};

const toggleTenantStatus = (id: string) => {
    router.post(route('superadmin.tenants.toggle-status', id), {}, {
        preserveScroll: true,
    });
};

const getStatusToggleDescription = (tenant: any) => {
    return tenant.is_active 
        ? `This will deactivate the tenant "${tenant.name}" and all associated users. Users will not be able to login until the tenant is reactivated.`
        : `This will activate the tenant "${tenant.name}" and all associated users. Users will be able to login again.`;
};

const getDeleteDescription = (tenant: any) => {
    return `This action cannot be undone. This will permanently delete the tenant "${tenant.name}" and remove their data from our servers.`;
};
</script>

<template>
    <Head title="Tenants" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="flex h-full flex-1 flex-col p-6">
            <!-- Header Section (like Dashboard) -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">Tenants</h1>
                    <p class="text-muted-foreground mt-2">Manage tenants and their settings</p>
                </div>
                <div>
                    <Button @click="router.visit(route('superadmin.tenants.create'))">
                        <Plus class="h-4 w-4 mr-2" />
                        Create Tenant
                    </Button>
                </div>
            </div>

            <Card>
                <CardContent>
                    <!-- Search and Filters -->
                    <div class="mb-4 space-y-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <Input v-model="search" placeholder="Search by name, email, or phone..." />
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
                                <TableHead>Full Name</TableHead>
                                <TableHead>Email</TableHead>
                                <TableHead>Phone</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Joined Date</TableHead>
                                <TableHead class="text-center">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-if="!props.tenants?.data?.length">
                                <TableCell colspan="6" class="text-center py-4">
                                    No records found
                                </TableCell>
                            </TableRow>
                            <TableRow v-for="tenant in props.tenants?.data || []" :key="tenant.id">
                                <TableCell>{{ tenant.name }}</TableCell>
                                <TableCell>{{ tenant.email }}</TableCell>
                                <TableCell>{{ tenant.phone || '-' }}</TableCell>
                                <TableCell>
                                    <Badge :variant="tenant.is_active ? 'default' : 'secondary'">
                                        {{ tenant.is_active ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </TableCell>
                                <TableCell>{{ new Date(tenant.created_at).toLocaleDateString('en-GB') }}</TableCell>
                                <TableCell class="flex gap-1 justify-center">
                                    <!-- View Tenant -->
                                    <Tooltip>
                                        <TooltipTrigger asChild>
                                            <Button 
                                                variant="outline" 
                                                size="sm" 
                                                asChild
                                            >
                                                <Link :href="route('superadmin.tenants.show', tenant.id)">
                                                    <Eye class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            <p>View tenant details</p>
                                        </TooltipContent>
                                    </Tooltip>

                                    <!-- Edit Tenant -->
                                    <Tooltip>
                                        <TooltipTrigger asChild>
                                            <Button 
                                                variant="outline" 
                                                size="sm" 
                                                asChild
                                            >
                                                <Link :href="route('superadmin.tenants.edit', tenant.id)">
                                                    <Edit class="h-4 w-4" />
                                                </Link>
                                            </Button>
                                        </TooltipTrigger>
                                        <TooltipContent>
                                            <p>Edit tenant information</p>
                                        </TooltipContent>
                                    </Tooltip>

                                    <!-- Status Toggle -->
                                    <ConfirmationModal
                                        :title="tenant.is_active ? 'Deactivate Tenant' : 'Activate Tenant'"
                                        :description="getStatusToggleDescription(tenant)"
                                        :confirm-text="tenant.is_active ? 'Deactivate' : 'Activate'"
                                        :tooltip="tenant.is_active ? 'Deactivate tenant' : 'Activate tenant'"
                                        @confirm="toggleTenantStatus(tenant.id)"
                                    >
                                        <template #trigger>
                                            <Button 
                                                variant="outline" 
                                                size="sm"
                                                :class="tenant.is_active ? 'text-red-600 hover:text-red-700' : 'text-green-600 hover:text-green-700'"
                                            >
                                                <UserX v-if="tenant.is_active" class="h-4 w-4" />
                                                <UserCheck v-else class="h-4 w-4" />
                                            </Button>
                                        </template>
                                    </ConfirmationModal>

                                    <!-- Delete Tenant -->
                                    <ConfirmationModal
                                        title="Are you sure?"
                                        :description="getDeleteDescription(tenant)"
                                        confirm-text="Delete"
                                        tooltip="Delete tenant permanently"
                                        @confirm="deleteTenant(tenant.id)"
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