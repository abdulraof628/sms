<template>
    <Head title="Create Daily Report" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div>
                            <h2 class="text-2xl font-bold tracking-tight">Create Daily Report</h2>
                            <p class="text-muted-foreground">Add a new daily report for a student</p>
                        </div>

                        <div v-if="form.recentlySuccessful" class="rounded-md bg-green-100 p-4 text-green-700">
                            Daily report created successfully!
                        </div>

                        <form @submit.prevent="submitForm" class="space-y-6">
                            <div class="grid gap-2">
                                <Label for="student_id">Student</Label>
                                <select
                                    id="student_id"
                                    v-model="form.student_id"
                                    :disabled="form.processing"
                                    required
                                    class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-10 w-full rounded-md border px-3 py-2 text-sm file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50"
                                    :class="{ 'border-destructive': form.errors.student_id }"
                                >
                                    <option value="" disabled>Select a student</option>
                                    <option v-for="student in students" :key="student.id" :value="student.id">
                                        {{ student.name }}
                                    </option>
                                </select>
                                <p v-if="form.errors.student_id" class="text-destructive text-sm font-medium">
                                    {{ form.errors.student_id }}
                                </p>
                            </div>

                            <div class="grid gap-2">
                                <Label for="date">Date</Label>
                                <Input
                                    type="date"
                                    id="date"
                                    v-model="form.date"
                                    :disabled="form.processing"
                                    required
                                    :class="{ 'border-destructive': form.errors.date }"
                                />
                                <p v-if="form.errors.date" class="text-destructive text-sm font-medium">
                                    {{ form.errors.date }}
                                </p>
                            </div>

                            <div class="grid gap-2">
                                <Label for="report">Report</Label>
                                <Textarea
                                    id="report"
                                    v-model="form.report"
                                    :disabled="form.processing"
                                    rows="6"
                                    required
                                    placeholder="Enter daily report details..."
                                    class="min-h-[120px]"
                                    :class="{ 'border-destructive': form.errors.report }"
                                />
                                <p v-if="form.errors.report" class="text-destructive text-sm font-medium">
                                    {{ form.errors.report }}
                                </p>
                            </div>

                            <div class="grid gap-2">
                                <Label>Status</Label>
                                <RadioGroup v-model="form.status" class="flex space-x-4" :disabled="form.processing">
                                    <div class="flex items-center space-x-2">
                                        <RadioGroupItem id="draft" value="draft" />
                                        <Label for="draft" class="font-normal">Draft</Label>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <RadioGroupItem id="published" value="published" />
                                        <Label for="published" class="font-normal">Publish</Label>
                                    </div>
                                </RadioGroup>
                                <p v-if="form.errors.status" class="text-destructive text-sm font-medium">
                                    {{ form.errors.status }}
                                </p>
                            </div>

                            <div class="flex justify-end space-x-2">
                                <Button type="button" variant="outline" :disabled="form.processing" @click="$inertia.visit(route('daily.index'))">
                                    Cancel
                                </Button>
                                <Button type="submit" :disabled="form.processing">
                                    <span v-if="form.processing">Saving...</span>
                                    <span v-else>Save Report</span>
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const breadcrumbs = [
    {
        title: 'Daily Reports',
        href: '/daily',
    },
    {
        title: 'Create Report',
        href: '/daily/create',
    },
];

// Use Inertia's useForm for form handling
const form = useForm({
    student_id: '',
    date: new Date().toISOString().split('T')[0],
    report: '',
    status: 'draft',
});

// Students list from server
const students = ref([]);

// Handle form submission
const submitForm = () => {
    form.post(route('daily-reports.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // Reset form on success
            form.reset();
            form.date = new Date().toISOString().split('T')[0];
            form.status = 'draft';
        },
        onError: (errors) => {
            console.error('Error creating daily report:', errors);
        },
    });
};
</script>
