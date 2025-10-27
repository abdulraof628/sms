<template>
    <Head title="Daily Reports" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">Daily Reports</h1>
                    <p class="text-muted-foreground mt-2">View and manage daily reports</p>
                </div>
                <div v-if="isTeacher">
                    <Button @click="createNewReport">
                        <Plus class="h-4 w-4 mr-2" />
                        Create New Report
                    </Button>
                </div>
            </div>

            <Card>
                <CardContent>
                    <!-- Search and Filters -->
                    <div class="mb-4 space-y-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <Input v-model="search" placeholder="Search by date, name, or content..." />
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
                                <Label for="status-filter">Status</Label>
                                <SimpleCombobox
                                    v-model="statusFilter"
                                    :options="statusOptions"
                                    placeholder="All Status"
                                    class="w-full"
                                />
                            </div>
                            
                            <div class="md:col-span-3 flex gap-2 items-end">
                                <Button variant="outline" @click="clearFilters" size="sm">
                                    <X class="h-4 w-4 mr-2" />
                                    Clear Filters
                                </Button>
                            </div>
                        </div>
                    </div>

                    <div v-if="message" class="rounded-md p-4 mb-4" :class="isError ? 'bg-destructive/10 text-destructive' : 'bg-green-100 text-green-700'">
                        {{ message }}
                    </div>

                    <div v-if="loading" class="flex items-center justify-center py-12">
                        <div class="h-8 w-8 animate-spin rounded-full border-b-2 border-primary"></div>
                    </div>

                    <div v-else class="space-y-4">
                        <div v-if="filteredReports.length === 0" class="rounded-lg border py-12 text-center">
                            <p class="text-muted-foreground">No daily reports found.</p>
                            <Button v-if="isTeacher" class="mt-4" @click="createNewReport">
                                <Plus class="h-4 w-4 mr-2" />
                                Create Your First Report
                            </Button>
                        </div>

                        <div v-else class="space-y-3">
                            <div v-for="(report, index) in filteredReports" :key="report.id" class="rounded-lg border p-4 hover:bg-accent/50 transition-colors">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-start space-x-4 flex-1">
                                        <div class="bg-primary/10 text-primary rounded-full p-2 flex-shrink-0">
                                            <CalendarIcon class="h-4 w-4" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-medium">
                                                {{ formatDate(report.date) }}
                                            </h3>
                                            <p class="text-muted-foreground text-xs mt-1">
                                                {{ isTeacher ? `For: ${report.student?.name || 'N/A'}` : `By: ${report.teacher?.name || 'N/A'}` }}
                                            </p>
                                            <p class="text-foreground text-sm mt-2 line-clamp-2">{{ report.report }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <Badge
                                            :variant="report.status === 'draft' ? 'secondary' : 'default'"
                                        >
                                            {{ report.status }}
                                        </Badge>
                                        <Tooltip>
                                            <TooltipTrigger asChild>
                                                <Button 
                                                    variant="outline" 
                                                    size="sm"
                                                    @click="viewReport(report)"
                                                >
                                                    <EyeIcon class="h-4 w-4" />
                                                </Button>
                                            </TooltipTrigger>
                                            <TooltipContent>
                                                <p>View details</p>
                                            </TooltipContent>
                                        </Tooltip>
                                        <Tooltip v-if="isTeacher">
                                            <TooltipTrigger asChild>
                                                <Button 
                                                    variant="outline" 
                                                    size="sm"
                                                    @click="editReport(report)"
                                                >
                                                    <PencilIcon class="h-4 w-4" />
                                                </Button>
                                            </TooltipTrigger>
                                            <TooltipContent>
                                                <p>Edit report</p>
                                            </TooltipContent>
                                        </Tooltip>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Card, CardContent } from '@/components/ui/card';
import { Tooltip, TooltipTrigger, TooltipContent } from '@/components/ui/tooltip';
import { SimpleCombobox } from '@/components/ui';
import AppLayout from '@/layouts/AppLayout.vue';
import { CalendarIcon, PencilIcon, EyeIcon, Filter, ChevronDown, X, Plus } from 'lucide-vue-next';

const breadcrumbs = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Daily Reports',
        href: '/daily',
    },
];

const loading = ref(false);
const reports = ref([]);
const message = ref('');
const isError = ref(false);
const search = ref('');
const statusFilter = ref('');
const showFilters = ref(false);

const statusOptions = [
    { value: '', label: 'All Status' },
    { value: 'draft', label: 'Draft' },
    { value: 'published', label: 'Published' },
];

// Get user role from Inertia page props
const page = usePage();
const isTeacher = computed(() => {
    const user = page.props?.auth?.user;
    const roles = user?.roles || [];
    return roles.some(role => role?.name === 'teacher');
});

// Filter reports based on search and status
const filteredReports = computed(() => {
    return reports.value.filter(report => {
        const matchesSearch = !search.value || 
            report.report.toLowerCase().includes(search.value.toLowerCase()) ||
            formatDate(report.date).toLowerCase().includes(search.value.toLowerCase()) ||
            (report.student?.name || '').toLowerCase().includes(search.value.toLowerCase()) ||
            (report.teacher?.name || '').toLowerCase().includes(search.value.toLowerCase());
        
        const matchesStatus = !statusFilter.value || report.status === statusFilter.value;
        
        return matchesSearch && matchesStatus;
    });
});

const clearFilters = () => {
    search.value = '';
    statusFilter.value = '';
};

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

// Fetch reports on mount
onMounted(async () => {
    loading.value = true;
    try {
        const response = await fetch(route('daily.api.index'));
        const data = await response.json();
        reports.value = data.reports || [];
    } catch (error) {
        showMessage('Failed to load reports', true);
    } finally {
        loading.value = false;
    }
});
</script>
