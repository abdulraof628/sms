<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { 
    Building2, 
    MapPin, 
    Phone, 
    Mail, 
    User, 
    Calendar,
    Edit,
    ArrowLeft,
    CheckCircle,
    XCircle
} from 'lucide-vue-next';

interface TenantData {
    id: string;
    name: string;
    ssm_number: string;
    email: string;
    phone: string;
    address: string;
    city: string;
    state: string;
    postal_code: string;
    country: string;
    notes: string;
    is_active: boolean;
    is_new: boolean;
    created_at: string;
    updated_at: string;
    creator: {
        id: string;
        name: string;
    };
    updater: {
        id: string;
        name: string;
    };
}

const props = defineProps<{
    tenant: TenantData;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Tenants',
        href: '/super-admin/tenants',
    },
    {
        title: props.tenant.name,
        href: `/super-admin/tenants/${props.tenant.id}`,
    },
];

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>

<template>
    <Head :title="tenant.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">{{ tenant.name }}</h1>
                    <p class="text-muted-foreground mt-2">Tenant details and information</p>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('superadmin.tenants.index')">
                        <Button variant="outline">
                            <ArrowLeft class="h-4 w-4 mr-2" />
                            Back to Tenants
                        </Button>
                    </Link>
                    <Link :href="route('superadmin.tenants.edit', tenant.id)">
                        <Button>
                            <Edit class="h-4 w-4 mr-2" />
                            Edit Tenant
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Tenant Information -->
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <Building2 class="h-5 w-5" />
                                <span>Tenant Information</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Tenant Name</label>
                                    <p class="text-sm">{{ tenant.name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">SSM Number</label>
                                    <p class="text-sm font-mono">{{ tenant.ssm_number }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Email</label>
                                    <p class="text-sm">{{ tenant.email }}</p>
                                </div>
                            </div>
                            
                            <div v-if="tenant.notes">
                                <label class="text-sm font-medium text-muted-foreground">Notes</label>
                                <p class="text-sm mt-1">{{ tenant.notes }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Contact Information -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <Phone class="h-5 w-5" />
                                <span>Contact Information</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="flex items-center space-x-3">
                                    <MapPin class="h-4 w-4 text-muted-foreground" />
                                    <div>
                                        <p class="text-sm font-medium">{{ tenant.address }}</p>
                                        <p class="text-sm text-muted-foreground">{{ tenant.city }}, {{ tenant.state }} {{ tenant.postal_code }}</p>
                                        <p class="text-sm text-muted-foreground">{{ tenant.country }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-3">
                                        <Phone class="h-4 w-4 text-muted-foreground" />
                                        <span class="text-sm">{{ tenant.phone }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <Mail class="h-4 w-4 text-muted-foreground" />
                                        <span class="text-sm">{{ tenant.email }}</span>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Statistics and Activity -->
                <div class="space-y-6">
                    <!-- Status Information -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Status Information</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <CheckCircle v-if="tenant.is_active" class="h-4 w-4 text-green-500" />
                                    <XCircle v-else class="h-4 w-4 text-red-500" />
                                    <span class="text-sm font-medium">Tenant Status</span>
                                </div>
                                <Badge :variant="tenant.is_active ? 'default' : 'secondary'">
                                    {{ tenant.is_active ? 'Active' : 'Inactive' }}
                                </Badge>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Recent Activity -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Recent Activity</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div class="flex items-center space-x-3">
                                    <Calendar class="h-4 w-4 text-muted-foreground" />
                                    <div>
                                        <p class="text-sm font-medium">Created</p>
                                        <p class="text-xs text-muted-foreground">{{ formatDate(tenant.created_at) }}</p>
                                        <p class="text-xs text-muted-foreground">by {{ tenant.creator?.name || 'System' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <Calendar class="h-4 w-4 text-muted-foreground" />
                                    <div>
                                        <p class="text-sm font-medium">Last Updated</p>
                                        <p class="text-xs text-muted-foreground">{{ formatDate(tenant.updated_at) }}</p>
                                        <p class="text-xs text-muted-foreground">by {{ tenant.updater?.name || 'System' }}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
