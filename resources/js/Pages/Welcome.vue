<script setup>
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import FeaturesSection from '@/Components/FeaturesSection.vue';
import StockSection from '@/Components/StockSection.vue';
import TestimonialsSection from '@/Components/TestimonialsSection.vue';
import CustomRequestSection from '@/Components/CustomRequestSection.vue';
import Simulator from '@/Components/Simulator.vue';
import PlatesSection from '@/Components/PlatesSection.vue';
import ContactSection from '@/Components/ContactSection.vue';
import { useTranslations } from '@/Composables/useTranslations';

const { __ } = useTranslations();

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    featuredVehicle: Object,
    vehicles: Array,
});
</script>

<template>
    <Head :title="__('home')" />

    <MainLayout>
        <!-- Hero Section -->
        <header class="relative min-h-screen flex items-center overflow-hidden bg-brand-dark">
            <!-- Video Background Effect with Multiple Layers -->
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" alt="Luxury Car" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-brand-dark via-brand-dark/95 to-brand-dark/70"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-brand-dark via-transparent to-brand-dark/50"></div>
            </div>
            
            <!-- Animated Particles/Lines Effect -->
            <div class="absolute inset-0 z-1 opacity-30">
                <div class="absolute top-20 left-10 w-px h-40 bg-gradient-to-b from-transparent via-brand-orange to-transparent animate-pulse"></div>
                <div class="absolute top-40 left-1/4 w-px h-60 bg-gradient-to-b from-transparent via-brand-red to-transparent animate-pulse" style="animation-delay: 0.5s;"></div>
                <div class="absolute top-10 right-1/3 w-px h-80 bg-gradient-to-b from-transparent via-brand-blue to-transparent animate-pulse" style="animation-delay: 1s;"></div>
                <div class="absolute bottom-20 right-20 w-40 h-px bg-gradient-to-r from-transparent via-brand-orange to-transparent animate-pulse"></div>
            </div>

            <!-- Floating Brand Colors -->
            <div class="absolute top-1/4 right-0 w-[600px] h-[600px] bg-brand-blue/20 rounded-full blur-[150px] float-animation"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-brand-orange/15 rounded-full blur-[120px] float-animation" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/3 w-[300px] h-[300px] bg-brand-red/10 rounded-full blur-[100px] float-animation" style="animation-delay: 1s;"></div>
            
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-20">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Left Content -->
                    <div class="text-left">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-8">
                            <span class="w-2 h-2 bg-brand-orange rounded-full animate-pulse"></span>
                            <span class="text-white/90 text-sm font-medium tracking-wide">{{ __('trusted_partner') }}</span>
                        </div>
                        
                        <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-white mb-6 leading-[1.1]">
                            {{ __('find_dream_vehicle') }}
                            <span class="block mt-2">
                                <span class="text-gradient">{{ __('of_your_dreams') }}</span>
                            </span>
                        </h1>
                        
                        <p class="text-xl text-slate-300 mb-10 max-w-lg leading-relaxed">
                            {{ __('hero_description') }}
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4 mb-12">
                            <button class="group bg-brand-gradient hover:opacity-90 text-white px-8 py-5 rounded-2xl font-bold text-lg transition-all shadow-2xl shadow-brand-red/30 flex items-center justify-center gap-3 hover:shadow-brand-red/50 btn-glow relative overflow-hidden hover:-translate-y-1">
                                <i class="ph-bold ph-car-profile text-xl relative z-10"></i> 
                                <span class="relative z-10">{{ __('explore_stock') }}</span>
                                <i class="ph-bold ph-arrow-right relative z-10 group-hover:translate-x-1 transition-transform"></i>
                            </button>
                            <button class="group bg-white/10 backdrop-blur-sm hover:bg-white/20 border border-white/30 text-white px-8 py-5 rounded-2xl font-bold text-lg transition-all flex items-center justify-center gap-3 hover:-translate-y-1">
                                <i class="ph-bold ph-magnifying-glass text-xl"></i> 
                                <span>{{ __('custom_search') }}</span>
                            </button>
                        </div>

                        <!-- Trust Badges -->
                        <div class="flex flex-wrap items-center gap-6">
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center">
                                    <i class="ph-fill ph-shield-check text-green-400 text-xl"></i>
                                </div>
                                <span class="text-white/80 text-sm font-medium">{{ __('warranty_included') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-xl bg-brand-orange/20 flex items-center justify-center">
                                    <i class="ph-fill ph-handshake text-brand-orange text-xl"></i>
                                </div>
                                <span class="text-white/80 text-sm font-medium">{{ __('trade_in_estimate') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-10 h-10 rounded-xl bg-brand-blue/20 flex items-center justify-center">
                                    <i class="ph-fill ph-credit-card text-brand-blue text-xl"></i>
                                </div>
                                <span class="text-white/80 text-sm font-medium">{{ __('easy_financing') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content - Featured Car Card -->
                    <div class="relative mt-12 lg:mt-0">
                        <div v-if="featuredVehicle" class="relative">
                            <!-- Main Car Image -->
                            <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-black/50 border border-white/10">
                                <img :src="featuredVehicle.main_image || 'https://images.unsplash.com/photo-1555215695-3004980ad54e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'" 
                                     :alt="featuredVehicle.make + ' ' + featuredVehicle.model" 
                                     class="w-full h-[350px] md:h-[450px] object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                                
                                <!-- Vehicle Info Overlay -->
                                <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8">
                                    <div class="flex items-end justify-between">
                                        <div>
                                            <div v-if="featuredVehicle.badge_type" class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-orange text-white text-xs font-bold mb-3">
                                                <i class="ph-fill ph-star"></i>
                                                {{ featuredVehicle.badge_type }}
                                            </div>
                                            <h3 class="text-xl md:text-2xl font-bold text-white">{{ featuredVehicle.make }} {{ featuredVehicle.model }}</h3>
                                            <p class="text-slate-300 text-sm md:text-base">{{ featuredVehicle.fuel_type }} • {{ featuredVehicle.year }}</p>
                                        </div>
                                        <div class="text-right">
                                            <div v-if="featuredVehicle.type === 'sale'" class="text-2xl md:text-3xl font-extrabold text-white">{{ featuredVehicle.price?.toLocaleString() }} €</div>
                                            <div v-else class="text-2xl md:text-3xl font-extrabold text-white">{{ featuredVehicle.rent_price }} €<span class="text-sm">{{ __('per_day') }}</span></div>
                                            <p v-if="featuredVehicle.mileage" class="text-slate-400 text-sm">{{ featuredVehicle.mileage?.toLocaleString() }} km</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Floating Stats Cards -->
                            <div class="absolute -left-2 md:-left-8 top-1/4 bg-white/95 backdrop-blur-sm rounded-2xl p-3 md:p-4 shadow-xl border border-slate-100 float-animation scale-90 md:scale-100 origin-left">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-blue/10 rounded-xl flex items-center justify-center">
                                        <i class="ph-fill ph-car text-brand-blue text-xl md:text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-xl md:text-2xl font-bold text-brand-dark">50+</p>
                                        <p class="text-slate-500 text-xs font-medium">{{ __('in_stock') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="absolute -right-2 md:-right-4 bottom-1/3 bg-white/95 backdrop-blur-sm rounded-2xl p-3 md:p-4 shadow-xl border border-slate-100 float-animation scale-90 md:scale-100 origin-right" style="animation-delay: 1s;">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-green-100 rounded-xl flex items-center justify-center">
                                        <i class="ph-fill ph-star text-green-600 text-xl md:text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-xl md:text-2xl font-bold text-brand-dark">4.9/5</p>
                                        <p class="text-slate-500 text-xs font-medium">{{ __('customer_reviews') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="relative">
                            <!-- Fallback if no featured vehicle -->
                            <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-black/50 border border-white/10">
                                <img src="https://images.unsplash.com/photo-1555215695-3004980ad54e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                                     :alt="__('premium_vehicle')" 
                                     class="w-full h-[350px] md:h-[450px] object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Scroll Indicator -->
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 animate-bounce">
                <div class="w-8 h-14 rounded-full border-2 border-white/30 flex items-start justify-center p-2">
                    <div class="w-1.5 h-3 bg-white rounded-full animate-pulse"></div>
                </div>
            </div>
        </header>

        <FeaturesSection />
        <StockSection :vehicles="vehicles" />
        <TestimonialsSection />
        <CustomRequestSection />
        <Simulator />
        <PlatesSection />
        <ContactSection />
    </MainLayout>
</template>
