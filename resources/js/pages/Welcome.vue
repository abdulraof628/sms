<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

const isMobileMenuOpen = ref(false);
const currentSection = ref('home');

const menuItems = [
    { 
        label: 'Home', 
        icon: 'fas fa-home', 
        section: 'home', 
        command: () => {
            currentSection.value = 'home';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },
    { 
        label: 'Features', 
        icon: 'fas fa-list', 
        section: 'features', 
        command: () => {
            currentSection.value = 'features';
            document.getElementById('features')?.scrollIntoView({ behavior: 'smooth' });
        }
    },
    { 
        label: 'Pricing', 
        icon: 'fas fa-tag', 
        section: 'pricing', 
        command: () => {
            currentSection.value = 'pricing';
            document.getElementById('pricing')?.scrollIntoView({ behavior: 'smooth' });
        }
    },
    { 
        label: 'Contact', 
        icon: 'fas fa-phone', 
        section: 'contact', 
        command: () => {
            currentSection.value = 'contact';
            document.getElementById('contact')?.scrollIntoView({ behavior: 'smooth' });
        }
    },
];

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};

const handleScroll = () => {
    const sections = ['home', 'features', 'pricing', 'contact'];
    const scrollPosition = window.scrollY + window.innerHeight / 3;

    for (const section of sections) {
        const element = section === 'home' ? document.documentElement : document.getElementById(section);
        if (element) {
            const rect = element.getBoundingClientRect();
            const elementTop = rect.top + window.scrollY;
            const elementBottom = elementTop + rect.height;

            if (scrollPosition >= elementTop && scrollPosition <= elementBottom) {
                currentSection.value = section;
                break;
            }
        }
    }
};

onMounted(() => {
    // Initial check
    handleScroll();
});

onUnmounted(() => {
    // Clean up
    window.removeEventListener('scroll', handleScroll);
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Home">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    </Head>
    <div class="min-h-screen flex flex-col bg-gradient-to-b from-[var(--color-background)] to-white">
        <!-- Navigation -->
        <nav class="w-full glass-effect sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-3">
                        <Link :href="route('dashboard')" class="flex items-center gap-3 group">
                            <div class="relative">
                                <div class="absolute inset-0 bg-[var(--color-primary)] rounded-full blur-md opacity-50"></div>
                                <i class="fas fa-graduation-cap text-2xl relative text-[var(--color-primary)]"></i>
                            </div>
                            <span class="text-xl font-extrabold tracking-tight text-[var(--color-text)] group-hover:text-[var(--color-secondary)] transition-colors duration-300">Manage<span class="text-[var(--color-primary)]">Lah</span></span>
                        </Link>
                    </div>
                    <div class="hidden lg:flex items-center gap-6">
                        <a v-for="item in menuItems" :key="item.label" 
                           :class="[
                               'text-[var(--color-text)] hover:bg-[var(--color-primary)] transition-all duration-300 flex items-center gap-2 cursor-pointer px-3 py-2 rounded-lg menu-item-hover',
                               { 'bg-[var(--color-primary)] text-white border-b-2 border-[var(--color-secondary)] shadow-lg shadow-[var(--color-primary)]/20': currentSection === item.section }
                           ]"
                           @click="item.command">
                            <i :class="[item.icon, { 'text-white': currentSection === item.section }]"></i>
                            <span>{{ item.label }}</span>
                        </a>
                    </div>
                    <div class="hidden lg:flex items-center gap-4">
                        <Link :href="route('login')" class="px-4 py-2 rounded-lg text-[var(--color-primary)] border border-[var(--color-primary)] hover:bg-[var(--color-primary)]/10 transition-all duration-300 flex items-center gap-2 button-hover">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Login</span>
                        </Link>
                        <Link href="#" class="px-4 py-2 rounded-lg bg-[var(--color-primary)] text-white hover:bg-[var(--color-primary-dark)] transition-all duration-300 flex items-center gap-2 button-hover">
                            <i class="fas fa-user-plus"></i>
                            <span>Get Started</span>
                        </Link>
                    </div>
                    <div class="lg:hidden">
                        <button class="text-[var(--color-text)] hover-scale" @click="toggleMobileMenu">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div v-show="isMobileMenuOpen" class="lg:hidden animate-slide-in">
                    <div class="px-2 pt-2 pb-3 space-y-1 bg-[var(--color-primary)] rounded-lg shadow-lg mt-2">
                        <a v-for="item in menuItems" :key="item.label"
                           class="block px-3 py-2 rounded-md text-base font-medium text-white hover:bg-[var(--color-primary-dark)] transition-all duration-300 flex items-center gap-2 cursor-pointer menu-item-hover"
                           @click="() => { item.command(); toggleMobileMenu(); }">
                            <i :class="item.icon"></i>
                            <span>{{ item.label }}</span>
                        </a>
                        <div class="border-t border-white/20 my-2"></div>
                        <div class="flex flex-col gap-2 px-3 py-2">
                            <Link :href="route('login')" class="w-full px-4 py-2 rounded-lg text-white border border-white/30 hover:bg-white/10 transition-all duration-300 flex items-center gap-2 justify-center button-hover">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Login</span>
                            </Link>
                            <Link href="#" class="w-full px-4 py-2 rounded-lg bg-white text-[var(--color-primary)] hover:bg-white/90 transition-all duration-300 flex items-center gap-2 justify-center button-hover">
                                <i class="fas fa-user-plus"></i>
                                <span>Get Started</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-gradient-to-b from-[var(--color-background)] to-white">
            <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">
                <div class="grid md:grid-cols-2 gap-8 md:gap-12 items-center">
                    <div class="space-y-6 md:space-y-8 text-center md:text-left animate-slide-in">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-[var(--color-text)] leading-tight">
                            Streamline Your <span class="text-[var(--color-primary)] relative">
                                School Management
                                <span class="absolute -bottom-2 left-0 w-full h-1 bg-[var(--color-primary)] transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></span>
                            </span>
                        </h1>
                        <p class="text-lg md:text-xl text-[var(--color-text-light)] leading-relaxed">
                            A comprehensive solution for managing students, teachers, classes, and administrative tasks. 
                            Make your school operations more efficient and organized.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                            <button class="bg-[var(--color-primary)] hover:bg-[var(--color-primary-dark)] border-none px-8 py-3 rounded-lg text-white font-semibold transition-all duration-300 flex items-center gap-2 button-hover animate-pulse-glow">
                                <i class="fas fa-rocket"></i>
                                <span>Get Started</span>
                            </button>
                            <button class="text-[var(--color-text)] hover:bg-[var(--color-primary)] px-8 py-3 rounded-lg transition-all duration-300 border-2 border-[var(--color-primary)] flex items-center gap-2 button-hover hover:text-white">
                                <i class="fas fa-phone-alt"></i>
                                <a href="https://wa.me/60192453854?text=Hi%20there!%20I'm%20interested%20in%20learning%20more%20about%20Sekolah%20Lah%20and%20would%20like%20to%20schedule%20a%20demo.%20Could%20you%20please%20provide%20more%20information?" target="_blank" class="flex items-center gap-2">
                                    <span>Contact Us for Demo</span>
                                </a>
                            </button>
                        </div>
                    </div>
                    <div class="relative order-first md:order-last">
                        <div class="absolute inset-0 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] opacity-20 rounded-full blur-3xl transform -translate-x-1/2 -translate-y-1/2"></div>
                        <img src="/assets/images/school.png" alt="School Illustration" 
                             class="w-full max-w-lg mx-auto transform hover:scale-105 transition-all duration-300 animate-float relative z-10" />
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-12 md:py-20 bg-gradient-to-b from-white to-[var(--color-background)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center">
                <div class="text-center mb-12 md:mb-16 animate-slide-in">
                    <h2 class="text-3xl md:text-4xl font-bold text-[var(--color-text)] mb-4">Powerful Features</h2>
                    <p class="text-lg md:text-xl text-[var(--color-text-light)]">Transform Your School with Smart Management Tools</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8 w-full">
                    <div class="group relative glass-effect rounded-2xl p-6 hover-lift hover-glow transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-br from-[var(--color-primary)]/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative z-10">
                            <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-[var(--color-primary)] flex items-center justify-center animate-pulse-slow group-hover:scale-110 transition-transform duration-500">
                                <i class="fas fa-users-cog text-2xl text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-[var(--color-text)] mb-3 text-center group-hover:text-[var(--color-primary)] transition-colors duration-300">Smart Student Hub</h3>
                            <p class="text-[var(--color-text-light)] text-center leading-relaxed">Effortlessly manage student records, track progress, and monitor attendance with our intuitive dashboard</p>
                        </div>
                    </div>

                    <div class="group relative glass-effect rounded-2xl p-6 hover-lift hover-glow transition-all duration-500">
                        <div class="absolute inset-0 bg-gradient-to-br from-[var(--color-secondary)]/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative z-10">
                            <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-[var(--color-secondary)] flex items-center justify-center animate-pulse-slow group-hover:scale-110 transition-transform duration-500">
                                <i class="fas fa-calendar-check text-2xl text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-[var(--color-text)] mb-3 text-center group-hover:text-[var(--color-secondary)] transition-colors duration-300">Dynamic Scheduling</h3>
                            <p class="text-[var(--color-text-light)] text-center leading-relaxed">Create and manage class schedules with smart conflict detection and automatic room allocation</p>
                        </div>
                    </div>

                    <div class="group relative glass-effect rounded-2xl p-6 hover-lift hover-glow transition-all duration-500 sm:col-span-2 md:col-span-1">
                        <div class="absolute inset-0 bg-gradient-to-br from-[var(--color-accent)]/20 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="relative z-10">
                            <div class="w-16 h-16 mx-auto mb-6 rounded-full bg-[var(--color-accent)] flex items-center justify-center animate-pulse-slow group-hover:scale-110 transition-transform duration-500">
                                <i class="fas fa-chart-line text-2xl text-white"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-[var(--color-text)] mb-3 text-center group-hover:text-[var(--color-accent)] transition-colors duration-300">Insightful Analytics</h3>
                            <p class="text-[var(--color-text-light)] text-center leading-relaxed">Get real-time insights into academic performance, attendance trends, and school-wide metrics</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="py-12 md:py-20 bg-gradient-to-b from-[var(--color-background)] to-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 md:mb-16 animate-slide-in">
                    <h2 class="text-3xl md:text-4xl font-bold text-[var(--color-text)] mb-4">Flexible Pricing Plans</h2>
                    <p class="text-lg md:text-xl text-[var(--color-text-light)]">Tailored solutions for schools of all sizes</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <div class="glass-effect rounded-2xl p-8 hover-lift hover-glow transition-all duration-500">
                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-[var(--color-text)] mb-4">Starter</h3>
                            <p class="text-[var(--color-text-light)] mb-8">Perfect for small schools just getting started</p>
                            <ul class="space-y-4 mb-8 text-left">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-[var(--color-success)]"></i>
                                    <span>Up to 150 students</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-[var(--color-success)]"></i>
                                    <span>Basic features</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-[var(--color-success)]"></i>
                                    <span>Email support</span>
                                </li>
                            </ul>
                            <button class="w-full bg-[var(--color-primary)] hover:bg-[var(--color-primary-dark)] text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 button-hover">
                                <a href="https://wa.me/60192453854?text=Hi%20there!%20I'm%20interested%20in%20the%20Starter%20plan%20for%20our%20school.%20Could%20you%20please%20provide%20more%20details%20about%20the%20features%20and%20implementation%20process?" target="_blank" class="flex items-center gap-2 justify-center">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>Contact Sales</span>
                                </a>
                            </button>
                        </div>
                    </div>

                    <div class="glass-effect rounded-2xl p-8 hover-lift hover-glow transition-all duration-500 transform scale-105 relative">
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] text-white px-4 py-1 rounded-full text-sm font-semibold animate-pulse-slow">
                            Most Popular
                        </div>
                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-[var(--color-text)] mb-4">Professional</h3>
                            <p class="text-[var(--color-text-light)] mb-8">Ideal for growing schools</p>
                            <ul class="space-y-4 mb-8 text-left">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-[var(--color-success)]"></i>
                                    <span>Up to 400 students</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-[var(--color-success)]"></i>
                                    <span>Advanced features</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-[var(--color-success)]"></i>
                                    <span>Priority support</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-[var(--color-success)]"></i>
                                    <span>Custom integrations</span>
                                </li>
                            </ul>
                            <button class="w-full bg-[var(--color-primary)] hover:bg-[var(--color-primary-dark)] text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 button-hover animate-pulse-glow">
                                <a href="https://wa.me/60192453854?text=Hi%20there!%20I'm%20interested%20in%20the%20Professional%20plan%20for%20our%20school.%20Could%20you%20please%20provide%20more%20details%20about%20the%20features%20and%20implementation%20process?" target="_blank" class="flex items-center gap-2 justify-center">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>Contact Sales</span>
                                </a>
                            </button>
                        </div>
                    </div>

                    <div class="glass-effect rounded-2xl p-8 hover-lift hover-glow transition-all duration-500">
                        <div class="text-center">
                            <h3 class="text-2xl font-bold text-[var(--color-text)] mb-4">Enterprise</h3>
                            <p class="text-[var(--color-text-light)] mb-8">For large institutions</p>
                            <ul class="space-y-4 mb-8 text-left">
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-[var(--color-success)]"></i>
                                    <span>Up to 1000 students</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-[var(--color-success)]"></i>
                                    <span>All features included</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-[var(--color-success)]"></i>
                                    <span>24/7 dedicated support</span>
                                </li>
                                <li class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-[var(--color-success)]"></i>
                                    <span>Custom development</span>
                                </li>
                            </ul>
                            <button class="w-full bg-[var(--color-primary)] hover:bg-[var(--color-primary-dark)] text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 button-hover">
                                <a href="https://wa.me/60192453854?text=Hi%20there!%20I'm%20interested%20in%20the%20Enterprise%20plan%20for%20our%20institution.%20Could%20you%20please%20provide%20more%20details%20about%20the%20features%20and%20implementation%20process?" target="_blank" class="flex items-center gap-2 justify-center">
                                    <i class="fab fa-whatsapp"></i>
                                    <span>Contact Sales</span>
                                </a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="contact" class="py-12 md:py-20 bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 md:mb-8 animate-slide-in">Ready to Transform Your School Management?</h2>
                <p class="text-lg md:text-xl text-white/90 mb-8 max-w-2xl mx-auto animate-slide-in">
                    Join schools that are already benefiting from our comprehensive management solution. 
                    Start streamlining your administrative tasks today!
                </p>
                <button class="bg-white hover:bg-white/90 text-[var(--color-primary)] px-6 md:px-8 py-3 text-base md:text-lg font-semibold rounded-lg flex items-center gap-2 mx-auto button-hover animate-pulse-slow">
                    <a href="https://wa.me/60192453854?text=Hi%20there!%20I'm%20interested%20in%20learning%20more%20about%20Sekolah%20Lah%20and%20would%20like%20to%20schedule%20a%20demo.%20Could%20you%20please%20provide%20more%20information?" target="_blank" class="flex items-center gap-2">
                        <i class="fab fa-whatsapp"></i>
                        <span>Contact Us for Demo</span>
                    </a>
                </button>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gradient-to-b from-[var(--color-text)] to-[var(--color-text)]/90 text-white py-8 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="col-span-2 md:col-span-1">
                        <h3 class="text-white text-lg font-semibold mb-4">School POC</h3>
                        <p class="text-sm text-white/80">Learning is fun with us!</p>
                    </div>
                    <div>
                        <h4 class="text-white text-lg font-semibold mb-4">Quick Links</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-[var(--color-primary)] transition-colors duration-300 hover:scale-105 inline-block">About Us</a></li>
                            <li><a href="#" class="hover:text-[var(--color-primary)] transition-colors duration-300 hover:scale-105 inline-block">Games</a></li>
                            <li><a href="#" class="hover:text-[var(--color-primary)] transition-colors duration-300 hover:scale-105 inline-block">Stories</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white text-lg font-semibold mb-4">Help</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-[var(--color-primary)] transition-colors duration-300 hover:scale-105 inline-block">Parents</a></li>
                            <li><a href="#" class="hover:text-[var(--color-primary)] transition-colors duration-300 hover:scale-105 inline-block">Teachers</a></li>
                            <li><a href="#" class="hover:text-[var(--color-primary)] transition-colors duration-300 hover:scale-105 inline-block">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <h4 class="text-white text-lg font-semibold mb-4">Legal</h4>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-[var(--color-primary)] transition-colors duration-300 hover:scale-105 inline-block">Privacy</a></li>
                            <li><a href="#" class="hover:text-[var(--color-primary)] transition-colors duration-300 hover:scale-105 inline-block">Safety</a></li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-white/20 mt-8 pt-8 text-center">
                    <p>&copy; {{ new Date().getFullYear() }} ManageLah. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
/* Custom Animations */
@keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
    100% { transform: translateY(0px); }
}

@keyframes pulse-glow {
    0% { box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.4); }
    70% { box-shadow: 0 0 0 20px rgba(249, 115, 22, 0); }
    100% { box-shadow: 0 0 0 0 rgba(249, 115, 22, 0); }
}

@keyframes slide-in {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Custom Classes */
.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-pulse-glow {
    animation: pulse-glow 2s infinite;
}

.animate-slide-in {
    animation: slide-in 0.6s ease-out forwards;
}

.hover-lift {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.hover-glow:hover {
    box-shadow: 0 0 20px rgba(249, 115, 22, 0.3);
}

.button-hover {
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.button-hover::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s ease, height 0.6s ease;
}

.button-hover:hover::after {
    width: 300px;
    height: 300px;
}

.menu-item-hover {
    position: relative;
    overflow: hidden;
}

.menu-item-hover::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: #f97316;
    transform: scaleX(0);
    transform-origin: right;
    transition: transform 0.3s ease;
}

.menu-item-hover:hover::before {
    transform: scaleX(1);
    transform-origin: left;
}

/* Glassmorphism */
.glass-effect {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.glass-effect:hover {
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f3f4f6;
}

::-webkit-scrollbar-thumb {
    background: #f97316;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #ea580c;
}

/* CSS Variables */
:root {
    --color-primary: #f97316;
    --color-primary-dark: #ea580c;
    --color-secondary: #f59e0b;
    --color-secondary-dark: #d97706;
    --color-accent: #fbbf24;
    --color-accent-dark: #f59e0b;
    --color-background: #fff7ed;
    --color-text: #1e293b;
    --color-text-light: #64748b;
    --color-success: #22c55e;
    --color-warning: #f59e0b;
    --color-error: #ef4444;
}

/* Grid Pattern */
.bg-grid-pattern {
    background-image: linear-gradient(var(--color-primary) 1px, transparent 1px),
                      linear-gradient(90deg, var(--color-primary) 1px, transparent 1px);
    background-size: 20px 20px;
}
</style>
