<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import { type BreadcrumbItem } from '@/types';
import { ArrowLeft } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Tenants',
        href: '/super-admin/tenants',
    },
    {
        title: 'Create Tenant',
        href: '/super-admin/tenants/create',
    },
];

interface Props {
    states?: string[];
}

const props = defineProps<Props>();

const form = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    state: '',
    postal_code: '',
    country: '',
    notes: '',
    ssm_number: '',
});

const submit = () => {
    form.post(route('superadmin.tenants.store'));
};
</script>

<template>
    <Head title="Create Tenant" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="mx-auto w-full p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-foreground">Create New Tenant</h1>
                    <p class="text-muted-foreground mt-2">Add a new tenant to the system</p>
                </div>
                <div>
                    <Button variant="outline" @click="router.visit(route('superadmin.tenants.index'))">
                        <ArrowLeft class="h-4 w-4 mr-2" />
                        Back to Tenants
                    </Button>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Basic Information Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Basic Information</CardTitle>
                        <CardDescription>Tenant's primary details and registration information</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid gap-6 md:grid-cols-2">
                            <div class="space-y-2">
                                <Label for="name">Name <span class="text-red-500">*</span></Label>
                                <Input id="name" v-model="form.name" type="text" />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="space-y-2">
                                <Label for="ssm_number">SSM No. <span class="text-red-500">*</span></Label>
                                <Input id="ssm_number" v-model="form.ssm_number" type="text" />
                                <InputError :message="form.errors.ssm_number" />
                            </div>
                            <div class="space-y-2">
                                <Label for="email">Email <span class="text-red-500">*</span></Label>
                                <Input id="email" v-model="form.email" type="email" />
                                <InputError :message="form.errors.email" />
                            </div>
                            <div class="space-y-2">
                                <Label for="phone">Phone <span class="text-red-500">*</span></Label>
                                <Input id="phone" v-model="form.phone" type="tel" />
                                <InputError :message="form.errors.phone" />
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
                                <Input id="address" v-model="form.address" type="text" />
                                <InputError :message="form.errors.address" />
                            </div>
                            <div class="space-y-2">
                                <Label for="country">Country <span class="text-red-500">*</span></Label>
                                <Input id="country" v-model="form.country" type="text" />
                                <InputError :message="form.errors.country" />
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
                        </div>
                    </CardContent>
                </Card>

                <!-- Additional Information Card -->
                <Card>
                    <CardHeader>
                        <CardTitle>Additional Information</CardTitle>
                        <CardDescription>Optional notes and remarks</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <Label for="notes">Notes</Label>
                            <Textarea id="notes" v-model="form.notes" rows="4" />
                            <InputError :message="form.errors.notes" />
                        </div>
                    </CardContent>
                </Card>

                <!-- Actions -->
                <div class="flex justify-end gap-1">
                    <Button type="button" variant="outline" @click="router.visit(route('superadmin.tenants.index'))">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        Create Tenant
                    </Button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
