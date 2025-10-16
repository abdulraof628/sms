<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

interface Student {
    id: string;
    first_name: string;
    last_name: string;
    email: string;
    phone: string | null;
    date_of_birth: string;
    gender: string;
    school: {
        name: string;
    };
    class: string;
    section: string;
    admission_number: string;
    roll_number: string;
    parent_name: string;
    parent_phone: string;
    parent_email: string | null;
    address: string | null;
    medical_conditions: string | null;
    notes: string | null;
}

const props = defineProps<{
    student: Student;
}>();
</script>

<template>
    <AppLayout :title="`${student.first_name} ${student.last_name}`">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Student Details
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <div class="flex justify-between items-center">
                            <CardTitle>Student Information</CardTitle>
                            <Button @click="router.visit(route('students.edit', student.id))">
                                Edit Student
                            </Button>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium">Basic Information</h3>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Full Name</p>
                                    <p class="font-medium">{{ student.first_name }} {{ student.last_name }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="font-medium">{{ student.email }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Phone</p>
                                    <p class="font-medium">{{ student.phone || 'Not provided' }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Date of Birth</p>
                                    <p class="font-medium">{{ student.date_of_birth }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Gender</p>
                                    <p class="font-medium capitalize">{{ student.gender }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg font-medium">Academic Information</h3>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">School</p>
                                    <p class="font-medium">{{ student.school.name }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Admission Number</p>
                                    <p class="font-medium">{{ student.admission_number }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Class</p>
                                    <p class="font-medium">{{ student.class }} - {{ student.section }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Roll Number</p>
                                    <p class="font-medium">{{ student.roll_number }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg font-medium">Parent Information</h3>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Parent Name</p>
                                    <p class="font-medium">{{ student.parent_name }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Parent Phone</p>
                                    <p class="font-medium">{{ student.parent_phone }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Parent Email</p>
                                    <p class="font-medium">{{ student.parent_email || 'Not provided' }}</p>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <h3 class="text-lg font-medium">Additional Information</h3>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Address</p>
                                    <p class="font-medium">{{ student.address || 'Not provided' }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Medical Conditions</p>
                                    <p class="font-medium">{{ student.medical_conditions || 'None' }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-500">Notes</p>
                                    <p class="font-medium">{{ student.notes || 'None' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <Button
                                variant="outline"
                                @click="router.visit(route('students.index'))"
                            >
                                Back to List
                            </Button>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template> 