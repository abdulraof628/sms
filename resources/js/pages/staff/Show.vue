<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Separator } from '@/components/ui/separator';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type BreadcrumbItem } from '@/types';
import { 
    Mail, 
    Phone, 
    MapPin, 
    Calendar, 
    Clock, 
    Briefcase,
    CalendarDays,
    TrendingUp,
    Edit,
    ArrowLeft
} from 'lucide-vue-next';

interface Tenant {
    id: string;
    name: string;
}

interface Attendance {
    id: string;
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

interface Staff {
    id: string;
    name: string;
    email: string;
    phone: string | null;
    address: string | null;
    city: string | null;
    state: string | null;
    postal_code: string | null;
    country: string | null;
    photo: string | null;
    employee_id: string;
    position: string | null;
    department: string | null;
    hire_date: string | null;
    salary: number | null;
    role: 'admin' | 'principal' | 'teacher';
    shift_start: string | null;
    shift_end: string | null;
    weekly_hours: number;
    working_days: number[] | null;
    annual_leave_balance: number;
    sick_leave_balance: number;
    casual_leave_balance: number;
    total_leave_balance: number;
    overtime_enabled: boolean;
    overtime_rate: number;
    total_overtime_minutes: number;
    is_active: boolean;
    tenant?: Tenant;
    attendances?: Attendance[];
    created_at: string;
}

interface Props {
    staff: Staff;
    overtimePay: number;
}

const props = defineProps<Props>();

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
        title: props.staff.name,
        href: `/staff/${props.staff.id}`,
    },
];

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

const getInitials = (name: string | undefined | null) => {
    if (!name) return '??';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
};

const formatDate = (date: string | null) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('en-GB');
};

const formatTime = (time: string | null) => {
    if (!time) return '-';
    return new Date('1970-01-01T' + time).toLocaleTimeString('en-US', { 
        hour: '2-digit', 
        minute: '2-digit',
        hour12: true 
    });
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

const weekDayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

const getWorkingDaysText = (days: number[] | null) => {
    if (!days || days.length === 0) return 'Not set';
    return days.sort().map(d => weekDayNames[d]).join(', ');
};
</script>

<template>
    <Head :title="`Staff - ${staff.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">{{ staff.name }}</h1>
                    <p class="text-muted-foreground mt-2">Employee ID: {{ staff.employee_id }}</p>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('staff.index')">
                        <Button variant="outline">
                            <ArrowLeft class="h-4 w-4 mr-2" />
                            Back to Staff
                        </Button>
                    </Link>
                    <Link :href="route('staff.edit', staff.id)">
                        <Button>
                            <Edit class="h-4 w-4 mr-2" />
                            Edit Profile
                        </Button>
                    </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Profile & Contact -->
                <div class="space-y-6">
                    <!-- Profile Card -->
                    <Card>
                        <CardContent class="pt-6">
                            <div class="flex flex-col items-center text-center">
                                <Avatar class="h-32 w-32 mb-4">
                                    <AvatarImage 
                                        v-if="staff.photo" 
                                        :src="`/storage/${staff.photo}`" 
                                        :alt="staff.name"
                                        class="object-cover"
                                        loading="lazy"
                                    />
                                    <AvatarFallback class="text-3xl">{{ getInitials(staff.name) }}</AvatarFallback>
                                </Avatar>
                                <h3 class="text-xl font-semibold">{{ staff.name }}</h3>
                                <p class="text-muted-foreground mb-3">{{ staff.position || 'Staff Member' }}</p>
                                <div class="flex gap-2 mb-4">
                                    <Badge :variant="getRoleBadgeVariant(staff.role)">
                                        {{ staff.role.charAt(0).toUpperCase() + staff.role.slice(1) }}
                                    </Badge>
                                    <Badge :variant="staff.is_active ? 'default' : 'secondary'">
                                        {{ staff.is_active ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </div>
                            </div>

                            <Separator class="my-4" />

                            <div class="space-y-3">
                                <div class="flex items-center gap-2 text-sm">
                                    <Mail class="h-4 w-4 text-muted-foreground" />
                                    <span>{{ staff.email }}</span>
                                </div>
                                <div v-if="staff.phone" class="flex items-center gap-2 text-sm">
                                    <Phone class="h-4 w-4 text-muted-foreground" />
                                    <span>{{ staff.phone }}</span>
                                </div>
                                <div v-if="staff.address" class="flex items-start gap-2 text-sm">
                                    <MapPin class="h-4 w-4 text-muted-foreground mt-0.5" />
                                    <span>{{ staff.address }}<br v-if="staff.city" />{{ staff.city }}<template v-if="staff.state">, {{ staff.state }}</template> {{ staff.postal_code }}</span>
                                </div>
                                <div v-if="staff.hire_date" class="flex items-center gap-2 text-sm">
                                    <Calendar class="h-4 w-4 text-muted-foreground" />
                                    <span>Joined {{ formatDate(staff.hire_date) }}</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Leave Balance Card -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <CalendarDays class="h-5 w-5" />
                                Leave Balance
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-muted-foreground">Annual Leave</span>
                                <span class="font-semibold">{{ staff.annual_leave_balance }} days</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-muted-foreground">Sick Leave</span>
                                <span class="font-semibold">{{ staff.sick_leave_balance }} days</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-muted-foreground">Casual Leave</span>
                                <span class="font-semibold">{{ staff.casual_leave_balance }} days</span>
                            </div>
                            <Separator />
                            <div class="flex justify-between items-center">
                                <span class="font-medium">Total Available</span>
                                <span class="font-bold text-lg">{{ staff.total_leave_balance }} days</span>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Overtime Card -->
                    <Card v-if="staff.overtime_enabled">
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <TrendingUp class="h-5 w-5" />
                                Overtime
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-muted-foreground">Total Hours</span>
                                <span class="font-semibold">{{ formatOvertimeHours(staff.total_overtime_minutes) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-muted-foreground">Overtime Rate</span>
                                <span class="font-semibold">{{ staff.overtime_rate }}x</span>
                            </div>
                            <Separator />
                            <div class="flex justify-between items-center">
                                <span class="font-medium">Estimated Pay</span>
                                <span class="font-bold text-lg text-green-600">{{ formatCurrency(overtimePay) }}</span>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Right Column - Details & Attendance -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Employment Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Briefcase class="h-5 w-5" />
                                Employment Details
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Position</p>
                                <p class="text-sm">{{ staff.position || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Department</p>
                                <p class="text-sm">{{ staff.department || '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Role</p>
                                <Badge :variant="getRoleBadgeVariant(staff.role)">
                                    {{ staff.role.charAt(0).toUpperCase() + staff.role.slice(1) }}
                                </Badge>
                            </div>
                            <div v-if="staff.salary">
                                <p class="text-sm font-medium text-muted-foreground">Monthly Salary</p>
                                <p class="text-sm">{{ formatCurrency(staff.salary) }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Working Hours -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Clock class="h-5 w-5" />
                                Working Hours & Schedule
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Shift Timing</p>
                                <p class="text-sm">{{ formatTime(staff.shift_start) }} - {{ formatTime(staff.shift_end) }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-muted-foreground">Weekly Hours</p>
                                <p class="text-sm">{{ staff.weekly_hours }} hours</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-muted-foreground mb-1">Working Days</p>
                                <p class="text-sm">{{ getWorkingDaysText(staff.working_days) }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Recent Attendance -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Recent Attendance</CardTitle>
                            <CardDescription>Last 10 attendance records</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <Table v-if="staff.attendances && staff.attendances.length > 0">
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Date</TableHead>
                                        <TableHead>Clock In</TableHead>
                                        <TableHead>Clock Out</TableHead>
                                        <TableHead>Status</TableHead>
                                        <TableHead>Overtime</TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow v-for="attendance in staff.attendances" :key="attendance.id">
                                        <TableCell>{{ formatDate(attendance.date) }}</TableCell>
                                        <TableCell>
                                            <div class="flex flex-col">
                                                <span>{{ formatDateTime(attendance.clock_in) }}</span>
                                                <span v-if="attendance.is_late" class="text-xs text-red-600">
                                                    Late by {{ attendance.late_minutes }}m
                                                </span>
                                            </div>
                                        </TableCell>
                                        <TableCell>{{ formatDateTime(attendance.clock_out) }}</TableCell>
                                        <TableCell>
                                            <Badge :variant="getStatusBadgeVariant(attendance.status)">
                                                {{ attendance.status }}
                                            </Badge>
                                        </TableCell>
                                        <TableCell>
                                            <span v-if="attendance.overtime_minutes > 0" class="text-sm">
                                                {{ formatOvertimeHours(attendance.overtime_minutes) }}
                                                <span v-if="attendance.overtime_pay > 0" class="text-green-600">
                                                    ({{ formatCurrency(attendance.overtime_pay) }})
                                                </span>
                                            </span>
                                            <span v-else class="text-muted-foreground">-</span>
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                            <div v-else class="text-center py-8 text-muted-foreground">
                                No attendance records found
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
