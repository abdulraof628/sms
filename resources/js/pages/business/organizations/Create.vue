<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Switch } from '@/components/ui/switch';
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';
import Combobox from '@/components/ui/combobox/Combobox.vue';
import ComboboxInput from '@/components/ui/combobox/ComboboxInput.vue';
import ComboboxList from '@/components/ui/combobox/ComboboxList.vue';
import ComboboxItem from '@/components/ui/combobox/ComboboxItem.vue';
import SimpleCombobox from '@/components/ui/SimpleCombobox.vue';
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
        title: 'Create Organization',
        href: '/business/organizations/create',
    },
];

interface Props {
    states: string[];
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
    is_active: true,
    ssm_number: '',
});

const malaysiaStates = props.states.map(state => ({ value: state, label: state }));

const submit = () => {
    form.post(route('business.organizations.store'));
};
</script>

<template>
    <Head title="Create Organization" />

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
                                    Create Organization
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 