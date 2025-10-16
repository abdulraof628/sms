<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Combobox } from '@/components/ui/combobox';
import AppLayout from '@/layouts/AppLayout.vue';
import InputError from '@/components/InputError.vue';

interface Props {
    user: {
        id: number;
        name: string;
        email: string;
        roles: Array<{
            id: number;
            name: string;
        }>;
        organization_id?: string;
    };
    roles: Array<{
        id: number;
        name: string;
    }>;
    organizations: Array<{
        id: string;
        name: string;
    }>;
}

const props = defineProps<Props>();

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    role: props.user.roles[0]?.id.toString() || '',
    organization_id: props.user.organization_id || '',
});

const submit = () => {
    form.put(route('users.update', props.user.id));
};
</script>

<template>
    <Head title="Edit User" />

    <AppLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Edit User
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <div>
                                <Label for="name">Name</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    required
                                    autofocus
                                />
                                <InputError :message="form.errors.name" class="mt-2" />
                            </div>

                            <div>
                                <Label for="email">Email</Label>
                                <Input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError :message="form.errors.email" class="mt-2" />
                            </div>

                            <div>
                                <Label for="role">Role</Label>
                                <Combobox
                                    id="role"
                                    v-model="form.role"
                                    :items="roles"
                                    class="mt-1 block w-full"
                                    required
                                />
                                <InputError :message="form.errors.role" class="mt-2" />
                            </div>

                            <div>
                                <Label for="organization_id">Organization</Label>
                                <Combobox
                                    id="organization_id"
                                    v-model="form.organization_id"
                                    :items="organizations"
                                    item-value="id"
                                    item-text="name"
                                    class="mt-1 block w-full"
                                    placeholder="Select organization"
                                />
                                <InputError :message="form.errors.organization_id" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end gap-4">
                                <Button
                                    type="button"
                                    variant="outline"
                                    :disabled="form.processing"
                                    @click="router.visit(route('users.index'))"
                                >
                                    Cancel
                                </Button>
                                <Button type="submit" :disabled="form.processing">
                                    Update User
                                </Button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 