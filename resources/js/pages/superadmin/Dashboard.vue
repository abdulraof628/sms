<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { 
    Building2, 
    Users, 
    Globe, 
    TrendingUp, 
    Settings,
    Plus,
    Eye,
    BarChart3,
    Calendar,
    Activity
} from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/superadmin/dashboard',
    },
];

// Mock data - in real app this would come from props
const stats = {
    totalTenants: 12,
    activeTenants: 10,
    totalUsers: 156,
    monthlyGrowth: 15.3,
    systemHealth: 99.8
};

const recentActivity = [
    { id: 1, type: 'tenant', name: 'ABC Branch District', action: 'created', time: '2 hours ago' },
    { id: 2, type: 'user', name: 'John Doe', action: 'registered', time: '4 hours ago' },
    { id: 3, type: 'tenant', name: 'XYZ Branch', action: 'activated', time: '6 hours ago' },
    { id: 4, type: 'tenant', name: 'City High Branch', action: 'updated', time: '1 day ago' },
];

const systemMetrics = [
    { label: 'CPU Usage', value: '45%', status: 'good' },
    { label: 'Memory Usage', value: '62%', status: 'warning' },
    { label: 'Disk Usage', value: '38%', status: 'good' },
    { label: 'Network I/O', value: '12%', status: 'good' },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-bold text-foreground">Dashboard</h1>
                <p class="text-muted-foreground mt-2">System overview and management</p>
            </div>

            <!-- Key Metrics -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <!-- Tenants Card -->
                <Card class="hover:shadow-lg transition-shadow duration-200">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Tenants</CardTitle>
                        <Building2 class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalTenants }}</div>
                        <div class="flex items-center space-x-2 text-xs text-muted-foreground">
                            <TrendingUp class="h-3 w-3 text-green-500" />
                            <span>{{ stats.activeTenants }} active</span>
                        </div>
                    </CardContent>
                </Card>

                <!-- Users Card -->
                <Card class="hover:shadow-lg transition-shadow duration-200">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Users</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalUsers }}</div>
                        <div class="flex items-center space-x-2 text-xs text-muted-foreground">
                            <Activity class="h-3 w-3 text-blue-500" />
                            <span>Across all tenants</span>
                        </div>
                    </CardContent>
                </Card>

                <!-- Monthly Growth Card -->
                <Card class="hover:shadow-lg transition-shadow duration-200">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Monthly Growth</CardTitle>
                        <TrendingUp class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.monthlyGrowth }}%</div>
                        <div class="flex items-center space-x-2 text-xs text-muted-foreground">
                            <BarChart3 class="h-3 w-3 text-purple-500" />
                            <span>Tenant growth</span>
                        </div>
                    </CardContent>
                </Card>

                <!-- System Health Card -->
                <Card class="hover:shadow-lg transition-shadow duration-200">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">System Health</CardTitle>
                        <Activity class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.systemHealth }}%</div>
                        <div class="flex items-center space-x-2 text-xs text-muted-foreground">
                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                            <span>All systems operational</span>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- System Metrics and Recent Activity -->
            <div class="grid gap-6 lg:grid-cols-2">
                <!-- System Metrics -->
                <Card>
                    <CardHeader>
                        <CardTitle>System Metrics</CardTitle>
                        <CardDescription>Current system performance</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-for="metric in systemMetrics" :key="metric.label" class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div :class="[
                                        'w-2 h-2 rounded-full',
                                        metric.status === 'good' ? 'bg-green-500' : 
                                        metric.status === 'warning' ? 'bg-yellow-500' : 'bg-red-500'
                                    ]"></div>
                                    <span class="text-sm font-medium">{{ metric.label }}</span>
                                </div>
                                <Badge :variant="metric.status === 'good' ? 'default' : 'destructive'">
                                    {{ metric.value }}
                                </Badge>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Recent Activity -->
                <Card>
                    <CardHeader>
                        <CardTitle>Recent Activity</CardTitle>
                        <CardDescription>Latest system events</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div v-for="activity in recentActivity" :key="activity.id" class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div :class="[
                                        'w-2 h-2 rounded-full',
                                        activity.type === 'tenant' ? 'bg-blue-500' :
                                        activity.type === 'user' ? 'bg-green-500' : 'bg-purple-500'
                                    ]"></div>
                                    <div>
                                        <div class="text-sm font-medium">{{ activity.name }}</div>
                                        <div class="text-xs text-muted-foreground">{{ activity.action }}</div>
                                    </div>
                                </div>
                                <span class="text-xs text-muted-foreground">{{ activity.time }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Management Actions -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Tenant Management -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <Building2 class="h-5 w-5" />
                            <span>Tenants</span>
                        </CardTitle>
                        <CardDescription>Manage tenants and their settings</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-3">
                        <Link :href="route('superadmin.tenants.index')" class="block">
                            <Button variant="outline" class="w-full justify-start">
                                <Eye class="h-4 w-4 mr-2" />
                                View All Tenants
                            </Button>
                        </Link>
                        <Link :href="route('superadmin.tenants.create')" class="block">
                            <Button class="w-full justify-start">
                                <Plus class="h-4 w-4 mr-2" />
                                Create New Tenant
                            </Button>
                        </Link>
                    </CardContent>
                </Card>

                <!-- User Management -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <Users class="h-5 w-5" />
                            <span>User Management</span>
                        </CardTitle>
                        <CardDescription>Manage users and permissions</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-3">
                        <Button variant="outline" class="w-full justify-start">
                            <Eye class="h-4 w-4 mr-2" />
                            View All Users
                        </Button>
                        <Button class="w-full justify-start">
                            <Plus class="h-4 w-4 mr-2" />
                            Create New User
                        </Button>
                    </CardContent>
                </Card>

                <!-- System Settings -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center space-x-2">
                            <Settings class="h-5 w-5" />
                            <span>System Settings</span>
                        </CardTitle>
                        <CardDescription>Configure system-wide settings</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-3">
                        <Button variant="outline" class="w-full justify-start">
                            <Settings class="h-4 w-4 mr-2" />
                            General Settings
                        </Button>
                        <Button variant="outline" class="w-full justify-start">
                            <BarChart3 class="h-4 w-4 mr-2" />
                            Analytics
                        </Button>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
