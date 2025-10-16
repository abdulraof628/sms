<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { 
    GraduationCap, 
    School, 
    Users, 
    Edit, 
    ArrowLeft,
    Calendar,
    User
} from 'lucide-vue-next';

interface ClassData {
    id: string;
    name: string;
    code: string;
    description: string;
    status: boolean;
    created_at: string;
    updated_at: string;
    school: {
        id: string;
        name: string;
    };
    students: Array<{
        id: string;
        first_name: string;
        last_name: string;
        roll_number: string;
        status: boolean;
    }>;
}

const props = defineProps<{
    classData: ClassData;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Classes',
        href: '/classes',
    },
    {
        title: props.classData.name,
        href: `/classes/${props.classData.id}`,
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
    <Head :title="classData.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <Link :href="route('classes.index')">
                        <Button variant="outline" size="sm">
                            <ArrowLeft class="h-4 w-4 mr-2" />
                            Back to Classes
                        </Button>
                    </Link>
                    <div>
                        <h1 class="text-3xl font-bold text-foreground">{{ classData.name }}</h1>
                        <p class="text-muted-foreground">{{ classData.school.name }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <Badge :variant="classData.status ? 'default' : 'secondary'">
                        {{ classData.status ? 'Active' : 'Inactive' }}
                    </Badge>
                    <Link :href="route('classes.edit', classData.id)">
                        <Button>
                            <Edit class="h-4 w-4 mr-2" />
                            Edit Class
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Class Information -->
            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Details -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <GraduationCap class="h-5 w-5" />
                                <span>Class Information</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Class Name</label>
                                    <p class="text-sm">{{ classData.name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Class Code</label>
                                    <p class="text-sm font-mono">{{ classData.code }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">School</label>
                                    <Link :href="route('schools.show', classData.school.id)" class="text-sm text-primary hover:underline">
                                        {{ classData.school.name }}
                                    </Link>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-muted-foreground">Status</label>
                                    <Badge :variant="classData.status ? 'default' : 'secondary'">
                                        {{ classData.status ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </div>
                            </div>
                            
                            <div v-if="classData.description">
                                <label class="text-sm font-medium text-muted-foreground">Description</label>
                                <p class="text-sm mt-1">{{ classData.description }}</p>
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
                                    <Users class="h-4 w-4 text-primary" />
                                    <span class="text-sm font-medium">Total Students</span>
                                </div>
                                <Badge variant="secondary">{{ classData.students.length }}</Badge>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <User class="h-4 w-4 text-green-500" />
                                    <span class="text-sm font-medium">Active Students</span>
                                </div>
                                <Badge variant="secondary">{{ classData.students.filter(s => s.status).length }}</Badge>
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
                                        <p class="text-xs text-muted-foreground">{{ formatDate(classData.created_at) }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <Calendar class="h-4 w-4 text-muted-foreground" />
                                    <div>
                                        <p class="text-sm font-medium">Last Updated</p>
                                        <p class="text-xs text-muted-foreground">{{ formatDate(classData.updated_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Students -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center justify-between">
                        <span class="flex items-center space-x-2">
                            <Users class="h-5 w-5" />
                            <span>Students ({{ classData.students.length }})</span>
                        </span>
                        <Link :href="route('students.create')">
                            <Button size="sm">Add Student</Button>
                        </Link>
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div v-if="classData.students.length > 0" class="space-y-3">
                        <div v-for="student in classData.students" :key="student.id" 
                             class="flex items-center justify-between p-3 rounded-lg border">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center">
                                    <User class="h-4 w-4 text-primary" />
                                </div>
                                <div>
                                    <p class="font-medium">{{ student.first_name }} {{ student.last_name }}</p>
                                    <p class="text-sm text-muted-foreground">Roll: {{ student.roll_number }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <Badge :variant="student.status ? 'default' : 'secondary'">
                                    {{ student.status ? 'Active' : 'Inactive' }}
                                </Badge>
                                <Link :href="route('students.show', student.id)">
                                    <Button variant="outline" size="sm">View</Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center text-muted-foreground py-8">
                        <Users class="h-12 w-12 mx-auto mb-4 text-muted-foreground/50" />
                        <p>No students enrolled in this class yet.</p>
                        <Link :href="route('students.create')">
                            <Button class="mt-4">Add First Student</Button>
                        </Link>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
