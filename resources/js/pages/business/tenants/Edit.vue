<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

interface Tenant {
    id: string;
    name: string;
    domain: string | null;
    email: string | null;
}

const props = defineProps<{
    tenant: Tenant;
}>();

const form = ref({
    name: props.tenant.name,
    domain: props.tenant.domain || '',
    email: props.tenant.email || '',
});

const submit = () => {
    router.put(route('tenants.update', props.tenant.id), form.value);
};
</script>

<template>
    <AppLayout title="Edit Tenant">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Tenant
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle>Edit Tenant Information</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div class="space-y-2">
                                <Label for="name">Name</Label>
                                <Input id="name" v-model="form.name" required />
                            </div>

                            <div class="space-y-2">
                                <Label for="domain">Domain</Label>
                                <Input id="domain" v-model="form.domain" />
                            </div>  

                            <div class="space-y-2">
                                <Label for="email">Email</Label>
                                <Input id="email" v-model="form.email" />
                            </div>

                            <div class="flex justify-end space-x-2">
                                <Button 
                                    type="button" 
                                    variant="outline" 
                                    @click="router.visit(route('tenants.index'))"
                                >
                                    Cancel
                                </Button>
                                <Button type="submit">
                                    Update Tenant
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template> 