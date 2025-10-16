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

interface OrganizationData {
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
    organization: OrganizationData;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Organizations',
        href: '/business/organizations',
    },
    {
        title: props.organization.name,
        href: `/business/organizations/${props.organization.id}`,
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
    <Head :title="organization.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Link :href="route('business.organizations.index')">
                        <Button variant="outline" size="sm">
                            <ArrowLeft class="h-4 w-4 mr-2" />
                            Back to Organizations
                        </Button>
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold text-foreground">{{ organization.name }}</h1>
                        <p class="text-muted-foreground">SSM: {{ organization.ssm_number }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <Badge :variant="organization.is_active ? 'default' : 'secondary'">
                        {{ organization.is_active ? 'Active' : 'Inactive' }}
                    </Badge>
                    <Badge v-if="organization.is_new" variant="outline" class="text-green-600 border-green-600">
                        New
                    </Badge>
                    <Link :href="route('business.organizations.edit', organization.id)">
                        <Button>
                            <Edit class="h-4 w-4 mr-2" />
                            Edit Organization
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Organization Information -->
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <Building2 class="h-5 w-5" />
                                <span>Organization Information</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Organization Name</label>
                                    <p class="text-sm">{{ organization.name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">SSM Number</label>
                                    <p class="text-sm font-mono">{{ organization.ssm_number }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Status</label>
                                    <div class="flex items-center space-x-2">
                                        <Badge :variant="organization.is_active ? 'default' : 'secondary'">
                                            {{ organization.is_active ? 'Active' : 'Inactive' }}
                                        </Badge>
                                        <Badge v-if="organization.is_new" variant="outline" class="text-green-600 border-green-600">
                                            New Organization
                                        </Badge>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Email</label>
                                    <p class="text-sm">{{ organization.email }}</p>
                                </div>
                            </div>
                            
                            <div v-if="organization.notes">
                                <label class="text-sm font-medium text-muted-foreground">Notes</label>
                                <p class="text-sm mt-1">{{ organization.notes }}</p>
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
                                        <p class="text-sm font-medium">{{ organization.address }}</p>
                                        <p class="text-sm text-muted-foreground">{{ organization.city }}, {{ organization.state }} {{ organization.postal_code }}</p>
                                        <p class="text-sm text-muted-foreground">{{ organization.country }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-3">
                                        <Phone class="h-4 w-4 text-muted-foreground" />
                                        <span class="text-sm">{{ organization.phone }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <Mail class="h-4 w-4 text-muted-foreground" />
                                        <span class="text-sm">{{ organization.email }}</span>
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
                                    <CheckCircle v-if="organization.is_active" class="h-4 w-4 text-green-500" />
                                    <XCircle v-else class="h-4 w-4 text-red-500" />
                                    <span class="text-sm font-medium">Organization Status</span>
                                </div>
                                <Badge :variant="organization.is_active ? 'default' : 'secondary'">
                                    {{ organization.is_active ? 'Active' : 'Inactive' }}
                                </Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <Badge v-if="organization.is_new" variant="outline" class="text-green-600 border-green-600">
                                        New
                                    </Badge>
                                    <span class="text-sm font-medium">Account Type</span>
                                </div>
                                <span class="text-sm text-muted-foreground">
                                    {{ organization.is_new ? 'New Account' : 'Established' }}
                                </span>
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
                                        <p class="text-xs text-muted-foreground">{{ formatDate(organization.created_at) }}</p>
                                        <p class="text-xs text-muted-foreground">by {{ organization.creator?.name || 'System' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <Calendar class="h-4 w-4 text-muted-foreground" />
                                    <div>
                                        <p class="text-sm font-medium">Last Updated</p>
                                        <p class="text-xs text-muted-foreground">{{ formatDate(organization.updated_at) }}</p>
                                        <p class="text-xs text-muted-foreground">by {{ organization.updater?.name || 'System' }}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Quick Actions -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Quick Actions</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <Link :href="route('business.organizations.edit', organization.id)" class="block">
                                <Button variant="outline" class="w-full justify-start">
                                    <Edit class="h-4 w-4 mr-2" />
                                    Edit Organization
                                </Button>
                            </Link>
                            <Button variant="outline" class="w-full justify-start">
                                <User class="h-4 w-4 mr-2" />
                                Manage Users
                            </Button>
                            <Button variant="outline" class="w-full justify-start">
                                <Building2 class="h-4 w-4 mr-2" />
                                View Schools
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
