<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ref, computed } from 'vue';
import SimpleCombobox from '@/components/ui/SimpleCombobox.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Get Started',
        href: '/get-started',
    },
];

// Local state for the onboarding wizard
const currentStep = ref(1);
const totalSteps = 3;

const form = useForm({
    password: '',
    password_confirmation: '',
});

// Country options for the combobox
const countryOptions = [
    { value: 'us', label: 'United States' },
    { value: 'ca', label: 'Canada' },
    { value: 'uk', label: 'United Kingdom' },
    { value: 'au', label: 'Australia' },
    { value: 'de', label: 'Germany' },
    { value: 'fr', label: 'France' },
    { value: 'jp', label: 'Japan' },
    { value: 'in', label: 'India' },
    { value: 'br', label: 'Brazil' },
    { value: 'za', label: 'South Africa' },
];

const selectedCountry = ref<string | null>(null);
const password = ref('');
const confirmPassword = ref('');

// Password validation
const passwordValid = computed(() => {
    return password.value.length >= 8;
});

const passwordsMatch = computed(() => {
    return password.value === confirmPassword.value;
});

const canProceedFromPasswordStep = computed(() => {
    return passwordValid.value && passwordsMatch.value;
});

const nextStep = () => {
    // For step 2 (password step), validate before proceeding
    if (currentStep.value === 2 && !canProceedFromPasswordStep.value) {
        return;
    }
    
    if (currentStep.value < totalSteps) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const submitOnboarding = () => {
    form.password = password.value;
    form.password_confirmation = confirmPassword.value;
    form.post(route('password.onboarding'), {
        onSuccess: () => {
            // User will be redirected to dashboard by backend
        },
    });
};
</script>

<template>
    <Head title="Get Started" />

    <div class="flex h-screen items-center justify-center bg-gradient-to-br from-orange-50 to-orange-100">
        <div class="w-full max-w-lg p-6">
            <!-- Logo area -->
            <div class="flex justify-center mb-6">
                <div class="h-10 w-10 bg-orange-500 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xl">S</span>
                </div>
            </div>
            
            <Card class="w-full border border-gray-200 shadow-lg rounded-xl overflow-hidden bg-white">
                <!-- Progress bar at top -->
                <div class="h-1 bg-gray-100 w-full">
                    <div 
                        class="h-full bg-orange-500 transition-all duration-300 ease-in-out"
                        :style="`width: ${(currentStep / totalSteps) * 100}%`"
                    ></div>
                </div>
                
                <CardContent class="p-8">
                    <!-- Slides Container -->
                    <div class="relative w-full overflow-hidden" style="min-height: 400px">
                        <!-- Step 1: Welcome -->
                        <div 
                            class="absolute w-full transition-transform duration-500 ease-in-out"
                            :class="[
                                currentStep === 1 ? 'translate-x-0' : '',
                                currentStep > 1 ? '-translate-x-full' : '',
                                currentStep < 1 ? 'translate-x-full' : '',
                            ]"
                        >
                            <!-- Illustration -->
                            <div class="flex justify-center mb-6">
                                <div class="rounded-full bg-orange-100 p-6 w-48 h-48 flex items-center justify-center">
                                    <img src="/assets/svg/welcome.svg" alt="Welcome Illustration" class="w-36 h-36" />
                                </div>
                            </div>
                            
                            <!-- Headline -->
                            <h2 class="text-2xl font-bold mb-3 text-center text-gray-800">Welcome to <span class="text-orange-500">ManageLah</span></h2>
                            <!-- Subtitle -->
                            <p class="mb-8 text-center text-gray-600 max-w-md mx-auto">
                                Manage your school with ease, connect with your teachers, and support every child's journey — all in one place.
                            </p>
                            <!-- Get Started Button -->
                            <div class="flex justify-center">
                                <Button @click="nextStep" class="w-64 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-semibold text-base py-3 shadow-md hover:shadow-lg transition-all">
                                    Get Started
                                </Button>
                            </div>
                        </div>

                        <!-- Step 2: Create Password -->
                        <div 
                            class="absolute w-full transition-transform duration-500 ease-in-out"
                            :class="[
                                currentStep === 2 ? 'translate-x-0' : '',
                                currentStep > 2 ? '-translate-x-full' : '',
                                currentStep < 2 ? 'translate-x-full' : '',
                            ]"
                        >
                            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Create Your <span class="text-orange-500">Password</span></h2>
                            
                            <div class="space-y-4 w-full max-w-xs mx-auto">
                                <!-- Password field -->
                                <div class="space-y-2">
                                    <Label for="password" class="text-gray-700">Password</Label>
                                    <Input 
                                        id="password" 
                                        type="password" 
                                        v-model="password" 
                                        class="border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 transition-all"
                                        :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-200': password.length > 0 && !passwordValid }"
                                    />
                                    <div class="flex items-center gap-1 mt-1 text-xs" :class="passwordValid ? 'text-green-600' : 'text-gray-500'">
                                        <svg v-if="passwordValid" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Password must be at least 8 characters</span>
                                    </div>
                                </div>
                                
                                <!-- Confirm Password field -->
                                <div class="space-y-2">
                                    <Label for="confirmPassword" class="text-gray-700">Confirm Password</Label>
                                    <Input 
                                        id="confirmPassword" 
                                        type="password" 
                                        v-model="confirmPassword" 
                                        class="border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 transition-all"
                                        :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-200': confirmPassword.length > 0 && !passwordsMatch }"
                                    />
                                    <div v-if="confirmPassword.length > 0" class="flex items-center gap-1 mt-1 text-xs" :class="passwordsMatch ? 'text-green-600' : 'text-red-500'">
                                        <svg v-if="passwordsMatch" xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        <span>{{ passwordsMatch ? 'Passwords match' : 'Passwords do not match' }}</span>
                                    </div>
                                </div>
                                
                                <div class="pt-4">
                                    <div class="flex justify-between w-full mt-4">
                                        <Button variant="outline" @click="prevStep" class="border-gray-300 hover:bg-gray-50">Back</Button>
                                        <Button 
                                            @click="nextStep" 
                                            class="bg-orange-500 hover:bg-orange-600 text-white shadow-sm hover:shadow transition-all"
                                            :disabled="!canProceedFromPasswordStep"
                                            :class="{ 'opacity-50 cursor-not-allowed': !canProceedFromPasswordStep }"
                                        >
                                            Next
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Success -->
                        <div 
                            class="absolute w-full transition-transform duration-500 ease-in-out"
                            :class="[
                                currentStep === 3 ? 'translate-x-0' : '',
                                currentStep > 3 ? '-translate-x-full' : '',
                                currentStep < 3 ? 'translate-x-full' : '',
                            ]"
                        >
                            <div class="flex justify-center mb-6">
                                <div class="rounded-full bg-green-100 p-4 w-20 h-20 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            
                            <h2 class="text-2xl font-bold mb-3 text-center text-gray-800">All Set!</h2>
                            <p class="mb-8 text-center text-gray-600 max-w-md mx-auto">
                                Your account is ready to go. You can now start using your dashboard to manage your school.
                            </p>
                            
                            <div class="flex justify-center">
                                <Button @click="submitOnboarding" class="w-64 rounded-lg bg-orange-500 hover:bg-orange-600 text-white font-semibold text-base py-3 shadow-md hover:shadow-lg transition-all">
                                    Go to Dashboard
                                </Button>
                            </div>
                        </div>
                    </div>

                    <!-- Stepper Dots -->
                    <div class="flex justify-center gap-3 mt-8">
                        <span v-for="step in totalSteps" :key="step" 
                            :class="[
                                'inline-block w-2 h-2 rounded-full transition-all duration-300',
                                step === currentStep ? 'bg-orange-500 w-6' : 'bg-orange-200 hover:bg-orange-300 cursor-pointer'
                            ]"
                            @click="currentStep = step"
                        ></span>
                    </div>
                </CardContent>
            </Card>
            
            <!-- Footer text -->
            <p class="text-center text-gray-500 text-xs mt-4">
                &copy; 2023 ManageLah. All rights reserved.
            </p>
        </div>
    </div>
</template>

<style scoped>
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.5s ease-in-out;
}

.slide-enter-from {
    transform: translateX(100%);
}

.slide-leave-to {
    transform: translateX(-100%);
}
</style> 