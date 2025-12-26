<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import CarModal from '@/Components/CarModal.vue';

const props = defineProps({
    vehicles: {
        type: Array,
        default: () => []
    }
});

const selectedCar = ref(null);
const isModalOpen = ref(false);

// Séparer les véhicules par type
const carsForSale = computed(() => {
    return props.vehicles.filter(v => v.type === 'sale');
});

const carsForRent = computed(() => {
    return props.vehicles.filter(v => v.type === 'rent');
});

const openModal = (car) => {
    selectedCar.value = car;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedCar.value = null;
};

const handleContact = (car) => {
    closeModal();
    const element = document.getElementById('custom-request');
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
};

const scrollCarousel = (type, direction) => {
    const container = document.getElementById(`car-carousel-${type}`);
    if (container) {
        const scrollAmount = 350;
        if (direction === 'next') {
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        } else {
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        }
    }
};
</script>

<template>
    <section id="stock" class="py-24 bg-gradient-to-b from-white to-slate-50 relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-brand-blue/5 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-brand-orange/5 rounded-full blur-[100px] translate-y-1/2 -translate-x-1/3"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <CarModal 
                :car="selectedCar" 
                :isOpen="isModalOpen" 
                @close="closeModal"
                @contact="handleContact"
            />

            <!-- Section Véhicules à Vendre -->
            <div class="mb-24">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-12 gap-8">
                    <div>
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-orange/10 text-brand-orange font-semibold text-sm mb-4">
                            <i class="ph-fill ph-tag"></i>
                            Achat
                        </span>
                        <h2 class="text-4xl md:text-5xl font-extrabold text-brand-dark mb-3">
                            Véhicules <span class="text-gradient">à Vendre</span>
                        </h2>
                        <p class="text-slate-500 text-lg max-w-md">Notre sélection de véhicules d'occasion contrôlés et garantis</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <!-- Navigation Carrousel Vente -->
                        <div class="flex gap-2">
                            <button @click="scrollCarousel('sale', 'prev')" class="w-12 h-12 rounded-full bg-white border-2 border-slate-200 hover:border-brand-orange hover:bg-brand-orange hover:text-white text-slate-600 flex items-center justify-center transition-all shadow-lg">
                                <i class="ph-bold ph-caret-left text-lg"></i>
                            </button>
                            <button @click="scrollCarousel('sale', 'next')" class="w-12 h-12 rounded-full bg-white border-2 border-slate-200 hover:border-brand-orange hover:bg-brand-orange hover:text-white text-slate-600 flex items-center justify-center transition-all shadow-lg">
                                <i class="ph-bold ph-caret-right text-lg"></i>
                            </button>
                        </div>
                        <Link href="/catalogue?type=sale" class="group inline-flex items-center gap-2 text-brand-blue hover:text-brand-dark font-semibold transition-all">
                            <span>Voir tout</span>
                            <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </Link>
                    </div>
                </div>

                <!-- Car Carousel - Vente -->
                <div class="relative">
                    <div v-if="carsForSale.length > 0" id="car-carousel-sale" class="flex gap-6 overflow-x-auto scroll-smooth pb-4 hide-scrollbar" style="scroll-snap-type: x mandatory;">
                        <div v-for="car in carsForSale" :key="car.id" class="carousel-card bg-white rounded-3xl shadow-lg border-2 border-slate-100 overflow-hidden car-card transition-all duration-500 group flex flex-col relative">
                            <div class="relative h-72 overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <img :src="car.main_image || 'https://images.unsplash.com/photo-1555215695-3004980ad54e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'" :alt="car.make + ' ' + car.model" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                <span v-if="car.badge_type" class="absolute top-5 left-5 bg-brand-orange text-white text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wide shadow-lg z-20 backdrop-blur-sm">
                                    {{ car.badge_type }}
                                </span>
                                <div class="absolute bottom-5 right-5 bg-white/90 backdrop-blur-sm rounded-xl px-4 py-2 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <i class="ph-bold ph-heart text-brand-red text-xl"></i>
                                </div>
                            </div>
                            <div class="p-7 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-2xl font-extrabold text-brand-dark group-hover:text-brand-blue transition-colors">{{ car.make }}</h3>
                                        <p class="text-slate-500 font-medium text-base">{{ car.model }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-extrabold text-brand-blue">{{ car.price?.toLocaleString() }} €</p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-3 mt-auto pt-5 border-t-2 border-slate-100 text-sm text-slate-600">
                                    <div class="flex flex-col items-center gap-2 p-2 rounded-lg hover:bg-slate-50 transition-colors">
                                        <i class="ph-fill ph-gas-pump text-brand-orange text-xl"></i>
                                        <span class="font-semibold text-xs">{{ car.fuel_type || 'Diesel' }}</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-2 p-2 rounded-lg hover:bg-slate-50 transition-colors">
                                        <i class="ph-fill ph-gear text-brand-blue text-xl"></i>
                                        <span class="font-semibold text-xs">{{ car.transmission || 'Auto' }}</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-2 p-2 rounded-lg hover:bg-slate-50 transition-colors">
                                        <i class="ph-fill ph-gauge text-brand-red text-xl"></i>
                                        <span class="font-semibold text-xs">{{ car.mileage ? (car.mileage / 1000).toFixed(0) + 'k km' : car.year }}</span>
                                    </div>
                                </div>

                                <button @click="openModal(car)" class="w-full mt-6 bg-gradient-to-r from-slate-900 to-slate-800 hover:from-brand-blue hover:to-brand-blue text-white font-bold py-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl hover:-translate-y-1 transform relative overflow-hidden group/btn">
                                    <span class="relative z-10">Je suis intéressé</span>
                                    <i class="ph-bold ph-arrow-right relative z-10 group-hover/btn:translate-x-2 transition-transform"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-12">
                        <i class="ph-duotone ph-car text-6xl text-slate-300 mb-4"></i>
                        <p class="text-slate-400">Aucun véhicule à vendre pour le moment</p>
                    </div>
                    <!-- Gradient overlays -->
                    <div v-if="carsForSale.length > 0" class="absolute left-0 top-0 bottom-4 w-16 bg-gradient-to-r from-slate-50 to-transparent pointer-events-none z-10 hidden lg:block"></div>
                    <div v-if="carsForSale.length > 0" class="absolute right-0 top-0 bottom-4 w-16 bg-gradient-to-l from-slate-50 to-transparent pointer-events-none z-10 hidden lg:block"></div>
                </div>
            </div>

            <!-- Section Véhicules en Location -->
            <div class="mb-16">
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-12 gap-8">
                    <div>
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-blue/10 text-brand-blue font-semibold text-sm mb-4">
                            <i class="ph-fill ph-calendar"></i>
                            Location
                        </span>
                        <h2 class="text-4xl md:text-5xl font-extrabold text-brand-dark mb-3">
                            Véhicules en <span class="text-gradient">Location</span>
                        </h2>
                        <p class="text-slate-500 text-lg max-w-md">Louez le véhicule idéal pour tous vos déplacements</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <!-- Navigation Carrousel Location -->
                        <div class="flex gap-2">
                            <button @click="scrollCarousel('rent', 'prev')" class="w-12 h-12 rounded-full bg-white border-2 border-slate-200 hover:border-brand-blue hover:bg-brand-blue hover:text-white text-slate-600 flex items-center justify-center transition-all shadow-lg">
                                <i class="ph-bold ph-caret-left text-lg"></i>
                            </button>
                            <button @click="scrollCarousel('rent', 'next')" class="w-12 h-12 rounded-full bg-white border-2 border-slate-200 hover:border-brand-blue hover:bg-brand-blue hover:text-white text-slate-600 flex items-center justify-center transition-all shadow-lg">
                                <i class="ph-bold ph-caret-right text-lg"></i>
                            </button>
                        </div>
                        <Link href="/catalogue?type=rent" class="group inline-flex items-center gap-2 text-brand-blue hover:text-brand-dark font-semibold transition-all">
                            <span>Voir tout</span>
                            <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </Link>
                    </div>
                </div>

                <!-- Car Carousel - Location -->
                <div class="relative">
                    <div v-if="carsForRent.length > 0" id="car-carousel-rent" class="flex gap-6 overflow-x-auto scroll-smooth pb-4 hide-scrollbar" style="scroll-snap-type: x mandatory;">
                        <div v-for="car in carsForRent" :key="car.id" class="carousel-card bg-white rounded-3xl shadow-lg border-2 border-slate-100 overflow-hidden car-card transition-all duration-500 group flex flex-col relative">
                            <div class="relative h-72 overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                                <img :src="car.main_image || 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'" :alt="car.make + ' ' + car.model" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                <span v-if="car.badge_type" class="absolute top-5 left-5 bg-brand-red text-white text-xs font-bold px-4 py-2 rounded-full uppercase tracking-wide shadow-lg z-20 backdrop-blur-sm">
                                    {{ car.badge_type }}
                                </span>
                                <div class="absolute bottom-5 right-5 bg-white/90 backdrop-blur-sm rounded-xl px-4 py-2 z-20 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <i class="ph-bold ph-heart text-brand-red text-xl"></i>
                                </div>
                            </div>
                            <div class="p-7 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-2xl font-extrabold text-brand-dark group-hover:text-brand-blue transition-colors">{{ car.make }}</h3>
                                        <p class="text-slate-500 font-medium text-base">{{ car.model }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-extrabold text-brand-red">{{ car.rent_price }} €<span class="text-sm">/j</span></p>
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-3 mt-auto pt-5 border-t-2 border-slate-100 text-sm text-slate-600">
                                    <div class="flex flex-col items-center gap-2 p-2 rounded-lg hover:bg-slate-50 transition-colors">
                                        <i class="ph-fill ph-gas-pump text-brand-orange text-xl"></i>
                                        <span class="font-semibold text-xs">{{ car.fuel_type || 'Diesel' }}</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-2 p-2 rounded-lg hover:bg-slate-50 transition-colors">
                                        <i class="ph-fill ph-gear text-brand-blue text-xl"></i>
                                        <span class="font-semibold text-xs">{{ car.transmission || 'Auto' }}</span>
                                    </div>
                                    <div class="flex flex-col items-center gap-2 p-2 rounded-lg hover:bg-slate-50 transition-colors">
                                        <i class="ph-fill ph-users text-brand-red text-xl"></i>
                                        <span class="font-semibold text-xs">{{ car.seats || '5' }} places</span>
                                    </div>
                                </div>

                                <button @click="openModal(car)" class="w-full mt-6 bg-gradient-to-r from-slate-900 to-slate-800 hover:from-brand-blue hover:to-brand-blue text-white font-bold py-4 rounded-xl transition-all duration-300 flex items-center justify-center gap-3 shadow-lg hover:shadow-xl hover:-translate-y-1 transform relative overflow-hidden group/btn">
                                    <span class="relative z-10">Je suis intéressé</span>
                                    <i class="ph-bold ph-arrow-right relative z-10 group-hover/btn:translate-x-2 transition-transform"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-else class="text-center py-12">
                        <i class="ph-duotone ph-key text-6xl text-slate-300 mb-4"></i>
                        <p class="text-slate-400">Aucun véhicule en location pour le moment</p>
                    </div>
                    <!-- Gradient overlays -->
                    <div v-if="carsForRent.length > 0" class="absolute left-0 top-0 bottom-4 w-16 bg-gradient-to-r from-slate-50 to-transparent pointer-events-none z-10 hidden lg:block"></div>
                    <div v-if="carsForRent.length > 0" class="absolute right-0 top-0 bottom-4 w-16 bg-gradient-to-l from-slate-50 to-transparent pointer-events-none z-10 hidden lg:block"></div>
                </div>
            </div>
            
            <!-- Bouton Voir Plus -->
            <div class="mt-16 text-center">
                <Link href="/catalogue" class="group inline-flex items-center gap-3 bg-brand-dark hover:bg-brand-blue text-white font-bold px-10 py-5 rounded-2xl transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1 transform duration-300">
                    <i class="ph-bold ph-grid-four text-xl"></i>
                    <span>Voir le catalogue complet</span>
                    <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </Link>
            </div>

            <!-- CTA Recherche personnalisée -->
            <div id="custom-request" class="mt-20 relative">
                <div class="bg-gradient-to-r from-brand-dark via-slate-900 to-brand-dark rounded-3xl p-8 md:p-12 overflow-hidden relative">
                    <!-- Background pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-brand-orange rounded-full blur-[80px]"></div>
                        <div class="absolute bottom-0 left-0 w-64 h-64 bg-brand-blue rounded-full blur-[80px]"></div>
                    </div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="text-center md:text-left">
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-4">
                                <i class="ph-fill ph-magnifying-glass text-brand-orange"></i>
                                <span class="text-white/90 text-sm font-medium">Service Chasseur Auto</span>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-bold text-white mb-3">Vous ne trouvez pas votre bonheur ?</h3>
                            <p class="text-slate-300 max-w-lg">Notre équipe d'experts recherche le véhicule parfait pour vous. Décrivez-nous vos critères !</p>
                        </div>
                        <button class="group flex-shrink-0 bg-brand-gradient text-white font-bold px-8 py-5 rounded-2xl hover:opacity-90 transition-all shadow-2xl shadow-brand-red/30 flex items-center gap-3 hover:shadow-brand-red/50 hover:-translate-y-1">
                            <span>Lancer ma recherche</span>
                            <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
