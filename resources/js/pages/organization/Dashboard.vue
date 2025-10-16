<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
    Users, 
    School, 
    GraduationCap, 
    TrendingUp, 
    Calendar,
    UserCheck,
    UserX,
    BarChart3
} from 'lucide-vue-next';
import { computed } from 'vue';

interface DashboardProps {
    schoolCount: number;
    studentCount: number;
    classCount: number;
    schoolStatusBreakdown: {
        active: number;
        inactive: number;
    };
    classDistribution: Array<{
        id: string;
        name: string;
        classes_count: number;
    }>;
    genderDistribution: {
        male: number;
        female: number;
        other: number;
    };
    recentSchools: Array<{
        id: string;
        name: string;
        created_at: string;
    }>;
    recentStudents: Array<{
        id: string;
        first_name: string;
        last_name: string;
        created_at: string;
    }>;
    recentClasses: Array<{
        id: string;
        name: string;
        created_at: string;
    }>;
}

const props = defineProps<DashboardProps>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

const totalStudents = computed(() => {
    return props.genderDistribution.male + props.genderDistribution.female + props.genderDistribution.other;
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Welcome Section -->
            <div class="mb-4">
                <h1 class="text-3xl font-bold text-foreground">Welcome to Your Dashboard</h1>
                <p class="text-muted-foreground mt-2">Here's an overview of your school management system</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <!-- Schools Card -->
                <Card class="hover:shadow-lg transition-shadow duration-200">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Schools</CardTitle>
                        <School class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ schoolCount }}</div>
                        <div class="flex items-center space-x-2 text-xs text-muted-foreground">
                            <UserCheck class="h-3 w-3 text-green-500" />
                            <span>{{ schoolStatusBreakdown.active }} active</span>
                            <UserX class="h-3 w-3 text-red-500 ml-2" />
                            <span>{{ schoolStatusBreakdown.inactive }} inactive</span>
                        </div>
                    </CardContent>
                </Card>

                <!-- Students Card -->
                <Card class="hover:shadow-lg transition-shadow duration-200">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Students</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ studentCount }}</div>
                        <div class="flex items-center space-x-2 text-xs text-muted-foreground">
                            <TrendingUp class="h-3 w-3 text-blue-500" />
                            <span>{{ genderDistribution.male }} male, {{ genderDistribution.female }} female</span>
                        </div>
                    </CardContent>
                </Card>

                <!-- Classes Card -->
                <Card class="hover:shadow-lg transition-shadow duration-200">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Classes</CardTitle>
                        <GraduationCap class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ classCount }}</div>
                        <div class="flex items-center space-x-2 text-xs text-muted-foreground">
                            <BarChart3 class="h-3 w-3 text-purple-500" />
                            <span>Across {{ schoolCount }} schools</span>
                        </div>
                    </CardContent>
                </Card>

                <!-- Gender Distribution Card -->
                <Card class="hover:shadow-lg transition-shadow duration-200">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Gender Distribution</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Male</span>
                                <span class="font-medium">{{ genderDistribution.male }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Female</span>
                                <span class="font-medium">{{ genderDistribution.female }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Other</span>
                                <span class="font-medium">{{ genderDistribution.other }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Charts and Recent Activity -->
            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Class Distribution Chart -->
                <Card>
                    <CardHeader>
                        <CardTitle>Classes per School</CardTitle>
                        <CardDescription>Distribution of classes across schools</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-for="school in classDistribution" :key="school.id" class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 rounded-full bg-primary"></div>
                                    <span class="text-sm font-medium">{{ school.name }}</span>
                                </div>
                                <Badge variant="secondary">{{ school.classes_count }} classes</Badge>
                            </div>
                            <div v-if="classDistribution.length === 0" class="text-center text-muted-foreground py-8">
                                No class distribution data available
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Recent Activity -->
                <Card>
                    <CardHeader>
                        <CardTitle>Recent Activity</CardTitle>
                        <CardDescription>Latest additions to the system</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="space-y-3">
                                <h4 class="text-sm font-semibold text-muted-foreground">Recent Schools</h4>
                                <div v-for="school in recentSchools.slice(0, 3)" :key="school.id" class="flex items-center justify-between text-sm">
                                    <span>{{ school.name }}</span>
                                    <span class="text-muted-foreground">{{ formatDate(school.created_at) }}</span>
                                </div>
                            </div>
                            
                            <div class="space-y-3">
                                <h4 class="text-sm font-semibold text-muted-foreground">Recent Students</h4>
                                <div v-for="student in recentStudents.slice(0, 3)" :key="student.id" class="flex items-center justify-between text-sm">
                                    <span>{{ student.first_name }} {{ student.last_name }}</span>
                                    <span class="text-muted-foreground">{{ formatDate(student.created_at) }}</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Quick Actions -->
            <Card>
                <CardHeader>
                    <CardTitle>Quick Actions</CardTitle>
                    <CardDescription>Common tasks and shortcuts</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 md:grid-cols-3">
                        <a href="/schools/create" class="flex items-center space-x-3 p-4 rounded-lg border hover:bg-muted/50 transition-colors">
                            <School class="h-5 w-5 text-primary" />
                            <div>
                                <div class="font-medium">Add New School</div>
                                <div class="text-sm text-muted-foreground">Register a new school</div>
                            </div>
                        </a>
                        
                        <a href="/students/create" class="flex items-center space-x-3 p-4 rounded-lg border hover:bg-muted/50 transition-colors">
                            <Users class="h-5 w-5 text-primary" />
                            <div>
                                <div class="font-medium">Add New Student</div>
                                <div class="text-sm text-muted-foreground">Enroll a new student</div>
                            </div>
                        </a>
                        
                        <a href="/classes/create" class="flex items-center space-x-3 p-4 rounded-lg border hover:bg-muted/50 transition-colors">
                            <GraduationCap class="h-5 w-5 text-primary" />
                            <div>
                                <div class="font-medium">Add New Class</div>
                                <div class="text-sm text-muted-foreground">Create a new class</div>
                            </div>
                        </a>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
