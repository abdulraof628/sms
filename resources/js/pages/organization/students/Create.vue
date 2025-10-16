<template>
  <AppLayout title="Add Student">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Add Student
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
                  <Combobox v-model="selectedSchool" by="id">
                    <ComboboxAnchor as-child>
                      <ComboboxTrigger as-child>
                        <Button variant="outline" class="w-full justify-between">
                          {{ selectedSchool?.name ?? 'Select a school' }}
                          <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                        </Button>
                      </ComboboxTrigger>
                    </ComboboxAnchor>

                    <ComboboxList>
                      <div class="relative w-full min-w-full items-center">
                        <ComboboxInput class="mt-0.5 focus-visible:ring-0 border-0 border-b rounded-none h-10" placeholder="Search schools..." />
                        <span class="absolute start-0 inset-y-0 flex items-center justify-center px-3">
                          <Search class="size-4 text-muted-foreground" />
                        </span>
                      </div>

                      <ComboboxEmpty>
                        No schools found.
                      </ComboboxEmpty>

                      <ComboboxGroup>
                        <ComboboxItem
                          v-for="school in displaySchools"
                          :key="school.id"
                          :value="school"
                          class="w-full"
                        >
                          {{ school.name }}
                          <ComboboxItemIndicator>
                            <Check :class="cn('ml-auto h-4 w-4')" />
                          </ComboboxItemIndicator>
                        </ComboboxItem>
                      </ComboboxGroup>
                    </ComboboxList>
                  </Combobox>
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
                  <DatePicker
                    v-model="dateOfBirthValue"
                    :class="'w-full'"
                  />
                  <p v-if="form.errors.date_of_birth" class="text-sm text-red-500">
                    {{ form.errors.date_of_birth }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="gender">Gender</Label>
                  <Combobox v-model="selectedGender">
                    <ComboboxAnchor as-child>
                      <ComboboxTrigger as-child>
                        <Button variant="outline" class="w-full justify-between">
                          {{ selectedGender ?? 'Select gender' }}
                          <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                        </Button>
                      </ComboboxTrigger>
                    </ComboboxAnchor>

                    <ComboboxList class="w-full min-w-full">
                      <div class="relative w-full min-w-full items-center">
                        <ComboboxInput class="mt-0.5 focus-visible:ring-0 border-0 border-b rounded-none h-10" placeholder="Search gender..." />
                        <span class="absolute start-0 inset-y-0 flex items-center justify-center px-3">
                          <Search class="size-4 text-muted-foreground" />
                        </span>
                      </div>

                      <ComboboxEmpty>
                        No gender options found.
                      </ComboboxEmpty>

                      <ComboboxGroup>
                        <ComboboxItem value="male" class="w-full">
                          Male
                          <ComboboxItemIndicator>
                            <Check :class="cn('ml-auto h-4 w-4')" />
                          </ComboboxItemIndicator>
                        </ComboboxItem>
                        <ComboboxItem value="female" class="w-full">
                          Female
                          <ComboboxItemIndicator>
                            <Check :class="cn('ml-auto h-4 w-4')" />
                          </ComboboxItemIndicator>
                        </ComboboxItem>
                        <ComboboxItem value="other" class="w-full">
                          Other
                          <ComboboxItemIndicator>
                            <Check :class="cn('ml-auto h-4 w-4')" />
                          </ComboboxItemIndicator>
                        </ComboboxItem>
                        <ComboboxItem value="prefer_not_to_say" class="w-full">
                          Prefer not to say
                          <ComboboxItemIndicator>
                            <Check :class="cn('ml-auto h-4 w-4')" />
                          </ComboboxItemIndicator>
                        </ComboboxItem>
                      </ComboboxGroup>
                    </ComboboxList>
                  </Combobox>
                  <p v-if="form.errors.gender" class="text-sm text-red-500">
                    {{ form.errors.gender }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="class">Class</Label>
                  <Combobox v-model="selectedClass" by="code">
                    <ComboboxAnchor as-child>
                      <ComboboxTrigger as-child>
                        <Button variant="outline" class="w-full justify-between" :disabled="!selectedSchool">
                          {{ selectedClass?.name ?? 'Select a class' }}
                          <ChevronsUpDown class="ml-2 h-4 w-4 shrink-0 opacity-50" />
                        </Button>
                      </ComboboxTrigger>
                    </ComboboxAnchor>

                    <ComboboxList>
                      <div class="relative w-full min-w-full items-center">
                        <ComboboxInput class="mt-0.5 focus-visible:ring-0 border-0 border-b rounded-none h-10" placeholder="Search classes..." />
                        <span class="absolute start-0 inset-y-0 flex items-center justify-center px-3">
                          <Search class="size-4 text-muted-foreground" />
                        </span>
                      </div>

                      <ComboboxEmpty>
                        No classes found.
                      </ComboboxEmpty>

                      <ComboboxGroup>
                        <ComboboxItem
                          v-for="classItem in filteredClasses"
                          :key="classItem.id"
                          :value="classItem"
                          class="w-full"
                        >
                          {{ classItem.name }}
                          <ComboboxItemIndicator>
                            <Check :class="cn('ml-auto h-4 w-4')" />
                          </ComboboxItemIndicator>
                        </ComboboxItem>
                      </ComboboxGroup>
                    </ComboboxList>
                  </Combobox>
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
                  @click="router.visit(route('students.index'))"
                >
                  Cancel
                </Button>
                <Button
                  type="submit"
                  :disabled="form.processing"
                >
                  Save Student
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
import { Check, ChevronsUpDown, Search } from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';
import { cn } from '@/lib/utils';
import { 
  Combobox,
  ComboboxAnchor,
  ComboboxEmpty,
  ComboboxGroup,
  ComboboxInput,
  ComboboxItem,
  ComboboxItemIndicator,
  ComboboxList,
  ComboboxTrigger
} from '@/components/ui/combobox';
import { Textarea } from '@/components/ui/textarea';
import DatePicker from '@/components/ui/date-picker/DatePicker.vue'
import { parseDate, type DateValue } from '@internationalized/date'

interface School {
    id: string;
    name: string;
}

interface SchoolClass {
    id: string;
    school_id: string;
    name: string;
    code: string;
    description: string | null;
    status: boolean;
}

const props = defineProps<{
    schools: School[];
    classes: SchoolClass[];
}>();

// Sample schools data for demonstration
const sampleSchools = [
    { id: '1', name: 'Springfield High School' },
    { id: '2', name: 'Lincoln Academy' },
    { id: '3', name: 'Riverside College' },
    { id: '4', name: 'St. Mary\'s School' },
    { id: '5', name: 'Central High School' }
];

// Use sample data if no schools are provided
const displaySchools = props.schools.length > 0 ? props.schools : sampleSchools;

const selectedSchool = ref<School | null>(null);
const selectedGender = ref<string | null>(null);
const selectedClass = ref<SchoolClass | null>(null);

// Filter classes based on selected school
const filteredClasses = computed(() => {
    if (!selectedSchool.value) return [];
    return props.classes.filter(c => c.school_id === selectedSchool.value?.id);
});

// Watch for school changes to reset class selection and update form
watch(selectedSchool, (newSchool) => {
    selectedClass.value = null;
    form.class = '';
    if (newSchool) {
        form.school_id = newSchool.id;
    } else {
        form.school_id = '';
    }
});

// Watch for class changes to update form
watch(selectedClass, (newClass) => {
    if (newClass) {
        form.class = newClass.code;
    } else {
        form.class = '';
    }
});

// Watch for gender changes to update form
watch(selectedGender, (newGender) => {
    form.gender = newGender || '';
});

const form = useForm({
    school_id: '',
    admission_number: '',
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    date_of_birth: '',
    gender: '',
    class: '',
    section: '',
    roll_number: '',
    parent_name: '',
    parent_phone: '',
    parent_email: '',
    address: '',
    medical_conditions: '',
    notes: '',
});

const dateOfBirthValue = ref<any>(form.date_of_birth ? parseDate(form.date_of_birth) : undefined)

watch(dateOfBirthValue, (newVal) => {
  form.date_of_birth = newVal ? newVal.toString() : ''
})

watch(() => form.date_of_birth, (newVal) => {
  if (!newVal) {
    dateOfBirthValue.value = undefined
  } else if (!dateOfBirthValue.value || dateOfBirthValue.value.toString() !== newVal) {
    dateOfBirthValue.value = parseDate(newVal)
  }
})

const submit = () => {
    form.post(route('students.store'));
};
</script> 