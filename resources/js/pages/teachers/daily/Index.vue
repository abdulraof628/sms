<template>
    <Head title="Daily Reports" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold tracking-tight">Daily Reports</h2>
                                <p class="text-muted-foreground">View and manage daily reports</p>
                            </div>
                            <Button v-if="isTeacher" @click="createNewReport">
                                Create New Report
                            </Button>
                        </div>

                        <div v-if="message" class="rounded-md p-4" :class="isError ? 'bg-destructive/10 text-destructive' : 'bg-green-100 text-green-700'">
                            {{ message }}
                        </div>

                        <div v-if="loading" class="flex items-center justify-center py-12">
                            <div class="h-8 w-8 animate-spin rounded-full border-b-2 border-primary"></div>
                        </div>

                        <div v-else class="space-y-8">
                            <div v-if="reports.length === 0" class="rounded-lg border py-12 text-center">
                                <p class="text-muted-foreground">No daily reports found.</p>
                                <Button v-if="isTeacher" class="mt-4" @click="createNewReport">
                                    Create Your First Report
                                </Button>
                            </div>

                            <div v-else class="space-y-6">
                                <div v-for="(report, index) in reports" :key="report.id" class="rounded-lg border p-6 hover:bg-accent/50 transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex items-start space-x-4">
                                            <div class="bg-primary/10 text-primary rounded-full p-3">
                                                <CalendarIcon class="h-5 w-5" />
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-medium">
                                                    {{ formatDate(report.date) }}
                                                </h3>
                                                <p class="text-muted-foreground text-sm">
                                                    {{ isTeacher ? `For: ${report.student?.name || 'N/A'}` : `By: ${report.teacher?.name || 'N/A'}` }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <Badge
                                                variant="outline"
                                                :class="{
                                                    'border-yellow-200 bg-yellow-50 text-yellow-800': report.status === 'draft',
                                                    'border-green-200 bg-green-50 text-green-800': report.status === 'published',
                                                }"
                                            >
                                                {{ report.status }}
                                            </Badge>
                                            <Button 
                                                variant="ghost" 
                                                size="icon" 
                                                @click="viewReport(report)"
                                                title="View Details"
                                            >
                                                <EyeIcon class="h-4 w-4" />
                                            </Button>
                                            <Button 
                                                v-if="isTeacher"
                                                variant="ghost" 
                                                size="icon" 
                                                @click="editReport(report)"
                                                title="Edit"
                                            >
                                                <PencilIcon class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 pl-11">
                                        <div class="prose text-foreground max-w-none text-sm">
                                            <p class="line-clamp-3 whitespace-pre-line">{{ report.report }}</p>
                                        </div>
                                        <Button 
                                            variant="link" 
                                            class="mt-2 h-auto p-0 text-sm"
                                            @click="viewReport(report)"
                                        >
                                            Read more
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import { CalendarIcon, PencilIcon, EyeIcon } from 'lucide-vue-next';

const breadcrumbs = [
    {
        title: 'Daily Reports',
        href: '/daily',
    },
];

const loading = ref(false);
const reports = ref([]);
const message = ref('');
const isError = ref(false);

// Get user role from Inertia page props
const page = usePage();
const isTeacher = computed(() => {
    const user = page.props?.auth?.user;
    const roles = user?.roles || [];
    return roles.some(role => role?.name === 'teacher');
});

// Format date for display
const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
};

// Show message helper
const showMessage = (msg, error = false) => {
    message.value = msg;
    isError.value = error;
    setTimeout(() => {
        message.value = '';
        isError.value = false;
    }, 3000);
};

// Navigate to create new report
const createNewReport = () => {
    window.location.href = route('daily.create');
};

// Navigate to view report
const viewReport = (report) => {
    window.location.href = route('daily.show', report.id);
};

// Navigate to edit report
const editReport = (report) => {
    window.location.href = route('daily.edit', report.id);
};
</script>
