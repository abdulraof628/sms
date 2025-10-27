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
    Globe, 
    User, 
    Calendar,
    GraduationCap,
    Users,
    Edit,
    ArrowLeft
} from 'lucide-vue-next';

interface BranchData {
    id: string;
    name: string;
    address: string;
    city: string;
    state: string;
    postal_code: string;
    country: string;
    website: string;
    established_year: number;
    description: string;
    status: boolean;
    created_at: string;
    updated_at: string;
    principal: {
        id: string;
        name: string;
        email: string;
    } | null;
    classes: Array<{
        id: string;
        name: string;
        code: string;
        status: boolean;
    }>;
    students: Array<{
        id: string;
        first_name: string;
        last_name: string;
        class: string;
        status: boolean;
    }>;
}

const props = defineProps<{
    branch: BranchData;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Branchs',
        href: '/branchs',
    },
    {
        title: props.branch.name,
        href: `/branchs/${props.branch.id}`,
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
    <Head :title="branch.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">{{ branch.name }}</h1>
                    <p class="text-muted-foreground mt-2">Branch details and information</p>
                </div>
                <div class="flex gap-2">
                    <Link :href="route('branches.index')">
                        <Button variant="outline">
                            <ArrowLeft class="h-4 w-4 mr-2" />
                            Back to Branches
                        </Button>
                    </Link>
                    <Link :href="route('branches.edit', branch.id)">
                        <Button>
                            <Edit class="h-4 w-4 mr-2" />
                            Edit Branch
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Branch Information -->
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <Building2 class="h-5 w-5" />
                                <span>Branch Information</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Branch Name</label>
                                    <p class="text-sm">{{ branch.name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Principal</label>
                                    <p class="text-sm">{{ branch.principal?.name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Established Year</label>
                                    <p class="text-sm">{{ branch.established_year }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Status</label>
                                    <Badge :variant="branch.status ? 'default' : 'secondary'">
                                        {{ branch.status ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </div>
                            </div>
                            
                            <div v-if="branch.description">
                                <label class="text-sm font-medium text-muted-foreground">Description</label>
                                <p class="text-sm mt-1">{{ branch.description }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Address Information -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <MapPin class="h-5 w-5" />
                                <span>Address</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-2">
                            <p class="text-sm font-medium">{{ branch.address }}</p>
                            <p class="text-sm text-muted-foreground">{{ branch.city }}, {{ branch.state }} {{ branch.postal_code }}</p>
                            <p class="text-sm text-muted-foreground">{{ branch.country }}</p>
                        </CardContent>
                    </Card>

                    <!-- Website -->
                    <Card v-if="branch.website">
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <Globe class="h-5 w-5" />
                                <span>Website</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <a :href="branch.website" target="_blank" class="text-primary hover:underline">
                                {{ branch.website }}
                            </a>
                        </CardContent>
                    </Card>
                </div>

                <!-- Statistics -->
                <div class="space-y-6">
                    <!-- Quick Stats -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Statistics</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <GraduationCap class="h-4 w-4 text-primary" />
                                    <span class="text-sm font-medium">Classes</span>
                                </div>
                                <Badge variant="secondary">{{ branch.classes.length }}</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <Users class="h-4 w-4 text-primary" />
                                    <span class="text-sm font-medium">Students</span>
                                </div>
                                <Badge variant="secondary">{{ branch.students.length }}</Badge>
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
                                        <p class="text-xs text-muted-foreground">{{ formatDate(branch.created_at) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <Calendar class="h-4 w-4 text-muted-foreground" />
                                    <div>
                                        <p class="text-sm font-medium">Last Updated</p>
                                        <p class="text-xs text-muted-foreground">{{ formatDate(branch.updated_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Classes and Students -->
            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Classes -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center justify-between">
                            <span class="flex items-center space-x-2">
                                <GraduationCap class="h-5 w-5" />
                                <span>Classes ({{ branch.classes.length }})</span>
                            </span>
                            <Link :href="route('classes.create')">
                                <Button size="sm">Add Class</Button>
                            </Link>
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="branch.classes.length > 0" class="space-y-3">
                            <div v-for="classItem in branch.classes" :key="classItem.id" 
                                 class="flex items-center justify-between p-3 rounded-lg border">
                                <div>
                                    <p class="font-medium">{{ classItem.name }}</p>
                                    <p class="text-sm text-muted-foreground">Code: {{ classItem.code }}</p>
                                </div>
                                <Badge :variant="classItem.status ? 'default' : 'secondary'">
                                    {{ classItem.status ? 'Active' : 'Inactive' }}
                                </Badge>
                            </div>
                        </div>
                        <div v-else class="text-center text-muted-foreground py-8">
                            No classes found for this branch.
                        </div>
                    </CardContent>
                </Card>

                <!-- Students -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center justify-between">
                            <span class="flex items-center space-x-2">
                                <Users class="h-5 w-5" />
                                <span>Students ({{ branch.students.length }})</span>
                            </span>
                            <Link :href="route('students.create')">
                                <Button size="sm">Add Student</Button>
                            </Link>
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="branch.students.length > 0" class="space-y-3">
                            <div v-for="student in branch.students.slice(0, 5)" :key="student.id" 
                                 class="flex items-center justify-between p-3 rounded-lg border">
                                <div>
                                    <p class="font-medium">{{ student.first_name }} {{ student.last_name }}</p>
                                    <p class="text-sm text-muted-foreground">Class: {{ student.class }}</p>
                                </div>
                                <Badge :variant="student.status ? 'default' : 'secondary'">
                                    {{ student.status ? 'Active' : 'Inactive' }}
                                </Badge>
                            </div>
                            <div v-if="branch.students.length > 5" class="text-center">
                                <Link :href="route('students.index', { branch: branch.id })">
                                    <Button variant="outline" size="sm">
                                        View All {{ branch.students.length }} Students
                                    </Button>
                                </Link>
                            </div>
                        </div>
                        <div v-else class="text-center text-muted-foreground py-8">
                            No students found for this branch.
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
