<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { 
    User, 
    Mail, 
    Building2, 
    Shield, 
    Calendar,
    Edit,
    ArrowLeft,
    CheckCircle,
    XCircle
} from 'lucide-vue-next';

interface UserData {
    id: string;
    name: string;
    email: string;
    is_active: boolean;
    is_new: boolean;
    created_at: string;
    updated_at: string;
    roles: Array<{
        id: string;
        name: string;
        display_name: string;
    }>;
    organization: {
        id: string;
        name: string;
    } | null;
}

const props = defineProps<{
    user: UserData;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/business/users',
    },
    {
        title: props.user.name,
        href: `/business/users/${props.user.id}`,
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
    <Head :title="user.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Link :href="route('business.users.index')">
                        <Button variant="outline" size="sm">
                            <ArrowLeft class="h-4 w-4 mr-2" />
                            Back to Users
                        </Button>
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold text-foreground">{{ user.name }}</h1>
                        <p class="text-muted-foreground">{{ user.email }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <Badge :variant="user.is_active ? 'default' : 'secondary'">
                        {{ user.is_active ? 'Active' : 'Inactive' }}
                    </Badge>
                    <Badge v-if="user.is_new" variant="outline" class="text-green-600 border-green-600">
                        New
                    </Badge>
                    <Link :href="route('business.users.edit', user.id)">
                        <Button>
                            <Edit class="h-4 w-4 mr-2" />
                            Edit User
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- User Information -->
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <User class="h-5 w-5" />
                                <span>User Information</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Full Name</label>
                                    <p class="text-sm">{{ user.name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Email Address</label>
                                    <p class="text-sm">{{ user.email }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Status</label>
                                    <div class="flex items-center space-x-2">
                                        <Badge :variant="user.is_active ? 'default' : 'secondary'">
                                            {{ user.is_active ? 'Active' : 'Inactive' }}
                                        </Badge>
                                        <Badge v-if="user.is_new" variant="outline" class="text-green-600 border-green-600">
                                            New User
                                        </Badge>
                                    </div>
                                </div>
                                <div v-if="user.organization">
                                    <label class="text-sm font-medium text-muted-foreground">Organization</label>
                                    <Link :href="route('business.organizations.show', user.organization.id)" class="text-sm text-primary hover:underline">
                                        {{ user.organization.name }}
                                    </Link>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Roles and Permissions -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <Shield class="h-5 w-5" />
                                <span>Roles and Permissions</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div v-if="user.roles.length > 0" class="space-y-3">
                                <div v-for="role in user.roles" :key="role.id" class="flex items-center justify-between p-3 rounded-lg border">
                                    <div class="flex items-center space-x-3">
                                        <Shield class="h-4 w-4 text-primary" />
                                        <div>
                                            <p class="font-medium">{{ role.display_name || role.name }}</p>
                                            <p class="text-sm text-muted-foreground">{{ role.name }}</p>
                                        </div>
                                    </div>
                                    <Badge variant="secondary">Role</Badge>
                                </div>
                            </div>
                            <div v-else class="text-center text-muted-foreground py-8">
                                <Shield class="h-12 w-12 mx-auto mb-4 text-muted-foreground/50" />
                                <p>No roles assigned to this user.</p>
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
                                    <CheckCircle v-if="user.is_active" class="h-4 w-4 text-green-500" />
                                    <XCircle v-else class="h-4 w-4 text-red-500" />
                                    <span class="text-sm font-medium">Account Status</span>
                                </div>
                                <Badge :variant="user.is_active ? 'default' : 'secondary'">
                                    {{ user.is_active ? 'Active' : 'Inactive' }}
                                </Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <Badge v-if="user.is_new" variant="outline" class="text-green-600 border-green-600">
                                        New
                                    </Badge>
                                    <span class="text-sm font-medium">Account Type</span>
                                </div>
                                <span class="text-sm text-muted-foreground">
                                    {{ user.is_new ? 'New Account' : 'Established' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <Shield class="h-4 w-4 text-muted-foreground" />
                                    <span class="text-sm font-medium">Roles</span>
                                </div>
                                <Badge variant="secondary">{{ user.roles.length }}</Badge>
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
                                        <p class="text-sm font-medium">Account Created</p>
                                        <p class="text-xs text-muted-foreground">{{ formatDate(user.created_at) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <Calendar class="h-4 w-4 text-muted-foreground" />
                                    <div>
                                        <p class="text-sm font-medium">Last Updated</p>
                                        <p class="text-xs text-muted-foreground">{{ formatDate(user.updated_at) }}</p>
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
                            <Link :href="route('business.users.edit', user.id)" class="block">
                                <Button variant="outline" class="w-full justify-start">
                                    <Edit class="h-4 w-4 mr-2" />
                                    Edit User
                                </Button>
                            </Link>
                            <Button variant="outline" class="w-full justify-start">
                                <Shield class="h-4 w-4 mr-2" />
                                Manage Roles
                            </Button>
                            <Button variant="outline" class="w-full justify-start">
                                <Mail class="h-4 w-4 mr-2" />
                                Send Message
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
