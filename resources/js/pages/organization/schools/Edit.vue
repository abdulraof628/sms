<template>
  <Head title="Edit School" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6">
            <form @submit.prevent="submit" class="space-y-6">
              <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Basic Info</h3>
                <div class="grid gap-6 md:grid-cols-2">
                  <div class="space-y-2">
                    <Label for="name">School Name <span class="text-red-500">*</span></Label>
                    <Input
                      id="name"
                      type="text"
                      class="mt-1 block w-full"
                      v-model="form.name"
                      autofocus
                    />
                    <InputError :message="form.errors.name" />
                  </div>

                  <div class="space-y-2">
                    <Label for="principal_name">Principal Name <span class="text-red-500">*</span></Label>
                    <Input
                      id="principal_name"
                      type="text"
                      class="mt-1 block w-full"
                      v-model="form.principal_name"
                    />
                    <InputError :message="form.errors.principal_name" />
                  </div>

                  <div class="space-y-2">
                    <Label for="email">Email <span class="text-red-500">*</span></Label>
                    <Input
                      id="email"
                      type="email"
                      class="mt-1 block w-full"
                      v-model="form.email"
                    />
                    <InputError :message="form.errors.email" />
                  </div>

                  <div class="space-y-2">
                    <Label for="phone">Phone <span class="text-red-500">*</span></Label>
                    <Input
                      id="phone"
                      type="text"
                      class="mt-1 block w-full"
                      v-model="form.phone"
                    />
                    <InputError :message="form.errors.phone" />
                  </div>

                  <div class="space-y-2">
                    <Label for="established_year">Established Year <span class="text-red-500">*</span></Label>
                    <Input
                      id="established_year"
                      type="number"
                      class="mt-1 block w-full"
                      v-model="form.established_year"
                    />
                    <InputError :message="form.errors.established_year" />
                  </div>
                </div>
              </div>

              <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Address Info</h3>
                <div class="grid gap-6 md:grid-cols-2">
                  <div class="space-y-2 md:col-span-2">
                    <Label for="address">Address <span class="text-red-500">*</span></Label>
                    <Textarea
                      id="address"
                      class="mt-1 block w-full"
                      v-model="form.address"
                    />
                    <InputError :message="form.errors.address" />
                  </div>

                  <div class="space-y-2">
                    <Label for="state">State <span class="text-red-500">*</span></Label>
                    <SimpleCombobox
                      v-model="form.state"
                      :options="malaysiaStates"
                      placeholder="Select state..."
                      emptyMessage="No states found"
                      class="w-full"
                    />
                    <InputError :message="form.errors.state" />
                  </div>

                  <div class="space-y-2">
                    <Label for="city">City <span class="text-red-500">*</span></Label>
                    <Input id="city" v-model="form.city" type="text" />
                    <InputError :message="form.errors.city" />
                  </div>

                  <div class="space-y-2">
                    <Label for="postal_code">Postal Code <span class="text-red-500">*</span></Label>
                    <Input id="postal_code" v-model="form.postal_code" type="text" />
                    <InputError :message="form.errors.postal_code" />
                  </div>
                  <div class="space-y-2">
                    <Label for="country">Country <span class="text-red-500">*</span></Label>
                    <Input id="country" v-model="form.country" type="text" />
                    <InputError :message="form.errors.country" />
                  </div>
                </div>
              </div>

              <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Other Info</h3>
                <div class="grid gap-6 md:grid-cols-2">
                  <div class="space-y-2">
                    <Label for="website">Website</Label>
                    <Input
                      id="website"
                      type="url"
                      class="mt-1 block w-full"
                      v-model="form.website"
                    />
                    <InputError :message="form.errors.website" />
                  </div>

                  <div class="space-y-2 md:col-span-2">
                    <Label for="description">Description</Label>
                    <Textarea
                      id="description"
                      class="mt-1 block w-full"
                      v-model="form.description"
                    />
                    <InputError :message="form.errors.description" />
                  </div>

                  <div class="space-y-2 md:col-span-2">
                    <Label for="notes">Notes</Label>
                    <Textarea id="notes" v-model="form.notes" />
                    <InputError :message="form.errors.notes" />
                  </div>

                  <div class="flex items-center space-x-2">
                    <Switch id="status" v-model="form.status" />
                    <Label for="status">Active</Label>
                  </div>
                </div>
              </div>

              <div class="flex justify-end gap-1">
                <Button
                  type="button"
                  variant="outline"
                  @click="router.visit(route('schools.index'))"
                >
                  Cancel
                </Button>
                <Button
                  type="submit"
                  :disabled="form.processing"
                >
                  Update School
                </Button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import InputError from '@/components/InputError.vue'
import { Button } from '@/components/ui/button'
import { Switch } from '@/components/ui/switch'
import SimpleCombobox from '@/components/ui/SimpleCombobox.vue'
import { type BreadcrumbItem } from '@/types';

interface Props {
  school: any;
  states: string[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Schools',
        href: '/schools',
    },
    {
        title: 'Edit School',
        href: `/schools/${props.school.id}/edit`,
    },
];

const form = useForm({
  name: props.school.name || '',
  address: props.school.address || '',
  city: props.school.city || '',
  state: props.school.state || '',
  postal_code: props.school.postal_code || '',
  country: props.school.country || '',
  notes: props.school.notes || '',
  phone: props.school.phone || '',
  email: props.school.email || '',
  website: props.school.website || '',
  principal_name: props.school.principal_name || '',
  established_year: props.school.established_year || new Date().getFullYear(),
  description: props.school.description || '',
  status: props.school.status === 1 || props.school.status === '1' || props.school.status === true,
})

const malaysiaStates = props.states.map(state => ({ value: state, label: state }));

const submit = () => {
  form.put(route('schools.update', props.school.id))
}
</script> 