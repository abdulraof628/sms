<script setup>
import { ref, onMounted, computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import { CalendarIcon, PencilIcon } from 'lucide-vue-next';

const breadcrumbs = [
    {
        title: 'Daily Reports',
        href: '/daily',
    },
];

const loading = ref(true);
const reports = ref([]);
const message = ref('');
const isError = ref(false);

// Get user role from Inertia page props
const page = usePage();
const userRole = computed(() => page.props.auth.user.roles?.[0]?.name || '');

// Check if current user is a teacher
const isTeacher = computed(() => userRole.value === 'teacher');

// Format date for display
const formatDate = (dateString) => {
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        weekday: 'long' 
    };
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

// Fetch daily reports
const fetchDailyReports = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/daily-reports');
        reports.value = response.data.data || [];
    } catch (error) {
        console.error('Error fetching daily reports:', error);
        showMessage('Failed to load daily reports', true);
    } finally {
        loading.value = false;
    }
};

// Navigate to create new report
const createNewReport = () => {
    window.location.href = '/daily/create';
};

// Navigate to edit report
const editReport = (report) => {
    window.location.href = `/daily/${report.id}/edit`;
};

// Initial data fetch
onMounted(() => {
    fetchDailyReports();
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Daily Reports" />

        <div class="space-y-6">
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
                </div>

                <div v-else class="space-y-8">
                    <Card v-for="report in reports" :key="report.id" class="relative">
                        <CardHeader class="pb-2">
                            <div class="flex items-start justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="bg-primary/10 text-primary rounded-full p-2">
                                        <CalendarIcon class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <CardTitle class="text-lg">
                                            {{ formatDate(report.date) }}
                                        </CardTitle>
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
                        </CardHeader>
                        <CardContent class="pt-0">
                            <div class="prose text-foreground max-w-none text-sm">
                                <p class="whitespace-pre-line">{{ report.report }}</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
