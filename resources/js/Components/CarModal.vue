<script setup>
import { ref, computed, watch } from 'vue';
import VehicleInterestModal from './VehicleInterestModal.vue';

const props = defineProps({
    car: Object,
    isOpen: Boolean
});

const emit = defineEmits(['close', 'contact']);

const showInterestModal = ref(false);
const showSuccessNotification = ref(false);

const currentImageIndex = ref(0);
const rentalDuration = ref(1);

// Reset state when car changes
watch(() => props.car, () => {
    currentImageIndex.value = 0;
    rentalDuration.value = 1;
});

const allImages = computed(() => {
    if (!props.car) return [];
    const images = [];
    if (props.car.main_image) images.push(props.car.main_image);
    if (props.car.images && Array.isArray(props.car.images)) {
        images.push(...props.car.images);
    }
    return images;
});

const currentImage = computed(() => {
    return allImages.value[currentImageIndex.value] || '';
});

const rentalTotal = computed(() => {
    if (!props.car || props.car.type !== 'rent') return 0;
    return props.car.rent_price * rentalDuration.value;
});

const nextImage = () => {
    if (allImages.value.length === 0) return;
    currentImageIndex.value = (currentImageIndex.value + 1) % allImages.value.length;
};

const prevImage = () => {
    if (allImages.value.length === 0) return;
    currentImageIndex.value = (currentImageIndex.value - 1 + allImages.value.length) % allImages.value.length;
};

const adjustDuration = (delta) => {
    const newValue = rentalDuration.value + delta;
    if (newValue >= 1) {
        rentalDuration.value = newValue;
    }
};

const close = () => {
    emit('close');
};

const openInterestModal = () => {
    showInterestModal.value = true;
};

const handleInterestSuccess = () => {
    showSuccessNotification.value = true;
    setTimeout(() => {
        showSuccessNotification.value = false;
    }, 5000);
};
</script>

<template>
    <div v-if="isOpen && car" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="close">
        <div class="bg-white rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto transform transition-all duration-300">
            <!-- Header Modal avec Carrousel -->
            <div class="relative">
                <!-- Image principale -->
                <img :src="currentImage" :alt="car.make + ' ' + car.model" class="w-full h-80 object-cover rounded-t-3xl transition-all duration-300">
                
                <!-- Boutons navigation carrousel -->
                <button v-if="allImages.length > 1" @click="prevImage" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm w-12 h-12 rounded-full flex items-center justify-center hover:bg-white transition-all shadow-lg">
                    <i class="ph-bold ph-caret-left text-2xl text-slate-700"></i>
                </button>
                <button v-if="allImages.length > 1" @click="nextImage" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 backdrop-blur-sm w-12 h-12 rounded-full flex items-center justify-center hover:bg-white transition-all shadow-lg">
                    <i class="ph-bold ph-caret-right text-2xl text-slate-700"></i>
                </button>
                
                <!-- Indicateurs -->
                <div v-if="allImages.length > 1" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
                    <div 
                        v-for="(img, index) in allImages" 
                        :key="index"
                        class="h-2 rounded-full transition-all duration-300"
                        :class="index === currentImageIndex ? 'bg-white w-6' : 'bg-white/50 w-2'"
                    ></div>
                </div>
                
                <button @click="close" class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm w-12 h-12 rounded-full flex items-center justify-center hover:bg-white transition-all shadow-lg z-10">
                    <i class="ph-bold ph-x text-2xl text-slate-700"></i>
                </button>
                
                <div v-if="car.status === 'sold'" class="absolute top-4 left-4 px-4 py-2 rounded-full bg-red-600 text-white text-sm font-bold uppercase tracking-wide shadow-lg backdrop-blur-sm animate-pulse z-10">
                    VENDU
                </div>
                <div v-else-if="car.badge_type" class="absolute top-4 left-4 px-4 py-2 rounded-full text-white text-sm font-bold uppercase tracking-wide shadow-lg backdrop-blur-sm"
                    :class="car.type === 'sale' ? 'bg-brand-orange' : 'bg-brand-red'">
                    {{ car.badge_type }}
                </div>
            </div>

            <!-- Body Modal -->
            <div class="p-8">
                <!-- Titre et Prix -->
                <div class="flex justify-between items-start mb-6 pb-6 border-b-2 border-slate-100">
                    <div>
                        <h2 class="text-3xl font-extrabold text-brand-dark mb-2">{{ car.make }}</h2>
                        <p class="text-xl text-slate-600 font-medium">{{ car.model }}</p>
                        <p class="text-slate-500 mt-1">{{ car.year }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-4xl font-extrabold" :class="car.status === 'sold' ? 'text-slate-400 line-through' : (car.type === 'sale' ? 'text-brand-blue' : 'text-brand-red')">
                            {{ car.type === 'sale' ? (car.price?.toLocaleString() + ' €') : (car.rent_price + ' €/j') }}
                        </p>
                    </div>
                </div>

                <!-- Caractéristiques -->
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-brand-dark mb-4 flex items-center gap-2">
                        <i class="ph-fill ph-list-checks text-brand-blue"></i>
                        Caractéristiques
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl">
                            <div class="w-12 h-12 bg-brand-orange/20 rounded-xl flex items-center justify-center">
                                <i class="ph-fill ph-gas-pump text-brand-orange text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase">Carburant</p>
                                <p class="text-sm font-bold text-slate-800">{{ car.fuel_type || 'Diesel' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl">
                            <div class="w-12 h-12 bg-brand-blue/20 rounded-xl flex items-center justify-center">
                                <i class="ph-fill ph-gear text-brand-blue text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase">Transmission</p>
                                <p class="text-sm font-bold text-slate-800">{{ car.transmission || 'Automatique' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl">
                            <div class="w-12 h-12 bg-brand-red/20 rounded-xl flex items-center justify-center">
                                <i :class="car.type === 'sale' ? 'ph-fill ph-gauge' : 'ph-fill ph-users'" class="text-brand-red text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase">{{ car.type === 'sale' ? 'Kilométrage' : 'Places' }}</p>
                                <p class="text-sm font-bold text-slate-800">{{ car.type === 'sale' ? (car.mileage?.toLocaleString() + ' km') : (car.seats || '5') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Rental Simulator (Visible seulement pour location) -->
                    <div v-if="car.type === 'rent'" class="mt-6 p-6 bg-slate-50 rounded-2xl border border-slate-100">
                        <h4 class="text-lg font-bold text-brand-dark mb-4 flex items-center gap-2">
                            <i class="ph-fill ph-calculator text-brand-blue"></i>
                            Simulateur de Location
                        </h4>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-600 mb-2">Durée (jours)</label>
                                <div class="flex items-center gap-4">
                                    <button @click="adjustDuration(-1)" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center hover:bg-brand-blue hover:text-white hover:border-brand-blue transition-all">
                                        <i class="ph-bold ph-minus"></i>
                                    </button>
                                    <input type="number" v-model="rentalDuration" min="1" class="w-20 text-center font-bold text-lg bg-transparent border-none focus:ring-0 p-0">
                                    <button @click="adjustDuration(1)" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center hover:bg-brand-blue hover:text-white hover:border-brand-blue transition-all">
                                        <i class="ph-bold ph-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="pt-4 border-t border-slate-200 flex justify-between items-end">
                                <span class="text-sm font-medium text-slate-500">Total estimé</span>
                                <span class="text-3xl font-extrabold text-brand-blue">{{ rentalTotal }} €</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <button @click="openInterestModal" 
                        :disabled="car.status === 'sold'"
                        class="flex-1 font-bold py-4 rounded-xl transition-all shadow-lg flex items-center justify-center gap-3 transform duration-300"
                        :class="car.status === 'sold' ? 'bg-slate-200 text-slate-400 cursor-not-allowed' : 'bg-brand-gradient text-white hover:opacity-90 hover:-translate-y-1'">
                        <i class="ph-bold" :class="car.status === 'sold' ? 'ph-lock' : 'ph-phone text-xl'"></i>
                        <span>{{ car.status === 'sold' ? 'Véhicule Vendu' : 'Contacter pour ce véhicule' }}</span>
                    </button>
                    <button @click="close" class="px-8 py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-all">
                        Fermer
                    </button>
                </div>
            </div>
        </div>

        <!-- Vehicle Interest Modal -->
        <VehicleInterestModal 
            :isOpen="showInterestModal" 
            :vehicle="car" 
            @close="showInterestModal = false"
            @success="handleInterestSuccess"
        />

        <!-- Success Notification -->
        <Transition name="slide-down">
            <div v-if="showSuccessNotification" class="fixed top-6 right-6 z-[70] max-w-md w-full">
                <div class="rounded-2xl shadow-2xl p-6 flex items-start gap-4 border-2 bg-green-50 border-green-500">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 bg-green-500">
                        <i class="ph-bold ph-check text-white text-2xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-green-900 mb-1 text-lg">Demande envoyée !</h4>
                        <p class="text-sm text-green-700">Votre demande a été envoyée avec succès ! Nous vous contacterons rapidement.</p>
                    </div>
                    <button 
                        @click="showSuccessNotification = false"
                        class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors hover:bg-green-200 text-green-700"
                    >
                        <i class="ph-bold ph-x"></i>
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.slide-down-enter-from {
    opacity: 0;
    transform: translateY(-100px) scale(0.9);
}

.slide-down-leave-to {
    opacity: 0;
    transform: translateX(100px) scale(0.9);
}
</style>
