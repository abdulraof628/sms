<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';

const form = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
    domain: '',
    school_code: '',
});

const submit = () => {
    form.post(route('tenants.store'));
};
</script>

<template>
    <AppLayout title="Create Tenant">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create New Tenant
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Create New Tenant</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="space-y-2">
                                <Label for="name">Name</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    placeholder="e.g., Name"
                                    :class="{ 'border-red-500': form.errors.name }"
                                />
                                <p v-if="form.errors.name" class="text-sm text-red-500">
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="domain">Domain</Label>
                                <Input
                                    id="domain"
                                    v-model="form.domain"
                                    type="text"
                                    required
                                    placeholder="e.g., example.com"
                                    :class="{ 'border-red-500': form.errors.domain }"
                                />
                                <p v-if="form.errors.domain" class="text-sm text-red-500">
                                    {{ form.errors.domain }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="email">Email</Label>
                                <Input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    :class="{ 'border-red-500': form.errors.email }"
                                />
                                <p v-if="form.errors.email" class="text-sm text-red-500">
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <div class="flex justify-end space-x-1">
                                <Button
                                    type="button"
                                    variant="outline"
                                    @click="router.visit(route('tenants.index'))"
                                >
                                    Cancel
                                </Button>
                                <Button
                                    type="submit"
                                    variant="default"
                                    :disabled="form.processing"
                                >
                                    Create Tenant
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template> 