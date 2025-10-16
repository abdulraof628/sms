<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Switch } from '@/components/ui/switch';
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import { type BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Organizations',
        href: '/business/organizations',
    },
    {
        title: 'Edit Organization',
        href: '/business/organizations/edit',
    },
];

interface Props {
    organization: {
        id: string;
        name: string;
        email: string;
        phone: string | null;
        address: string | null;
        notes: string | null;
        is_active: boolean;
        ssm_number: string;
        city: string | null;
        state: string | null;
        postal_code: string | null;
        country: string | null;
    };
}

const props = defineProps<Props>();

const form = useForm({
    name: props.organization.name,
    email: props.organization.email,
    phone: props.organization.phone || '',
    address: props.organization.address || '',
    city: props.organization.city || '',
    state: props.organization.state || '',
    postal_code: props.organization.postal_code || '',
    country: props.organization.country || '',
    notes: props.organization.notes || '',
    is_active: props.organization.is_active,
    ssm_number: props.organization.ssm_number || '',
});

const submit = () => {
    form.put(route('business.organizations.update', props.organization.id));
};
</script>

<template>
    <Head title="Edit Organization" />

    <AppLayout :breadcrumbs="breadcrumbs">

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold mb-4">Basic Info</h3>
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
                            </div>
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold mb-4">Address Info</h3>
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
                            </div>
                            <div class="space-y-2 md:col-span-2">
                                <Label for="notes">Notes</Label>
                                <Textarea id="notes" v-model="form.notes" />
                                <InputError :message="form.errors.notes" />
                            </div>

                            <div class="flex items-center space-x-2">
                                <Switch id="is_active" v-model="form.is_active" />
                                <Label for="is_active">Active</Label>
                            </div>

                            <div class="flex justify-end gap-1">
                                <Button type="button" variant="outline" @click="router.visit(route('business.organizations.index'))">
                                    Cancel
                                </Button>
                                <Button type="submit" :disabled="form.processing">
                                    Update Organization
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 