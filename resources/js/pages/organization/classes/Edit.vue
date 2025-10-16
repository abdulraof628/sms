<template>
  <AppLayout title="Edit Class">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Edit Class
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <Card>
          <CardHeader>
            <CardTitle>Class Information</CardTitle>
          </CardHeader>
          <CardContent>
            <form @submit.prevent="submit" class="space-y-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <Label for="school_id">School</Label>
                  <SimpleCombobox
                    v-model="form.school_id"
                    :options="schoolOptions"
                    placeholder="Select a school"
                    emptyMessage="No schools found"
                  />
                  <p v-if="form.errors.school_id" class="text-sm text-red-500">
                    {{ form.errors.school_id }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="name">Class Name</Label>
                  <Input
                    id="name"
                    v-model="form.name"
                    :error="form.errors.name"
                  />
                  <p v-if="form.errors.name" class="text-sm text-red-500">
                    {{ form.errors.name }}
                  </p>
                </div>

                <div class="space-y-2">
                  <Label for="code">Class Code</Label>
                  <Input
                    id="code"
                    v-model="form.code"
                    :error="form.errors.code"
                  />
                  <p v-if="form.errors.code" class="text-sm text-red-500">
                    {{ form.errors.code }}
                  </p>
                </div>
              </div>

              <div class="space-y-2">
                <Label for="description">Description</Label>
                <Textarea
                  id="description"
                  v-model="form.description"
                  :error="form.errors.description"
                />
                <p v-if="form.errors.description" class="text-sm text-red-500">
                  {{ form.errors.description }}
                </p>
              </div>

              <div class="space-y-2">
                <Label for="status">Status</Label>
                <Switch
                  id="status"
                  v-model="form.status"
                />
                <p v-if="form.errors.status" class="text-sm text-red-500">
                  {{ form.errors.status }}
                </p>
              </div>

              <div class="flex justify-end gap-1">
                <Button
                  type="button"
                  variant="outline"
                  @click="router.visit(route('classes.index'))"
                >
                  Cancel
                </Button>
                <Button
                  type="submit"
                  :disabled="form.processing"
                >
                  Update Class
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
import { computed } from 'vue';
import { SimpleCombobox } from '@/components/ui';
import { Switch } from '@/components/ui/switch';
import { Textarea } from '@/components/ui/textarea';

interface School {
    id: string;
    name: string;
}

interface Class {
    id: string;
    school_id: string;
    name: string;
    code: string;
    description: string;
    status: boolean;
}

const props = defineProps<{
    schools: School[];
    classData: Class;
}>();

// Convert schools to the format expected by SimpleCombobox
const schoolOptions = computed(() => {
    return props.schools.map(school => ({
        value: school.id,
        label: school.name
    }));
});

const form = useForm({
    school_id: props.classData.school_id,
    name: props.classData.name,
    code: props.classData.code,
    description: props.classData.description,
    status: props.classData.status,
});

const submit = () => {
    form.put(route('classes.update', props.classData.id));
};
</script> 