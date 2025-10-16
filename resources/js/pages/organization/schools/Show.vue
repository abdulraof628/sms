<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { 
    School, 
    MapPin, 
    Phone, 
    Mail, 
    Globe, 
    User, 
    Calendar,
    GraduationCap,
    Users,
    Edit,
    ArrowLeft
} from 'lucide-vue-next';

interface SchoolData {
    id: string;
    name: string;
    address: string;
    city: string;
    state: string;
    postal_code: string;
    country: string;
    phone: string;
    email: string;
    website: string;
    principal_name: string;
    established_year: number;
    description: string;
    status: boolean;
    created_at: string;
    updated_at: string;
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
    school: SchoolData;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Schools',
        href: '/schools',
    },
    {
        title: props.school.name,
        href: `/schools/${props.school.id}`,
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
    <Head :title="school.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Link :href="route('schools.index')">
                        <Button variant="outline" size="sm">
                            <ArrowLeft class="h-4 w-4 mr-2" />
                            Back to Schools
                        </Button>
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold text-foreground">{{ school.name }}</h1>
                        <p class="text-muted-foreground">{{ school.address }}, {{ school.city }}, {{ school.state }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <Badge :variant="school.status ? 'default' : 'secondary'">
                        {{ school.status ? 'Active' : 'Inactive' }}
                    </Badge>
                    <Link :href="route('schools.edit', school.id)">
                        <Button>
                            <Edit class="h-4 w-4 mr-2" />
                            Edit School
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- School Information -->
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <School class="h-5 w-5" />
                                <span>School Information</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">School Name</label>
                                    <p class="text-sm">{{ school.name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Principal</label>
                                    <p class="text-sm">{{ school.principal_name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Established Year</label>
                                    <p class="text-sm">{{ school.established_year }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Status</label>
                                    <Badge :variant="school.status ? 'default' : 'secondary'">
                                        {{ school.status ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </div>
                            </div>
                            
                            <div v-if="school.description">
                                <label class="text-sm font-medium text-muted-foreground">Description</label>
                                <p class="text-sm mt-1">{{ school.description }}</p>
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
                                        <p class="text-sm font-medium">{{ school.address }}</p>
                                        <p class="text-sm text-muted-foreground">{{ school.city }}, {{ school.state }} {{ school.postal_code }}</p>
                                        <p class="text-sm text-muted-foreground">{{ school.country }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-3">
                                        <Phone class="h-4 w-4 text-muted-foreground" />
                                        <span class="text-sm">{{ school.phone }}</span>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <Mail class="h-4 w-4 text-muted-foreground" />
                                        <span class="text-sm">{{ school.email }}</span>
                                    </div>
                                    <div v-if="school.website" class="flex items-center space-x-3">
                                        <Globe class="h-4 w-4 text-muted-foreground" />
                                        <a :href="school.website" target="_blank" class="text-sm text-primary hover:underline">
                                            {{ school.website }}
                                        </a>
                                    </div>
                                </div>
                            </div>
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
                                <Badge variant="secondary">{{ school.classes.length }}</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <Users class="h-4 w-4 text-primary" />
                                    <span class="text-sm font-medium">Students</span>
                                </div>
                                <Badge variant="secondary">{{ school.students.length }}</Badge>
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
                                        <p class="text-xs text-muted-foreground">{{ formatDate(school.created_at) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <Calendar class="h-4 w-4 text-muted-foreground" />
                                    <div>
                                        <p class="text-sm font-medium">Last Updated</p>
                                        <p class="text-xs text-muted-foreground">{{ formatDate(school.updated_at) }}</p>
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
                                <span>Classes ({{ school.classes.length }})</span>
                            </span>
                            <Link :href="route('classes.create')">
                                <Button size="sm">Add Class</Button>
                            </Link>
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="school.classes.length > 0" class="space-y-3">
                            <div v-for="classItem in school.classes" :key="classItem.id" 
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
                            No classes found for this school.
                        </div>
                    </CardContent>
                </Card>

                <!-- Students -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center justify-between">
                            <span class="flex items-center space-x-2">
                                <Users class="h-5 w-5" />
                                <span>Students ({{ school.students.length }})</span>
                            </span>
                            <Link :href="route('students.create')">
                                <Button size="sm">Add Student</Button>
                            </Link>
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="school.students.length > 0" class="space-y-3">
                            <div v-for="student in school.students.slice(0, 5)" :key="student.id" 
                                 class="flex items-center justify-between p-3 rounded-lg border">
                                <div>
                                    <p class="font-medium">{{ student.first_name }} {{ student.last_name }}</p>
                                    <p class="text-sm text-muted-foreground">Class: {{ student.class }}</p>
                                </div>
                                <Badge :variant="student.status ? 'default' : 'secondary'">
                                    {{ student.status ? 'Active' : 'Inactive' }}
                                </Badge>
                            </div>
                            <div v-if="school.students.length > 5" class="text-center">
                                <Link :href="route('students.index', { school: school.id })">
                                    <Button variant="outline" size="sm">
                                        View All {{ school.students.length }} Students
                                    </Button>
                                </Link>
                            </div>
                        </div>
                        <div v-else class="text-center text-muted-foreground py-8">
                            No students found for this school.
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
