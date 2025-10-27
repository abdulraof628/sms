<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { SimpleCombobox } from '@/components/ui';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { TimePicker } from '@/components/ui/time-picker';
import { DatePicker } from '@/components/ui/date-picker';
import InputError from '@/components/InputError.vue';
import { type BreadcrumbItem } from '@/types';
import { ArrowLeft, Upload } from 'lucide-vue-next';

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
        title: 'Create',
        href: '/staff/create',
    },
];

const form = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    state: '',
    postal_code: '',
    country: '',
    photo: null as File | null,
    employee_id: '',
    position: '',
    department: '',
    hire_date: '',
    role: 'teacher',
    shift_start: '09:00',
    shift_end: '17:00',
    weekly_hours: 40,
    working_days: [1, 2, 3, 4, 5], // Monday to Friday
    annual_leave_balance: 12,
    sick_leave_balance: 10,
    casual_leave_balance: 8,
    overtime_enabled: false,
    overtime_rate: 1.5,
});

const weekDays = [
    { value: 1, label: 'Monday' },
    { value: 2, label: 'Tuesday' },
    { value: 3, label: 'Wednesday' },
    { value: 4, label: 'Thursday' },
    { value: 5, label: 'Friday' },
    { value: 6, label: 'Saturday' },
    { value: 0, label: 'Sunday' },
];

const roleOptions = [
    { value: 'teacher', label: 'Teacher' },
    { value: 'principal', label: 'Principal' },
    { value: 'admin', label: 'Admin' },
];

// Helper function to check if a day is selected
const isDaySelected = (day: number) => {
    // Convert form.working_days to numbers for comparison
    const workingDaysAsNumbers = form.working_days.map(d => typeof d === 'string' ? parseInt(d) : d);
    return workingDaysAsNumbers.includes(day);
};

// Helper function to toggle a working day
const toggleWorkingDay = (day: number, checked: boolean) => {
    // Convert form.working_days to numbers for consistent handling
    const workingDaysAsNumbers = form.working_days.map(d => typeof d === 'string' ? parseInt(d) : d);
    const index = workingDaysAsNumbers.indexOf(day);
    
    if (checked && index === -1) {
        form.working_days.push(day);
    } else if (!checked && index > -1) {
        // Find the actual index in the original array (might be string)
        const originalIndex = form.working_days.findIndex(d => (typeof d === 'string' ? parseInt(d) : d) === day);
        if (originalIndex > -1) {
            form.working_days.splice(originalIndex, 1);
        }
    }
};

const photoPreview = ref<string | null>(null);

const handlePhotoChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.photo = target.files[0];
        
        // Create preview URL
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreview.value = e.target?.result as string;
        };
        reader.readAsDataURL(target.files[0]);
    }
};

const removePhoto = () => {
    form.photo = null;
    photoPreview.value = null;
    // Reset file input
    const fileInput = document.getElementById('photo') as HTMLInputElement;
    if (fileInput) {
        fileInput.value = '';
    }
};

const getInitials = (name: string | undefined | null) => {
    if (!name) return '??';
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
};

const submit = () => {
    form.post(route('staff.store'), {
        preserveScroll: true,
        forceFormData: true, // Required for file uploads
    });
};
</script>

<template>
    <Head title="Add Staff" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">Create New Staff</h1>
                    <p class="text-muted-foreground mt-2">Add a new staff member to the system</p>
                </div>
                <div>
                    <Button variant="outline" @click="router.visit(route('staff.index'))">
                        <ArrowLeft class="h-4 w-4 mr-2" />
                        Back to Staff
                    </Button>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Personal Information Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Personal Information</CardTitle>
                        <CardDescription>Basic personal details and contact information</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="name">Full Name <span class="text-red-500">*</span></Label>
                                    <Input id="name" v-model="form.name" required />
                                    <InputError :message="form.errors.name" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="employee_id">Employee ID</Label>
                                    <Input id="employee_id" v-model="form.employee_id" placeholder="Leave empty for auto-generation" />
                                    <InputError :message="form.errors.employee_id" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="email">Email <span class="text-red-500">*</span></Label>
                                    <Input id="email" v-model="form.email" type="email" required />
                                    <InputError :message="form.errors.email" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="phone">Phone</Label>
                                    <Input id="phone" v-model="form.phone" type="tel" />
                                    <InputError :message="form.errors.phone" />
                                </div>

                                <div class="space-y-2 md:col-span-2">
                                    <Label for="photo">Photo</Label>
                                    
                                    <!-- Photo Preview -->
                                    <div v-if="photoPreview || form.name" class="flex items-center gap-4 mb-4">
                                        <Avatar class="h-24 w-24">
                                            <AvatarImage 
                                                v-if="photoPreview" 
                                                :src="photoPreview" 
                                                :alt="form.name"
                                                class="object-cover"
                                            />
                                            <AvatarFallback class="text-2xl">{{ getInitials(form.name) }}</AvatarFallback>
                                        </Avatar>
                                        <div class="flex-1">
                                            <p class="text-sm text-muted-foreground mb-2">
                                                {{ photoPreview ? 'Photo preview' : 'No photo selected' }}
                                            </p>
                                            <Button 
                                                v-if="photoPreview" 
                                                type="button" 
                                                variant="outline" 
                                                size="sm"
                                                @click="removePhoto"
                                            >
                                                Remove Photo
                                            </Button>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <Input id="photo" type="file" accept="image/*" @change="handlePhotoChange" class="flex-1" />
                                        <Upload class="h-4 w-4 text-muted-foreground" />
                                    </div>
                                    <p class="text-xs text-muted-foreground">Recommended: Square image, max 2MB</p>
                                    <InputError :message="form.errors.photo" />
                                </div>
                            </div>
                    </CardContent>
                </Card>

                <!-- Address Information Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Address Information</CardTitle>
                        <CardDescription>Physical location and contact details</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="space-y-2 md:col-span-2">
                                <Label for="address">Address</Label>
                                <Textarea id="address" v-model="form.address" rows="2" />
                                <InputError :message="form.errors.address" />
                            </div>

                            <div class="space-y-2">
                                <Label for="city">City</Label>
                                <Input id="city" v-model="form.city" />
                                <InputError :message="form.errors.city" />
                            </div>

                            <div class="space-y-2">
                                <Label for="state">State</Label>
                                <Input id="state" v-model="form.state" />
                                <InputError :message="form.errors.state" />
                            </div>

                            <div class="space-y-2">
                                <Label for="postal_code">Postal Code</Label>
                                <Input id="postal_code" v-model="form.postal_code" />
                                <InputError :message="form.errors.postal_code" />
                            </div>

                            <div class="space-y-2">
                                <Label for="country">Country</Label>
                                <Input id="country" v-model="form.country" />
                                <InputError :message="form.errors.country" />
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Employment Details Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Employment Details</CardTitle>
                        <CardDescription>Job information and employment settings</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="position">Position</Label>
                                    <Input id="position" v-model="form.position" placeholder="e.g., Math Teacher" />
                                    <InputError :message="form.errors.position" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="department">Department</Label>
                                    <Input id="department" v-model="form.department" placeholder="e.g., Mathematics" />
                                    <InputError :message="form.errors.department" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="role">Role <span class="text-red-500">*</span></Label>
                                    <SimpleCombobox 
                                        v-model="form.role" 
                                        :options="roleOptions"
                                        placeholder="Select role"
                                        class="w-full"
                                    />
                                    <InputError :message="form.errors.role" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="hire_date">Hire Date</Label>
                                    <DatePicker 
                                        id="hire_date"
                                        v-model="form.hire_date"
                                        placeholder="Select hire date"
                                    />
                                    <InputError :message="form.errors.hire_date" />
                                </div>
                            </div>
                    </CardContent>
                </Card>

                <!-- Working Hours & Schedule Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Working Hours & Schedule</CardTitle>
                        <CardDescription>Shift times, weekly hours, and working days</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="shift_start">Shift Start Time</Label>
                                    <TimePicker 
                                        id="shift_start"
                                        v-model="form.shift_start"
                                        placeholder="Select start time"
                                    />
                                    <InputError :message="form.errors.shift_start" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="shift_end">Shift End Time</Label>
                                    <TimePicker 
                                        id="shift_end"
                                        v-model="form.shift_end"
                                        placeholder="Select end time"
                                    />
                                    <InputError :message="form.errors.shift_end" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="weekly_hours">Weekly Hours</Label>
                                    <Input id="weekly_hours" v-model="form.weekly_hours" type="number" min="1" max="168" />
                                    <InputError :message="form.errors.weekly_hours" />
                                </div>

                                <div class="space-y-2 md:col-span-2">
                                    <Label>Working Days</Label>
                                    <div class="flex flex-wrap gap-3">
                                        <div v-for="day in weekDays" :key="day.value" class="flex items-center gap-2">
                                            <Checkbox 
                                                :id="`day-${day.value}`" 
                                                :model-value="isDaySelected(day.value)"
                                                @update:modelValue="(checked) => toggleWorkingDay(day.value, checked)"
                                            />
                                            <Label :for="`day-${day.value}`" class="cursor-pointer">{{ day.label }}</Label>
                                        </div>
                                    </div>
                                    <InputError :message="form.errors.working_days" />
                                </div>
                            </div>
                    </CardContent>
                </Card>

                <!-- Leave Management Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Leave Management</CardTitle>
                        <CardDescription>Annual, sick, and casual leave balances</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid gap-6 md:grid-cols-3">
                                <div class="space-y-2">
                                    <Label for="annual_leave">Annual Leave (days)</Label>
                                    <Input id="annual_leave" v-model="form.annual_leave_balance" type="number" min="0" />
                                    <InputError :message="form.errors.annual_leave_balance" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="sick_leave">Sick Leave (days)</Label>
                                    <Input id="sick_leave" v-model="form.sick_leave_balance" type="number" min="0" />
                                    <InputError :message="form.errors.sick_leave_balance" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="casual_leave">Casual Leave (days)</Label>
                                    <Input id="casual_leave" v-model="form.casual_leave_balance" type="number" min="0" />
                                    <InputError :message="form.errors.casual_leave_balance" />
                                </div>
                            </div>
                    </CardContent>
                </Card>

                <!-- Overtime Settings Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Overtime Settings</CardTitle>
                        <CardDescription>Configure overtime tracking and rates</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="flex items-center gap-2">
                                <Checkbox 
                                    id="overtime_enabled" 
                                    :model-value="form.overtime_enabled"
                                    @update:modelValue="(checked) => form.overtime_enabled = checked"
                                />
                                <Label for="overtime_enabled" class="cursor-pointer">Enable Overtime Tracking</Label>
                            </div>

                            <div v-if="form.overtime_enabled" class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label for="overtime_rate">Overtime Rate Multiplier</Label>
                                    <Input 
                                        id="overtime_rate" 
                                        v-model="form.overtime_rate" 
                                        type="number" 
                                        step="0.1" 
                                        min="1" 
                                        max="5"
                                        placeholder="e.g., 1.5 for 1.5x regular pay"
                                    />
                                    <p class="text-xs text-muted-foreground">Default is 1.5x (time and a half)</p>
                                    <InputError :message="form.errors.overtime_rate" />
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Actions -->
                <div class="flex justify-end gap-1">
                    <Button type="button" variant="outline" @click="router.visit(route('staff.index'))">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        Create Staff Member
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
