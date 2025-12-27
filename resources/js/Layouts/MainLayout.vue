<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Toast from '@/Components/Toast.vue';
import ScrollToTop from '@/Components/ScrollToTop.vue';
import ExitModal from '@/Components/ExitModal.vue';
import CallbackModal from '@/Components/CallbackModal.vue';
import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import { useTranslations } from '@/Composables/useTranslations';

const { __ } = useTranslations();
const mobileMenuOpen = ref(false);
const callbackModalRef = ref(null);
const currentYear = new Date().getFullYear();

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
};

const openCallbackModal = () => {
    if (callbackModalRef.value) {
        callbackModalRef.value.open();
    }
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 font-sans text-slate-900">
        <Toast />
        <ScrollToTop />
        <ExitModal />
        <CallbackModal ref="callbackModalRef" />

        <!-- Navbar -->
        <nav class="fixed w-full z-50 bg-white/95 backdrop-blur-xl border-b border-slate-200 shadow-sm transition-all duration-300" id="navbar">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-24 py-2 relative">
                    <!-- Logo -->
                    <Link href="/" class="flex-shrink-0 flex items-center cursor-pointer group">
                        <img src="/images/unnamed.png" alt="Central Auto B Logo" class="h-20 w-auto object-contain logo-shine group-hover:scale-105 transition-transform duration-300">
                    </Link>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex space-x-8 items-center absolute left-1/2 transform -translate-x-1/2">
                        <Link href="/" class="nav-link text-slate-700 hover:text-brand-blue font-semibold transition-all duration-300 relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-brand-blue after:transition-all hover:after:w-full">{{ __('home') }}</Link>
                        <Link href="/catalogue?type=sale" class="nav-link text-slate-700 hover:text-brand-blue font-semibold transition-all duration-300 relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-brand-blue after:transition-all hover:after:w-full">{{ __('vehicles') }}</Link>
                        <Link href="/plaques" class="nav-link text-slate-700 hover:text-brand-blue font-semibold transition-all duration-300 relative after:absolute after:bottom-0 after:left-0 after:w-0 after:h-0.5 after:bg-brand-blue after:transition-all hover:after:w-full">{{ __('plates') }}</Link>
                    </div>

                    <!-- Contact Button & Language Switcher -->
                    <div class="hidden md:flex items-center gap-4">
                        <LanguageSwitcher />
                        <a href="#contact" class="bg-brand-gradient text-white px-6 py-2.5 rounded-full font-bold hover:opacity-90 transition-all shadow-lg flex items-center gap-2 transform hover:scale-105 duration-300 btn-glow relative overflow-hidden">
                            <i class="ph-bold ph-phone relative z-10"></i> <span class="relative z-10">{{ __('contact') }}</span>
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="md:hidden flex items-center">
                        <button @click="toggleMobileMenu" class="text-slate-700 hover:text-brand-blue focus:outline-none p-2">
                            <i class="ph ph-list text-3xl"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu Panel -->
            <div v-show="mobileMenuOpen" class="md:hidden bg-white border-t border-slate-100 absolute w-full shadow-xl">
                <div class="px-4 pt-2 pb-6 space-y-2">
                    <Link href="/" class="block px-3 py-4 rounded-lg text-lg font-medium text-slate-700 hover:bg-slate-50 border-b border-slate-50">Accueil</Link>
                    <Link href="/catalogue?type=sale" class="block px-3 py-4 rounded-lg text-lg font-medium text-slate-700 hover:bg-slate-50 border-b border-slate-50">Acheter</Link>
                    <Link href="/catalogue?type=rent" class="block px-3 py-4 rounded-lg text-lg font-medium text-slate-700 hover:bg-slate-50 border-b border-slate-50">Louer</Link>
                    <a href="/#custom-request" class="block px-3 py-4 rounded-lg text-lg font-bold text-transparent bg-clip-text bg-gradient-to-r from-brand-orange via-brand-red to-brand-blue">Vendre</a>
                    <a href="#contact" class="block px-3 py-4 rounded-lg text-lg font-medium text-slate-700 hover:bg-slate-50">Contact</a>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="relative bg-gradient-to-br from-brand-dark via-slate-900 to-brand-dark overflow-hidden" id="contact">
            <!-- Decorative top border -->
            <div class="absolute top-0 left-0 right-0 h-1 bg-brand-gradient"></div>
            
            <!-- Decorative blobs -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-blue/10 rounded-full blur-[100px] translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-brand-orange/10 rounded-full blur-[100px] -translate-x-1/2 translate-y-1/2"></div>
            
            <!-- Pre-Footer CTA -->
            <div class="relative z-10 border-b border-white/10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                    <div class="flex flex-col lg:flex-row items-center justify-between gap-8 bg-white/5 backdrop-blur-sm rounded-3xl p-8 md:p-10 border border-white/10">
                        <div class="text-center lg:text-left">
                            <h3 class="text-2xl md:text-3xl font-bold text-white mb-2">Prêt à trouver votre véhicule idéal ?</h3>
                            <p class="text-slate-400">Contactez-nous dès maintenant pour une expérience personnalisée</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a :href="'tel:' + $page.props.settings.contact_phone" class="group flex items-center gap-3 bg-brand-gradient text-white font-bold px-8 py-4 rounded-2xl hover:opacity-90 transition-all shadow-lg">
                                <i class="ph-fill ph-phone text-xl"></i>
                                <span>{{ $page.props.settings.contact_phone }}</span>
                            </a>
                            <a href="#custom-request" class="flex items-center gap-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white font-bold px-8 py-4 rounded-2xl transition-all border border-white/20">
                                <i class="ph-fill ph-envelope text-xl"></i>
                                <span>Nous contacter</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Footer -->
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-12 mb-16">
                    <!-- Brand Column -->
                    <div class="lg:col-span-1">
                        <img src="/images/unnamed.png" alt="Central Auto B Logo" class="h-28 w-auto mb-6 object-contain brightness-0 invert">
                        <p class="text-slate-400 text-sm leading-relaxed mb-8">
                            Votre partenaire de confiance pour l'achat, la vente et la location de véhicules. Expertise, transparence et satisfaction client depuis plus de 10 ans.
                        </p>
                        <div class="flex space-x-3">
                            <a href="#" class="group w-12 h-12 rounded-2xl bg-white/5 backdrop-blur-sm flex items-center justify-center text-slate-400 hover:bg-brand-blue hover:text-white transition-all duration-300 border border-white/10 hover:border-brand-blue hover:scale-110 hover:-translate-y-1">
                                <i class="ph-fill ph-facebook-logo text-xl"></i>
                            </a>
                            <a href="#" class="group w-12 h-12 rounded-2xl bg-white/5 backdrop-blur-sm flex items-center justify-center text-slate-400 hover:bg-gradient-to-br hover:from-pink-500 hover:to-orange-500 hover:text-white transition-all duration-300 border border-white/10 hover:border-pink-500 hover:scale-110 hover:-translate-y-1">
                                <i class="ph-fill ph-instagram-logo text-xl"></i>
                            </a>
                            <a href="#" class="group w-12 h-12 rounded-2xl bg-white/5 backdrop-blur-sm flex items-center justify-center text-slate-400 hover:bg-brand-blue hover:text-white transition-all duration-300 border border-white/10 hover:border-brand-blue hover:scale-110 hover:-translate-y-1">
                                <i class="ph-fill ph-linkedin-logo text-xl"></i>
                            </a>
                            <a href="#" class="group w-12 h-12 rounded-2xl bg-white/5 backdrop-blur-sm flex items-center justify-center text-slate-400 hover:bg-red-500 hover:text-white transition-all duration-300 border border-white/10 hover:border-red-500 hover:scale-110 hover:-translate-y-1">
                                <i class="ph-fill ph-youtube-logo text-xl"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Services Column -->
                    <div>
                        <h4 class="font-bold text-white mb-6 text-lg flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-brand-orange/20 flex items-center justify-center">
                                <i class="ph-bold ph-wrench text-brand-orange text-sm"></i>
                            </div>
                            Services
                        </h4>
                        <ul class="space-y-3">
                            <li><Link href="/catalogue?type=sale" class="group flex items-center gap-3 text-slate-400 hover:text-white transition-all py-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-orange/50 group-hover:bg-brand-orange transition-colors"></span>
                                Achat Neuf & Occasion
                            </Link></li>
                            <li><Link href="/catalogue?type=rent" class="group flex items-center gap-3 text-slate-400 hover:text-white transition-all py-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-orange/50 group-hover:bg-brand-orange transition-colors"></span>
                                Location de véhicules
                            </Link></li>
                            <li><a href="#custom-request" class="group flex items-center gap-3 text-slate-400 hover:text-white transition-all py-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-orange/50 group-hover:bg-brand-orange transition-colors"></span>
                                Reprise Cash Immédiate
                            </a></li>
                            <li><a href="#custom-request" class="group flex items-center gap-3 text-slate-400 hover:text-white transition-all py-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-orange/50 group-hover:bg-brand-orange transition-colors"></span>
                                Recherche Personnalisée
                            </a></li>
                            <li><a href="#" class="group flex items-center gap-3 text-slate-400 hover:text-white transition-all py-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-orange/50 group-hover:bg-brand-orange transition-colors"></span>
                                Financement sur-mesure
                            </a></li>
                            <li><Link href="/plaques" class="group flex items-center gap-3 text-slate-400 hover:text-white transition-all py-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-orange/50 group-hover:bg-brand-orange transition-colors"></span>
                                Plaques d'immatriculation
                            </Link></li>
                        </ul>
                    </div>
                    
                    <!-- Horaires Column -->
                    <div>
                        <h4 class="font-bold text-white mb-6 text-lg flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-brand-blue/20 flex items-center justify-center">
                                <i class="ph-bold ph-clock text-brand-blue text-sm"></i>
                            </div>
                            Horaires
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 rounded-xl bg-white/5 border border-white/5">
                                <span class="text-slate-400">Lundi - Vendredi</span> 
                                <span class="font-bold text-brand-orange">{{ $page.props.settings.opening_hours_week }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded-xl bg-white/5 border border-white/5">
                                <span class="text-slate-400">Samedi</span> 
                                <span class="font-bold text-brand-orange">{{ $page.props.settings.opening_hours_weekend }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded-xl bg-white/5 border border-white/5">
                                <span class="text-slate-400">Dimanche</span> 
                                <span class="text-brand-red font-bold">Fermé</span>
                            </div>
                        </div>
                        <div class="mt-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20">
                            <div class="flex items-center gap-2 text-green-400 font-semibold text-sm">
                                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                                Ouvert maintenant
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Column -->
                    <div>
                        <h4 class="font-bold text-white mb-6 text-lg flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-brand-red/20 flex items-center justify-center">
                                <i class="ph-bold ph-chat-circle text-brand-red text-sm"></i>
                            </div>
                            Contact
                        </h4>
                        <ul class="space-y-4">
                            <li>
                                <a href="https://maps.google.com" target="_blank" class="flex items-start gap-3 text-slate-400 hover:text-white transition-all group">
                                    <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0 group-hover:bg-brand-red group-hover:text-white transition-all">
                                        <i class="ph-fill ph-map-pin text-xl"></i>
                                    </div>
                                    <span class="mt-2">{{ $page.props.settings.contact_address }}</span>
                                </a>
                            </li>
                            <li>
                                <a :href="'tel:' + $page.props.settings.contact_phone" class="flex items-center gap-3 text-slate-400 hover:text-white transition-all group">
                                    <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0 group-hover:bg-brand-red group-hover:text-white transition-all">
                                        <i class="ph-fill ph-phone text-xl"></i>
                                    </div>
                                    <span>{{ $page.props.settings.contact_phone }}</span>
                                </a>
                            </li>
                            <li>
                                <a :href="'mailto:' + $page.props.settings.contact_email" class="flex items-center gap-3 text-slate-400 hover:text-white transition-all group">
                                    <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center flex-shrink-0 group-hover:bg-brand-red group-hover:text-white transition-all">
                                        <i class="ph-fill ph-envelope text-xl"></i>
                                    </div>
                                    <span>{{ $page.props.settings.contact_email }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Copyright -->
                <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-slate-500 text-sm">
                        &copy; {{ currentYear }} Central Auto B. {{ __('all_rights_reserved') }}.
                    </p>
                    <div class="flex gap-6 text-sm text-slate-500">
                        <a href="#" class="hover:text-white transition-colors">{{ __('terms') }}</a>
                        <a href="#" class="hover:text-white transition-colors">{{ __('privacy') }}</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
