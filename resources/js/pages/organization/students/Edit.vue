<template>
  <AppLayout :title="`Edit ${student.first_name} ${student.last_name}`">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Student
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <Card>
          <CardHeader>
            <CardTitle>Student Information</CardTitle>
          </CardHeader>
          <CardContent>
            <form @submit.prevent="submit" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <Label for="school_id">School</Label>
                  <Select v-model="form.school_id" :error="form.errors.school_id">
                    <SelectTrigger>
                      <SelectValue placeholder="Select a school" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem v-for="school in schools" :key="school.id" :value="school.id">
                        {{ school.name }}
                      </SelectItem>
                    </SelectContent>
                  </Select>
                  <p v-if="form.errors.school_id" class="text-sm text-red-500">
                    {{ form.errors.school_id }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="admission_number">Admission Number</Label>
                  <Input
                    id="admission_number"
                    v-model="form.admission_number"
                    :error="form.errors.admission_number"
                  />
                  <p v-if="form.errors.admission_number" class="text-sm text-red-500">
                    {{ form.errors.admission_number }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="first_name">First Name</Label>
                  <Input
                    id="first_name"
                    v-model="form.first_name"
                    :error="form.errors.first_name"
                  />
                  <p v-if="form.errors.first_name" class="text-sm text-red-500">
                    {{ form.errors.first_name }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="last_name">Last Name</Label>
                  <Input
                    id="last_name"
                    v-model="form.last_name"
                    :error="form.errors.last_name"
                  />
                  <p v-if="form.errors.last_name" class="text-sm text-red-500">
                    {{ form.errors.last_name }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="email">Email</Label>
                  <Input
                    id="email"
                    type="email"
                    v-model="form.email"
                    :error="form.errors.email"
                  />
                  <p v-if="form.errors.email" class="text-sm text-red-500">
                    {{ form.errors.email }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="phone">Phone</Label>
                  <Input
                    id="phone"
                    type="tel"
                    v-model="form.phone"
                    :error="form.errors.phone"
                  />
                  <p v-if="form.errors.phone" class="text-sm text-red-500">
                    {{ form.errors.phone }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="date_of_birth">Date of Birth</Label>
                  <Input
                    id="date_of_birth"
                    type="date"
                    v-model="form.date_of_birth"
                    :error="form.errors.date_of_birth"
                  />
                  <p v-if="form.errors.date_of_birth" class="text-sm text-red-500">
                    {{ form.errors.date_of_birth }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="gender">Gender</Label>
                  <Select v-model="form.gender" :error="form.errors.gender">
                    <SelectTrigger>
                      <SelectValue placeholder="Select gender" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="male">Male</SelectItem>
                      <SelectItem value="female">Female</SelectItem>
                      <SelectItem value="other">Other</SelectItem>
                    </SelectContent>
                  </Select>
                  <p v-if="form.errors.gender" class="text-sm text-red-500">
                    {{ form.errors.gender }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="class">Class</Label>
                  <Input
                    id="class"
                    v-model="form.class"
                    :error="form.errors.class"
                  />
                  <p v-if="form.errors.class" class="text-sm text-red-500">
                    {{ form.errors.class }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="section">Section</Label>
                  <Input
                    id="section"
                    v-model="form.section"
                    :error="form.errors.section"
                  />
                  <p v-if="form.errors.section" class="text-sm text-red-500">
                    {{ form.errors.section }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="roll_number">Roll Number</Label>
                  <Input
                    id="roll_number"
                    v-model="form.roll_number"
                    :error="form.errors.roll_number"
                  />
                  <p v-if="form.errors.roll_number" class="text-sm text-red-500">
                    {{ form.errors.roll_number }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="parent_name">Parent Name</Label>
                  <Input
                    id="parent_name"
                    v-model="form.parent_name"
                    :error="form.errors.parent_name"
                  />
                  <p v-if="form.errors.parent_name" class="text-sm text-red-500">
                    {{ form.errors.parent_name }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="parent_phone">Parent Phone</Label>
                  <Input
                    id="parent_phone"
                    type="tel"
                    v-model="form.parent_phone"
                    :error="form.errors.parent_phone"
                  />
                  <p v-if="form.errors.parent_phone" class="text-sm text-red-500">
                    {{ form.errors.parent_phone }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="parent_email">Parent Email</Label>
                  <Input
                    id="parent_email"
                    type="email"
                    v-model="form.parent_email"
                    :error="form.errors.parent_email"
                  />
                  <p v-if="form.errors.parent_email" class="text-sm text-red-500">
                    {{ form.errors.parent_email }}
                  </p>
                </div>
              </div>

              <div class="space-y-2">
                <Label for="address">Address</Label>
                <Textarea
                  id="address"
                  v-model="form.address"
                  :error="form.errors.address"
                />
                <p v-if="form.errors.address" class="text-sm text-red-500">
                  {{ form.errors.address }}
                </p>
              </div>

              <div class="space-y-2">
                <Label for="medical_conditions">Medical Conditions</Label>
                <Textarea
                  id="medical_conditions"
                  v-model="form.medical_conditions"
                  :error="form.errors.medical_conditions"
                />
                <p v-if="form.errors.medical_conditions" class="text-sm text-red-500">
                  {{ form.errors.medical_conditions }}
                </p>
              </div>

              <div class="space-y-2">
                <Label for="notes">Notes</Label>
                <Textarea
                  id="notes"
                  v-model="form.notes"
                  :error="form.errors.notes"
                />
                <p v-if="form.errors.notes" class="text-sm text-red-500">
                  {{ form.errors.notes }}
                </p>
              </div>

              <div class="flex justify-end space-x-4">
                <Button
                  type="button"
                  variant="outline"
                  @click="router.visit(route('students.show', student.id))"
                >
                  Cancel
                </Button>
                <Button
                  type="submit"
                  :disabled="form.processing"
                >
                  Update Student
                </Button>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select/index';
import { Textarea } from '@/components/ui/textarea';

interface School {
    id: string;
    name: string;
}

interface Student {
    id: string;
    school_id: string;
    admission_number: string;
    first_name: string;
    last_name: string;
    email: string;
    phone: string | null;
    date_of_birth: string;
    gender: string;
    class: string;
    section: string;
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
    schools: School[];
}>();

const form = useForm({
    school_id: props.student.school_id,
    admission_number: props.student.admission_number,
    first_name: props.student.first_name,
    last_name: props.student.last_name,
    email: props.student.email,
    phone: props.student.phone || '',
    date_of_birth: props.student.date_of_birth,
    gender: props.student.gender,
    class: props.student.class,
    section: props.student.section,
    roll_number: props.student.roll_number,
    parent_name: props.student.parent_name,
    parent_phone: props.student.parent_phone,
    parent_email: props.student.parent_email || '',
    address: props.student.address || '',
    medical_conditions: props.student.medical_conditions || '',
    notes: props.student.notes || '',
});

const submit = () => {
    form.put(route('students.update', props.student.id));
};
</script> 