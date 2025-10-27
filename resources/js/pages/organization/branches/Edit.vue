<template>
  <Head title="Edit Branch" />

  <AppLayout :breadcrumbs="breadcrumbs">
    
    <div class="mx-auto w-full p-6">
      <!-- Header Section -->
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-3xl font-bold text-foreground">Edit Branch</h1>
          <p class="text-muted-foreground mt-2">Update branch information and settings</p>
        </div>
        <div>
          <Button variant="outline" @click="router.visit(route('branches.index'))">
            <ArrowLeft class="h-4 w-4 mr-2" />
            Back to Branches
          </Button>
        </div>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Basic Information Card -->
        <Card>
          <CardHeader>
            <CardTitle>Basic Information</CardTitle>
            <CardDescription>Branch's primary details and contact information</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="grid gap-6 md:grid-cols-2">
                  <div class="space-y-2">
                    <Label for="name">Branch Name <span class="text-red-500">*</span></Label>
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
                    <Label for="principal_id">Principal <span class="text-red-500">*</span></Label>
                    <SimpleCombobox
                      v-model="form.principal_id"
                      :options="principalOptions"
                      placeholder="Select principal..."
                      emptyMessage="No principals found"
                      class="w-full"
                    />
                    <InputError :message="form.errors.principal_id" />
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
          </CardContent>
        </Card>

        <!-- Address Information Card -->
        <Card>
          <CardHeader>
            <CardTitle>Address Information</CardTitle>
            <CardDescription>Physical location and contact details</CardDescription>
          </CardHeader>
          <CardContent>
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
                    <Input id="state" v-model="form.state" type="text" />
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
          </CardContent>
        </Card>

        <!-- Additional Information Card -->
        <Card>
          <CardHeader>
            <CardTitle>Additional Information</CardTitle>
            <CardDescription>Optional details and settings</CardDescription>
          </CardHeader>
          <CardContent>
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
          </CardContent>
        </Card>

        <!-- Actions -->
        <div class="flex justify-end gap-1">
          <Button type="button" variant="outline" @click="router.visit(route('branches.index'))">
            Cancel
          </Button>
          <Button type="submit" :disabled="form.processing">
            Update Branch
          </Button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import InputError from '@/components/InputError.vue'
import { Button } from '@/components/ui/button'
import { Switch } from '@/components/ui/switch'
import SimpleCombobox from '@/components/ui/SimpleCombobox.vue'
import { ArrowLeft } from 'lucide-vue-next'
import { type BreadcrumbItem } from '@/types';

interface Props {
  branch: any;
  states?: string[];
  principals: { id: string; name: string }[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Branches',
        href: '/branches',
    },
    {
        title: 'Edit Branch',
        href: `/branches/${props.branch.id}/edit`,
    },
];

const form = useForm({
  name: props.branch.name || '',
  address: props.branch.address || '',
  city: props.branch.city || '',
  state: props.branch.state || '',
  postal_code: props.branch.postal_code || '',
  country: props.branch.country || '',
  notes: props.branch.notes || '',
  website: props.branch.website || '',
  principal_id: props.branch.principal_id || '',
  established_year: props.branch.established_year || new Date().getFullYear(),
  description: props.branch.description || '',
  status: props.branch.status === 1 || props.branch.status === '1' || props.branch.status === true,
})

const principalOptions = props.principals.map((principal: { id: string; name: string }) => ({ value: principal.id, label: principal.name }));

const submit = () => {
  form.put(route('branches.update', props.branch.id))
}
</script> 