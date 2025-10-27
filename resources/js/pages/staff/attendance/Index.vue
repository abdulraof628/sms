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
import { Clock, ClockIcon, Filter, Trash2, LogIn, LogOut, ChevronDown, X } from 'lucide-vue-next';
import { Card, CardContent } from '@/components/ui/card';
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
    {
        title: 'Attendance',
        href: '/staff-attendance',
    },
];

interface Staff {
    id: string;
    name: string;
    employee_id: string;
}

interface AttendanceRecord {
    id: string;
    staff_id: string;
    staff: Staff;
    date: string;
    clock_in: string | null;
    clock_out: string | null;
    status: 'present' | 'absent' | 'half-day' | 'leave';
    is_late: boolean;
    late_minutes: number;
    overtime_minutes: number;
    overtime_pay: number;
    notes: string | null;
}

interface Props {
    attendance: {
        data: AttendanceRecord[];
        links: {
            url: string | null;
            label: string;
            active: boolean;
        }[];
    };
    staff: Staff[];
    filters?: {
        staff_id?: string;
        date_from?: string;
        date_to?: string;
        status?: string;
    };
}

const props = defineProps<Props>();

const staffFilter = ref(props.filters?.staff_id || '');
const dateFrom = ref(props.filters?.date_from || '');
const dateTo = ref(props.filters?.date_to || '');
const statusFilter = ref(props.filters?.status || '');
const showFilters = ref(
  (props.filters?.staff_id && props.filters.staff_id !== '') ||
  (props.filters?.date_from && props.filters.date_from !== '') ||
  (props.filters?.date_to && props.filters.date_to !== '') ||
  (props.filters?.status && props.filters.status !== '')
);

let isInitialized = false;

const staffOptions = computed(() => [
    { value: '', label: 'All Staff' },
    ...props.staff.map(member => ({
        value: member.id,
        label: `${member.name} (${member.employee_id})`
    }))
]);

const statusOptions = [
    { value: '', label: 'All Status' },
    { value: 'present', label: 'Present' },
    { value: 'absent', label: 'Absent' },
    { value: 'half-day', label: 'Half Day' },
    { value: 'leave', label: 'Leave' },
];

const applyFilters = () => {
    showFilters.value = true;
    router.get(route('staff.attendance.index'), { 
        staff_id: staffFilter.value,
        date_from: dateFrom.value,
        date_to: dateTo.value,
        status: statusFilter.value,
    }, {
        preserveScroll: true,
        preserveState: true,
        replace: true,
    });
};

watch([staffFilter, dateFrom, dateTo, statusFilter], () => {
    if (isInitialized) {
        applyFilters();
    }
});

onMounted(() => {
    isInitialized = true;
});

const clearFilters = () => {
    staffFilter.value = '';
    dateFrom.value = '';
    dateTo.value = '';
    statusFilter.value = '';
};

const totalPages = computed(() => {
    if (!props.attendance?.links) return 0;
    return props.attendance.links.filter(link => !isNaN(parseInt(link.label))).length;
});

const currentPage = computed(() => {
    if (!props.attendance?.links) return 1;
    const activePage = props.attendance.links.find(link => link.active && !isNaN(parseInt(link.label)));
    return activePage ? parseInt(activePage.label) : 1;
});

const handlePageChange = (page: number) => {
    router.get(route('staff.attendance.index'), { 
        page,
        staff_id: staffFilter.value,
        date_from: dateFrom.value,
        date_to: dateTo.value,
        status: statusFilter.value,
    }, {
        preserveScroll: true,
        preserveState: true,
    });
};

const deleteAttendance = (id: string) => {
    router.delete(route('staff.attendance.destroy', id));
};

const clockIn = (staffId: string) => {
    router.post(route('staff.attendance.clock-in', staffId), {}, {
        preserveScroll: true,
    });
};

const clockOut = (staffId: string) => {
    router.post(route('staff.attendance.clock-out', staffId), {}, {
        preserveScroll: true,
    });
};

const getStatusBadgeVariant = (status: string) => {
    switch (status) {
        case 'present':
            return 'default';
        case 'absent':
            return 'destructive';
        case 'half-day':
            return 'secondary';
        case 'leave':
            return 'outline';
        default:
            return 'outline';
    }
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('en-GB');
};

const formatDateTime = (datetime: string | null) => {
    if (!datetime) return '-';
    return new Date(datetime).toLocaleTimeString('en-US', { 
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

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',
    }).format(amount);
};

const getDeleteDescription = (record: AttendanceRecord) => {
    return `This action cannot be undone. This will permanently delete the attendance record for "${record.staff.name}" on ${formatDate(record.date)}.`;
};
</script>

<template>
    <Head title="Staff Attendance" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">Staff Attendance</h1>
                    <p class="text-muted-foreground mt-2">Track staff clock-ins, clock-outs, and overtime</p>
                </div>
            </div>

            <Card>
                <CardContent>
                    <!-- Search and Filters -->
                    <div class="mb-4 space-y-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1"></div>
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
                                <Label for="staff-filter">Staff Member</Label>
                                <SimpleCombobox
                                    v-model="staffFilter"
                                    :options="staffOptions"
                                    placeholder="All Staff"
                                    class="w-full"
                                />
                            </div>
                            
                            <div class="space-y-2">
                                <Label for="date-from">From Date</Label>
                                <Input 
                                    v-model="dateFrom" 
                                    type="date" 
                                    class="w-full" 
                                />
                            </div>

                            <div class="space-y-2">
                                <Label for="date-to">To Date</Label>
                                <Input 
                                    v-model="dateTo" 
                                    type="date" 
                                    class="w-full" 
                                />
                            </div>

                            <div class="space-y-2">
                                <Label for="status-filter">Status</Label>
                                <SimpleCombobox
                                    v-model="statusFilter"
                                    :options="statusOptions"
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
                                    <TableHead>Date</TableHead>
                                    <TableHead>Staff</TableHead>
                                    <TableHead>Employee ID</TableHead>
                                    <TableHead>Clock In</TableHead>
                                    <TableHead>Clock Out</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Late</TableHead>
                                    <TableHead>Overtime</TableHead>
                                    <TableHead>OT Pay</TableHead>
                                    <TableHead class="text-center">Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-if="!props.attendance?.data?.length">
                                    <TableCell colspan="10" class="text-center py-4">
                                        No attendance records found
                                    </TableCell>
                                </TableRow>
                                <TableRow v-for="record in props.attendance?.data || []" :key="record.id">
                                    <TableCell>{{ formatDate(record.date) }}</TableCell>
                                    <TableCell class="font-medium">{{ record.staff.name }}</TableCell>
                                    <TableCell>{{ record.staff.employee_id }}</TableCell>
                                    <TableCell>
                                        <div class="flex flex-col">
                                            <span>{{ formatDateTime(record.clock_in) }}</span>
                                            <span v-if="record.is_late" class="text-xs text-red-600">
                                                Late by {{ record.late_minutes }}m
                                            </span>
                                        </div>
                                    </TableCell>
                                    <TableCell>{{ formatDateTime(record.clock_out) }}</TableCell>
                                    <TableCell>
                                        <Badge :variant="getStatusBadgeVariant(record.status)">
                                            {{ record.status }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <span v-if="record.is_late" class="text-red-600 font-medium">
                                            {{ record.late_minutes }}m
                                        </span>
                                        <span v-else class="text-muted-foreground">-</span>
                                    </TableCell>
                                    <TableCell>
                                        <span v-if="record.overtime_minutes > 0" class="text-sm font-medium">
                                            {{ formatOvertimeHours(record.overtime_minutes) }}
                                        </span>
                                        <span v-else class="text-muted-foreground">-</span>
                                    </TableCell>
                                    <TableCell>
                                        <span v-if="record.overtime_pay > 0" class="text-green-600 font-medium">
                                            {{ formatCurrency(record.overtime_pay) }}
                                        </span>
                                        <span v-else class="text-muted-foreground">-</span>
                                    </TableCell>
                                    <TableCell class="flex gap-1 justify-center">
                                        <!-- Clock In (if not done) -->
                                        <Tooltip>
                                            <TooltipTrigger asChild>
                                                <Button 
                                                    v-if="!record.clock_in"
                                                    variant="outline" 
                                                    size="sm"
                                                    @click="clockIn(record.staff_id)"
                                                >
                                                    <LogIn class="h-4 w-4" />
                                                </Button>
                                            </TooltipTrigger>
                                            <TooltipContent v-if="!record.clock_in">
                                                <p>Clock in</p>
                                            </TooltipContent>
                                        </Tooltip>

                                        <!-- Clock Out (if clocked in but not out) -->
                                        <Tooltip>
                                            <TooltipTrigger asChild>
                                                <Button 
                                                    v-if="record.clock_in && !record.clock_out"
                                                    variant="outline" 
                                                    size="sm"
                                                    @click="clockOut(record.staff_id)"
                                                >
                                                    <LogOut class="h-4 w-4" />
                                                </Button>
                                            </TooltipTrigger>
                                            <TooltipContent v-if="record.clock_in && !record.clock_out">
                                                <p>Clock out</p>
                                            </TooltipContent>
                                        </Tooltip>

                                        <!-- Delete -->
                                        <ConfirmationModal
                                            title="Are you sure?"
                                            :description="getDeleteDescription(record)"
                                            confirm-text="Delete"
                                            tooltip="Delete attendance record"
                                            @confirm="deleteAttendance(record.id)"
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

