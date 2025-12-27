<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import CarModal from '@/Components/CarModal.vue';

const props = defineProps({
    vehicles: Object
});

const selectedCar = ref(null);
const isModalOpen = ref(false);

const openModal = (car) => {
    // Format data for modal
    const carData = { ...car };
    if (car.type === 'sale') {
        carData.price = formatPrice(car.price);
        carData.mileage = formatMileage(car.mileage);
    } else {
        carData.rentPrice = formatPrice(car.price) + ' / mois';
        carData.seats = '5 places';
    }
    // Use main_image if available (already includes /storage/ prefix from backend)
    if (car.main_image) {
        carData.image = car.main_image;
        carData.images = [car.main_image];
    } else if (!carData.images || !carData.images.length) {
        carData.images = [carData.image || '/images/unnamed.png'];
    }
    
    selectedCar.value = carData;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedCar.value = null;
};

const handleContact = (car) => {
    closeModal();
    // In a real app, maybe open contact modal or scroll to contact form
    window.location.href = '#contact';
};

// Get URL params for initial filter state
const page = usePage();
const urlParams = new URLSearchParams(window.location.search);
const initialType = urlParams.get('type') || 'all';

// Filters State
const filters = ref({
    search: '',
    type: initialType,
    make: 'all',
    model: 'all',
    fuel: [],
    transmission: [],
    sort: 'newest',
    priceMin: null,
    priceMax: null,
    yearMin: null,
    yearMax: null,
    mileageMax: null
});

// Unique Makes for Dropdown
const makes = computed(() => {
    if (!props.vehicles?.data) return [];
    const allMakes = props.vehicles.data.map(v => v.make);
    return [...new Set(allMakes)].sort();
});

// Unique Models for Dropdown (dependent on selected Make)
const models = computed(() => {
    if (!props.vehicles?.data) return [];
    let filtered = props.vehicles.data;
    if (filters.value.make !== 'all') {
        filtered = filtered.filter(v => v.make === filters.value.make);
    }
    const allModels = filtered.map(v => v.model);
    return [...new Set(allModels)].sort();
});

// Filtered & Sorted Vehicles
const filteredVehicles = computed(() => {
    if (!props.vehicles?.data) return [];
    let result = props.vehicles.data;

    // Filter by Type
    if (filters.value.type !== 'all') {
        result = result.filter(v => v.type === filters.value.type);
    }

    // Filter by Make
    if (filters.value.make !== 'all') {
        result = result.filter(v => v.make === filters.value.make);
    }

    // Filter by Model
    if (filters.value.model !== 'all') {
        result = result.filter(v => v.model === filters.value.model);
    }

    // Filter by Price
    if (filters.value.priceMin) {
        result = result.filter(v => {
            const price = v.type === 'sale' ? v.price : v.rent_price;
            return price >= filters.value.priceMin;
        });
    }
    if (filters.value.priceMax) {
        result = result.filter(v => {
            const price = v.type === 'sale' ? v.price : v.rent_price;
            return price <= filters.value.priceMax;
        });
    }

    // Filter by Year
    if (filters.value.yearMin) {
        result = result.filter(v => v.year >= filters.value.yearMin);
    }
    if (filters.value.yearMax) {
        result = result.filter(v => v.year <= filters.value.yearMax);
    }

    // Filter by Mileage
    if (filters.value.mileageMax) {
        result = result.filter(v => v.mileage <= filters.value.mileageMax);
    }

    // Filter by Search
    if (filters.value.search) {
        const query = filters.value.search.toLowerCase();
        result = result.filter(v => 
            v.make.toLowerCase().includes(query) || 
            v.model.toLowerCase().includes(query)
        );
    }

    // Filter by Fuel
    if (filters.value.fuel.length > 0) {
        result = result.filter(v => filters.value.fuel.includes(v.fuel));
    }

    // Filter by Transmission
    if (filters.value.transmission.length > 0) {
        result = result.filter(v => filters.value.transmission.includes(v.transmission));
    }

    // Sort
    return result.sort((a, b) => {
        if (filters.value.sort === 'priceAsc') return a.price - b.price;
        if (filters.value.sort === 'priceDesc') return b.price - a.price;
        return b.year - a.year; // Default newest
    });
});

const resetFilters = () => {
    filters.value = {
        search: '',
        type: 'all',
        make: 'all',
        model: 'all',
        fuel: [],
        transmission: [],
        sort: 'newest',
        priceMin: null,
        priceMax: null,
        yearMin: null,
        yearMax: null,
        mileageMax: null
    };
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(price);
};

const formatMileage = (km) => {
    return new Intl.NumberFormat('fr-FR').format(km) + ' km';
};
</script>

<template>
    <Head title="Catalogue" />

    <MainLayout>
        <!-- Hero Section -->
        <header class="relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden bg-brand-dark">
            <!-- Background Elements -->
            <div class="absolute inset-0 bg-gradient-to-br from-brand-dark via-slate-900 to-brand-blue/20 z-0"></div>
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-brand-blue/10 rounded-full blur-[120px] translate-x-1/3 -translate-y-1/3"></div>
            <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-brand-orange/10 rounded-full blur-[100px] -translate-x-1/3 translate-y-1/3"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-brand-orange font-semibold text-sm mb-6">
                    <i class="ph-fill ph-car-profile"></i>
                    Notre Collection
                </span>
                <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-white mb-6">
                    Trouvez votre <span class="bg-gradient-to-r from-brand-orange via-brand-red to-brand-blue bg-clip-text text-transparent">Perle Rare</span>
                </h1>
                <p class="text-slate-300 text-lg md:text-xl max-w-2xl mx-auto">
                    Une sélection rigoureuse de véhicules premium, contrôlés et garantis pour votre plus grande satisfaction.
                </p>
            </div>
        </header>

        <!-- Main Content -->
        <section class="py-12 md:py-20 relative bg-slate-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row gap-10">
                    
                    <!-- Sidebar Filters -->
                    <aside class="lg:w-1/4 space-y-8">
                        <div class="bg-white/70 backdrop-blur-md border border-white/50 rounded-3xl p-6 sticky top-28 shadow-sm">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                                    <i class="ph-fill ph-faders text-brand-blue"></i> Filtres
                                </h3>
                                <button @click="resetFilters" class="text-sm text-slate-500 hover:text-brand-red font-medium transition-colors flex items-center gap-1">
                                    <i class="ph-bold ph-arrow-counter-clockwise"></i> Reset
                                </button>
                            </div>

                            <!-- Search -->
                            <div class="mb-6">
                                <div class="relative">
                                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-3.5 text-slate-400"></i>
                                    <input type="text" v-model="filters.search" placeholder="Rechercher..." class="w-full bg-white border border-slate-200 rounded-xl pl-10 pr-4 py-3 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 transition-all text-sm">
                                </div>
                            </div>

                            <!-- Type Filter -->
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-slate-700 mb-3">Type de projet</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" v-model="filters.type" value="all" class="peer sr-only">
                                        <div class="text-center py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium peer-checked:bg-slate-900 peer-checked:text-white peer-checked:border-slate-900 transition-all hover:bg-slate-50">
                                            Tout
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" v-model="filters.type" value="sale" class="peer sr-only">
                                        <div class="text-center py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium peer-checked:bg-brand-orange peer-checked:text-white peer-checked:border-brand-orange transition-all hover:bg-slate-50">
                                            Achat
                                        </div>
                                    </label>
                                    <label class="cursor-pointer col-span-2">
                                        <input type="radio" v-model="filters.type" value="rent" class="peer sr-only">
                                        <div class="text-center py-2.5 rounded-xl border border-slate-200 text-slate-600 text-sm font-medium peer-checked:bg-brand-blue peer-checked:text-white peer-checked:border-brand-blue transition-all hover:bg-slate-50">
                                            Location
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Make & Model Filter -->
                            <div class="mb-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Marque</label>
                                    <select v-model="filters.make" @change="filters.model = 'all'" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 transition-all text-sm appearance-none cursor-pointer">
                                        <option value="all">Toutes les marques</option>
                                        <option v-for="make in makes" :key="make" :value="make">{{ make }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Modèle</label>
                                    <select v-model="filters.model" :disabled="filters.make === 'all'" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 transition-all text-sm appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                        <option value="all">Tous les modèles</option>
                                        <option v-for="model in models" :key="model" :value="model">{{ model }}</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-slate-700 mb-3">Prix {{ filters.type === 'rent' ? '(€/jour)' : '(€)' }}</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" v-model="filters.priceMin" placeholder="Min" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 transition-all text-sm">
                                    <input type="number" v-model="filters.priceMax" placeholder="Max" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 transition-all text-sm">
                                </div>
                            </div>

                            <!-- Year Range -->
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-slate-700 mb-3">Année</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" v-model="filters.yearMin" placeholder="De" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 transition-all text-sm">
                                    <input type="number" v-model="filters.yearMax" placeholder="À" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 transition-all text-sm">
                                </div>
                            </div>

                            <!-- Mileage Max -->
                            <div class="mb-6" v-if="filters.type !== 'rent'">
                                <label class="block text-sm font-bold text-slate-700 mb-3">Kilométrage max</label>
                                <input type="number" v-model="filters.mileageMax" placeholder="Ex: 100000" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-2 focus:outline-none focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/10 transition-all text-sm">
                            </div>

                            <!-- Fuel Filter -->
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-slate-700 mb-3">Carburant</label>
                                <div class="space-y-2">
                                    <label v-for="fuel in ['Essence', 'Diesel', 'Hybride', 'Électrique']" :key="fuel" class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" v-model="filters.fuel" :value="fuel" class="w-5 h-5 rounded-md border-slate-300 text-brand-blue focus:ring-brand-blue">
                                        <span class="text-slate-600 group-hover:text-slate-900 transition-colors">{{ fuel }}</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Transmission Filter -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-3">Boîte de vitesse</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <label class="cursor-pointer">
                                        <input type="checkbox" v-model="filters.transmission" value="Auto" class="peer sr-only">
                                        <div class="text-center py-2 rounded-lg border border-slate-200 text-slate-500 text-xs font-bold uppercase peer-checked:bg-slate-800 peer-checked:text-white peer-checked:border-slate-800 transition-all hover:bg-slate-50">
                                            Auto
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="checkbox" v-model="filters.transmission" value="Manuelle" class="peer sr-only">
                                        <div class="text-center py-2 rounded-lg border border-slate-200 text-slate-500 text-xs font-bold uppercase peer-checked:bg-slate-800 peer-checked:text-white peer-checked:border-slate-800 transition-all hover:bg-slate-50">
                                            Manuelle
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </aside>

                    <!-- Results Grid -->
                    <div class="lg:w-3/4">
                        <!-- Results Header -->
                        <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
                            <p class="text-slate-500 font-medium">
                                <span class="text-slate-900 font-bold text-xl">{{ filteredVehicles.length }}</span> véhicules disponibles
                            </p>
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-slate-500">Trier par:</span>
                                <select v-model="filters.sort" class="bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-brand-blue">
                                    <option value="newest">Nouveautés</option>
                                    <option value="priceAsc">Prix croissant</option>
                                    <option value="priceDesc">Prix décroissant</option>
                                </select>
                            </div>
                        </div>

                        <!-- Grid -->
                        <div v-if="filteredVehicles.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            <div v-for="vehicle in filteredVehicles" :key="vehicle.id" class="group bg-white rounded-3xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                                <!-- Image -->
                                <div class="relative h-48 overflow-hidden">
                                    <div class="absolute top-3 left-3 z-10 flex gap-2">
                                        <span v-if="vehicle.status === 'sold'" class="px-3 py-1 rounded-lg bg-red-600 text-white text-xs font-bold shadow-lg">VENDU</span>
                                        <template v-else>
                                            <span v-if="vehicle.type === 'sale'" class="px-3 py-1 rounded-lg bg-brand-orange text-white text-xs font-bold shadow-lg">Vente</span>
                                            <span v-else class="px-3 py-1 rounded-lg bg-brand-blue text-white text-xs font-bold shadow-lg">Location</span>
                                        </template>
                                    </div>
                                    <img 
                                        :src="vehicle.main_image || '/images/unnamed.png'" 
                                        :alt="vehicle.make + ' ' + vehicle.model" 
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                        :class="{ 'grayscale': vehicle.status === 'sold' }"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                </div>

                                <!-- Content -->
                                <div class="p-5">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <h3 class="font-bold text-slate-900 text-lg">{{ vehicle.make }} {{ vehicle.model }}</h3>
                                            <p class="text-slate-500 text-sm">{{ vehicle.year }} • {{ vehicle.fuel }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4 my-4 text-sm text-slate-500">
                                        <div class="flex items-center gap-1.5">
                                            <i class="ph-fill ph-speedometer text-brand-blue"></i>
                                            <span>{{ formatMileage(vehicle.mileage) }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <i class="ph-fill ph-gear text-brand-blue"></i>
                                            <span>{{ vehicle.transmission }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                        <p class="text-xl font-bold" :class="vehicle.status === 'sold' ? 'text-slate-400' : 'text-brand-blue'">
                                            {{ formatPrice(vehicle.price) }}
                                            <span v-if="vehicle.type === 'rent'" class="text-sm font-normal text-slate-500">/mois</span>
                                        </p>
                                        <button @click="openModal(vehicle)" class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 hover:bg-brand-blue hover:text-white transition-all">
                                            <i class="ph-bold ph-arrow-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-100">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                <i class="ph-fill ph-car-profile text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-slate-900 mb-2">Aucun véhicule trouvé</h3>
                            <p class="text-slate-500 mb-6">Essayez de modifier vos filtres de recherche.</p>
                            <button @click="resetFilters" class="text-brand-blue font-semibold hover:underline">
                                Réinitialiser les filtres
                            </button>
                        </div>

                        <!-- Pagination -->
                        <div v-if="vehicles.links && vehicles.links.length > 3" class="flex justify-center gap-2 mt-8">
                            <button 
                                v-for="link in vehicles.links" 
                                :key="link.label"
                                @click="link.url ? router.visit(link.url) : null"
                                :disabled="!link.url"
                                v-html="link.label"
                                :class="[
                                    'px-4 py-2 rounded-lg font-semibold text-sm transition-all',
                                    link.active 
                                        ? 'bg-brand-gradient text-white shadow-lg' 
                                        : link.url 
                                            ? 'bg-white text-slate-700 border border-slate-200 hover:border-brand-blue hover:text-brand-blue' 
                                            : 'bg-slate-50 text-slate-400 cursor-not-allowed'
                                ]"
                            ></button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <CarModal 
            :isOpen="isModalOpen"
            :car="selectedCar"
            @close="closeModal"
            @contact="handleContact"
        />
    </MainLayout>
</template>
