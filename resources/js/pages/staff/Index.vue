<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
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
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
  Pagination,
  PaginationContent,
  PaginationFirst,
  PaginationItem,
  PaginationLast,
} from '@/components/ui/pagination';
import { SimpleCombobox } from '@/components/ui';
import { Tooltip, TooltipTrigger, TooltipContent } from '@/components/ui/tooltip';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Staff',
        href: '/staff',
    },
];

interface Tenant {
    id: string;
    name: string;
}

interface User {
    id: string;
    name: string;
    email: string;
}

interface Staff {
    id: string;
    user_id: string;
    user?: User;
    name?: string; // Accessor from user
    email?: string; // Accessor from user
    phone: string | null;
    employee_id: string;
    position: string | null;
    department: string | null;
    role: 'admin' | 'principal' | 'teacher';
    shift_start: string | null;
    shift_end: string | null;
    weekly_hours: number;
    annual_leave_balance: number;
    sick_leave_balance: number;
    casual_leave_balance: number;
    total_leave_balance: number;
    overtime_enabled: boolean;
    total_overtime_minutes: number;
    is_active: boolean;
    tenant?: Tenant;
    photo?: string | null;
    created_at: string;
}

interface Props {
    staff: {
        data: Staff[];
        links: {
            url: string | null;
            label: string;
            active: boolean;
        }[];
    };
    filters?: {
        search?: string;
        role?: string;
        status?: boolean | null;
    };
    roleOptions?: Array<{ value: string; label: string }>;
    statusOptions?: Array<{ value: string; label: string }>;
}

const props = defineProps<Props>();

const search = ref(props.filters?.search || '');
const roleFilter = ref(props.filters?.role || '');
const statusFilter = ref<string>(props.filters?.status ? (props.filters.status ? '1' : '0') : '');
const showFilters = ref((props.filters?.search && props.filters.search !== '') || (props.filters?.role && props.filters.role !== '') || (props.filters?.status && props.filters.status !== ''));

let isInitialized = false;

const applyFilters = () => {
    showFilters.value = true;
    router.get(route('staff.index'), { 
        search: search.value,
        role: roleFilter.value,
        status: statusFilter.value,
    }, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
};

watch([search, roleFilter, statusFilter], () => {
    if (isInitialized) {
        applyFilters();
    }
});

onMounted(() => {
    isInitialized = true;
});

const clearFilters = () => {
    search.value = '';
    roleFilter.value = '';
    statusFilter.value = '';
};

const totalPages = computed(() => {
    if (!props.staff?.links) return 0;
    return props.staff.links.filter(link => !isNaN(parseInt(link.label))).length;
});

const currentPage = computed(() => {
    if (!props.staff?.links) return 1;
    const activePage = props.staff.links.find(link => link.active && !isNaN(parseInt(link.label)));
    return activePage ? parseInt(activePage.label) : 1;
});

const handlePageChange = (page: number) => {
    router.get(route('staff.index'), { 
        page, 
        search: search.value,
        role: roleFilter.value,
        status: statusFilter.value,
    }, {
        preserveScroll: true,
        preserveState: true,
    });
};

const deleteStaff = (id: string) => {
    router.delete(route('staff.destroy', id));
};

const toggleStaffStatus = (id: string) => {
    router.post(route('staff.toggle-status', id), {}, {
        preserveScroll: true,
    });
};

const getStatusToggleDescription = (staff: Staff) => {
    return staff.is_active 
        ? `This will deactivate "${staff.name}". They will be marked as inactive.`
        : `This will activate "${staff.name}". They will be marked as active.`;
};

const getDeleteDescription = (staff: Staff) => {
    return `This action cannot be undone. This will permanently delete "${staff.name}" and remove their data from our servers.`;
};

const getRoleBadgeVariant = (role: string) => {
    switch (role) {
        case 'admin':
            return 'destructive';
        case 'principal':
            return 'default';
        case 'teacher':
            return 'secondary';
        default:
            return 'outline';
    }
};

const formatTime = (time: string | null) => {
    if (!time) return '-';
    return new Date('1970-01-01T' + time).toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: true 
    });
};

const formatOvertimeHours = (minutes: number) => {
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return `${hours}h ${mins}m`;
};

const getInitials = (name: string | undefined | null) => {
    if (!name) return '??';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
};
</script>

<template>
    <Head title="Staff Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">Staff</h1>
                    <p class="text-muted-foreground mt-2">Manage staff members, roles, and attendance</p>
                </div>
                <div>
                    <Button @click="router.visit(route('staff.create'))">
                        <Plus class="h-4 w-4 mr-2" />
                        Add Staff
                    </Button>
                </div>
            </div>

            <Card>
                <CardContent>
                    <!-- Search and Filters -->
                    <div class="mb-4 space-y-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <Input v-model="search" placeholder="Search by name, email, ID, or position..." />
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
                        <div v-if="showFilters" class="grid grid-cols-1 md:grid-cols-4 gap-4 p-4 bg-muted/50 rounded-lg">
                            <div class="space-y-2">
                                <Label for="role-filter">Role</Label>
                                <SimpleCombobox
                                    v-model="roleFilter"
                                    :options="props.roleOptions || []"
                                    placeholder="All Roles"
                                    class="w-full"
                                />
                            </div>
                            
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

                    <div class="overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Photo</TableHead>
                                    <TableHead>Employee ID</TableHead>
                                    <TableHead>Name</TableHead>
                                    <TableHead>Email</TableHead>
                                    <TableHead>Position</TableHead>
                                    <TableHead>Role</TableHead>
                                    <TableHead>Shift</TableHead>
                                    <TableHead>Leave Balance</TableHead>
                                    <TableHead>Overtime</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead class="text-center">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-if="!props.staff?.data?.length">
                                    <TableCell colspan="11" class="text-center py-4">
                                        No staff members found
                                    </TableCell>
                                </TableRow>
                                <TableRow v-for="member in props.staff?.data || []" :key="member.id">
                                    <TableCell>
                            <Avatar class="h-10 w-10">
                                <AvatarImage 
                                    v-if="member.photo" 
                                    :src="`/storage/${member.photo}`" 
                                    :alt="member.name"
                                    class="object-cover"
                                    loading="lazy"
                                />
                                <AvatarFallback class="text-xs">{{ getInitials(member.name) }}</AvatarFallback>
                            </Avatar>
                                    </TableCell>
                                    <TableCell class="font-medium">{{ member.employee_id }}</TableCell>
                                    <TableCell>{{ member.name }}</TableCell>
                                    <TableCell>{{ member.email }}</TableCell>
                                    <TableCell>{{ member.position || '-' }}</TableCell>
                                    <TableCell>
                                        <Badge :variant="getRoleBadgeVariant(member.role)">
                                            {{ member.role.charAt(0).toUpperCase() + member.role.slice(1) }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <div class="text-sm">
                                            {{ formatTime(member.shift_start) }} - {{ formatTime(member.shift_end) }}
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <div class="text-sm">
                                            {{ member.total_leave_balance }} days
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <div class="text-sm">
                                            <span v-if="member.overtime_enabled && member.total_overtime_minutes > 0">
                                                {{ formatOvertimeHours(member.total_overtime_minutes) }}
                                            </span>
                                            <span v-else-if="member.overtime_enabled" class="text-muted-foreground">0h 0m</span>
                                            <span v-else class="text-muted-foreground">Disabled</span>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <Badge :variant="member.is_active ? 'default' : 'secondary'">
                                            {{ member.is_active ? 'Active' : 'Inactive' }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell class="flex gap-1 justify-center">
                                        <!-- View Staff -->
                                        <Button 
                                            variant="outline" 
                                            size="sm" 
                                            asChild
                                            tooltip="View staff details"
                                        >
                                            <Link :href="route('staff.show', member.id)">
                                                <Eye class="h-4 w-4" />
                                            </Link>
                                        </Button>

                                        <!-- Edit Staff -->
                                        <Button 
                                            variant="outline" 
                                            size="sm" 
                                            asChild
                                            tooltip="Edit staff information"
                                        >
                                            <Link :href="route('staff.edit', member.id)">
                                                <Edit class="h-4 w-4" />
                                            </Link>
                                        </Button>

                                        <!-- Status Toggle -->
                                        <ConfirmationModal
                                            :title="member.is_active ? 'Deactivate Staff' : 'Activate Staff'"
                                            :description="getStatusToggleDescription(member)"
                                            :confirm-text="member.is_active ? 'Deactivate' : 'Activate'"
                                            :tooltip="member.is_active ? 'Deactivate staff' : 'Activate staff'"
                                            @confirm="toggleStaffStatus(member.id)"
                                        >
                                            <template #trigger>
                                                <Button 
                                                    variant="outline" 
                                                    size="sm"
                                                    :class="member.is_active ? 'text-red-600 hover:text-red-700' : 'text-green-600 hover:text-green-700'"
                                                >
                                                    <UserX v-if="member.is_active" class="h-4 w-4" />
                                                    <UserCheck v-else class="h-4 w-4" />
                                                </Button>
                                            </template>
                                        </ConfirmationModal>

                                        <!-- Delete Staff -->
                                        <ConfirmationModal
                                            title="Are you sure?"
                                            :description="getDeleteDescription(member)"
                                            confirm-text="Delete"
                                            tooltip="Delete staff permanently"
                                            @confirm="deleteStaff(member.id)"
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
                    </div>

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

