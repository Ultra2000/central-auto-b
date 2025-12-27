<script setup>
import { ref, watch, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    isOpen: Boolean,
    vehicle: Object
});

const emit = defineEmits(['close', 'success']);

const availabilities = ref([]);
const loadingAvailabilities = ref(false);
const selectedAvailability = ref(null);
const deliveryCost = ref(0);
const loadingDeliveryCost = ref(false);
const deliveryError = ref('');

const form = useForm({
    vehicle_id: null,
    interest_type: 'callback',
    availability_id: null,
    name: '',
    email: '',
    phone: '',
    preferred_date: '',
    preferred_time: '',
    message: '',
    delivery_option: 'pickup',
    delivery_address: '',
    delivery_city: '',
    delivery_postal_code: '',
    delivery_cost: 0
});

const isRentalVehicle = computed(() => {
    return props.vehicle?.type === 'rent';
});

const totalCost = computed(() => {
    if (!isRentalVehicle.value) return 0;
    const rentPrice = props.vehicle?.rent_price || 0;
    return parseFloat(rentPrice) + parseFloat(deliveryCost.value);
});

const loadAvailabilities = async () => {
    loadingAvailabilities.value = true;
    try {
        const response = await axios.get(route('visit-availabilities.get'));
        availabilities.value = response.data;
    } catch (error) {
        console.error('Erreur lors du chargement des disponibilités:', error);
    } finally {
        loadingAvailabilities.value = false;
    }
};

const checkDeliveryCost = async () => {
    if (!form.delivery_postal_code || form.delivery_postal_code.length < 4) {
        deliveryCost.value = 0;
        deliveryError.value = '';
        return;
    }

    loadingDeliveryCost.value = true;
    deliveryError.value = '';
    
    try {
        const response = await axios.get(route('delivery-cost.get', form.delivery_postal_code));
        deliveryCost.value = response.data.cost;
        form.delivery_cost = response.data.cost;
        deliveryError.value = '';
    } catch (error) {
        deliveryCost.value = 0;
        form.delivery_cost = 0;
        deliveryError.value = error.response?.data?.message || 'Zone de livraison non disponible';
    } finally {
        loadingDeliveryCost.value = false;
    }
};

const selectAvailability = (availability) => {
    selectedAvailability.value = availability;
    form.availability_id = availability.id;
    form.preferred_date = availability.date;
    form.preferred_time = availability.start_time;
};

const submit = () => {
    form.vehicle_id = props.vehicle?.id;
    form.post(route('vehicle-interests.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            selectedAvailability.value = null;
            deliveryCost.value = 0;
            deliveryError.value = '';
            emit('success');
            emit('close');
        },
    });
};

const close = () => {
    form.reset();
    selectedAvailability.value = null;
    deliveryCost.value = 0;
    deliveryError.value = '';
    emit('close');
};

// Charger les disponibilités quand le modal s'ouvre
watch(() => props.isOpen, (isOpen) => {
    if (isOpen && form.interest_type === 'reservation') {
        loadAvailabilities();
    }
});

// Charger les disponibilités quand le type change vers réservation
watch(() => form.interest_type, (type) => {
    if (type === 'reservation' && props.isOpen) {
        loadAvailabilities();
    }
});

// Vérifier le coût de livraison quand le code postal change
watch(() => form.delivery_postal_code, () => {
    if (form.delivery_option === 'delivery') {
        checkDeliveryCost();
    }
});

// Réinitialiser les frais de livraison quand on change d'option
watch(() => form.delivery_option, (option) => {
    if (option === 'pickup') {
        deliveryCost.value = 0;
        form.delivery_cost = 0;
        deliveryError.value = '';
    } else if (form.delivery_postal_code) {
        checkDeliveryCost();
    }
});
</script>

<template>
    <div v-if="isOpen && vehicle" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="close">
        <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            
            <!-- Header -->
            <div class="sticky top-0 bg-white border-b border-slate-200 p-6 rounded-t-3xl">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold text-slate-900">Je suis intéressé(e)</h3>
                        <p class="text-slate-600 mt-1">{{ vehicle.make }} {{ vehicle.model }}</p>
                    </div>
                    <button @click="close" class="w-10 h-10 rounded-full hover:bg-slate-100 flex items-center justify-center transition-colors">
                        <i class="ph-bold ph-x text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <form @submit.prevent="submit" class="p-6 space-y-6">
                
                <!-- Type de demande -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Type de demande</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" value="callback" v-model="form.interest_type" class="peer sr-only">
                            <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-brand-blue peer-checked:bg-brand-blue/5 transition-all text-center">
                                <div class="w-10 h-10 mx-auto mb-2 bg-blue-100 peer-checked:bg-brand-blue/20 rounded-full flex items-center justify-center text-blue-600 peer-checked:text-brand-blue transition-colors">
                                    <i class="ph-bold ph-phone text-xl"></i>
                                </div>
                                <span class="block font-bold text-slate-700">Me rappeler</span>
                                <span class="text-xs text-slate-500">Nous vous contactons</span>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" value="reservation" v-model="form.interest_type" class="peer sr-only">
                            <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-brand-orange peer-checked:bg-brand-orange/5 transition-all text-center">
                                <div class="w-10 h-10 mx-auto mb-2 bg-orange-100 peer-checked:bg-brand-orange/20 rounded-full flex items-center justify-center text-orange-600 peer-checked:text-brand-orange transition-colors">
                                    <i class="ph-bold ph-calendar-check text-xl"></i>
                                </div>
                                <span class="block font-bold text-slate-700">Réserver visite</span>
                                <span class="text-xs text-slate-500">Choisir un créneau</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Date et heure (si réservation) -->
                <div v-if="form.interest_type === 'reservation'" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3">Choisissez un créneau disponible *</label>
                        
                        <!-- Loading State -->
                        <div v-if="loadingAvailabilities" class="text-center py-8">
                            <div class="inline-block w-8 h-8 border-4 border-brand-blue border-t-transparent rounded-full animate-spin"></div>
                            <p class="text-sm text-slate-500 mt-2">Chargement des disponibilités...</p>
                        </div>

                        <!-- Empty State -->
                        <div v-else-if="availabilities.length === 0" class="text-center py-8 bg-slate-50 rounded-xl border-2 border-dashed border-slate-200">
                            <i class="ph-fill ph-calendar-x text-4xl text-slate-400 mb-2"></i>
                            <p class="text-sm text-slate-600 font-medium">Aucun créneau disponible pour le moment</p>
                            <p class="text-xs text-slate-500 mt-1">Veuillez choisir "Me rappeler" ou réessayer plus tard</p>
                        </div>

                        <!-- Availabilities Grid -->
                        <div v-else class="grid grid-cols-1 gap-3 max-h-96 overflow-y-auto">
                            <button
                                v-for="availability in availabilities"
                                :key="availability.id"
                                type="button"
                                @click="selectAvailability(availability)"
                                class="p-4 rounded-xl border-2 transition-all text-left"
                                :class="selectedAvailability?.id === availability.id 
                                    ? 'border-brand-blue bg-brand-blue/5 shadow-md' 
                                    : 'border-slate-200 hover:border-brand-blue/50 hover:bg-slate-50'"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                            :class="selectedAvailability?.id === availability.id 
                                                ? 'bg-brand-blue text-white' 
                                                : 'bg-slate-100 text-slate-600'"
                                        >
                                            <i class="ph-fill ph-calendar text-xl"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">
                                                {{ new Date(availability.date).toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long' }) }}
                                            </div>
                                            <div class="text-sm text-slate-600">
                                                {{ availability.start_time.substring(0, 5) }} - {{ availability.end_time.substring(0, 5) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-medium px-2 py-1 rounded-full"
                                            :class="availability.available_slots > 2 
                                                ? 'bg-green-100 text-green-700' 
                                                : 'bg-orange-100 text-orange-700'"
                                        >
                                            {{ availability.available_slots }} place{{ availability.available_slots > 1 ? 's' : '' }}
                                        </span>
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Options de livraison (seulement pour location) -->
                <div v-if="isRentalVehicle && form.interest_type === 'reservation'" class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-3">Mode de récupération *</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="cursor-pointer">
                                <input type="radio" value="pickup" v-model="form.delivery_option" class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-brand-blue peer-checked:bg-brand-blue/5 transition-all text-center">
                                    <div class="w-10 h-10 mx-auto mb-2 bg-blue-100 peer-checked:bg-brand-blue/20 rounded-full flex items-center justify-center text-blue-600 peer-checked:text-brand-blue transition-colors">
                                        <i class="ph-bold ph-map-pin text-xl"></i>
                                    </div>
                                    <span class="block font-bold text-slate-700">Récupération sur place</span>
                                    <span class="text-xs text-green-600 font-semibold">Gratuit</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" value="delivery" v-model="form.delivery_option" class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-brand-orange peer-checked:bg-brand-orange/5 transition-all text-center">
                                    <div class="w-10 h-10 mx-auto mb-2 bg-orange-100 peer-checked:bg-brand-orange/20 rounded-full flex items-center justify-center text-orange-600 peer-checked:text-brand-orange transition-colors">
                                        <i class="ph-bold ph-truck text-xl"></i>
                                    </div>
                                    <span class="block font-bold text-slate-700">Livraison à domicile</span>
                                    <span class="text-xs text-slate-500">Selon zone</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Adresse de livraison -->
                    <div v-if="form.delivery_option === 'delivery'" class="space-y-4 p-5 bg-orange-50 rounded-xl border border-orange-200">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Adresse de livraison *</label>
                            <input 
                                type="text" 
                                v-model="form.delivery_address"
                                class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 focus:border-brand-orange focus:ring-2 focus:ring-brand-orange/20 outline-none transition-all"
                                placeholder="123 Rue Exemple"
                                required
                            >
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Code postal *</label>
                                <input 
                                    type="text" 
                                    v-model="form.delivery_postal_code"
                                    class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 focus:border-brand-orange focus:ring-2 focus:ring-brand-orange/20 outline-none transition-all"
                                    placeholder="97110"
                                    required
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Ville *</label>
                                <input 
                                    type="text" 
                                    v-model="form.delivery_city"
                                    class="w-full px-4 py-3 rounded-xl bg-white border border-slate-200 focus:border-brand-orange focus:ring-2 focus:ring-brand-orange/20 outline-none transition-all"
                                    placeholder="Pointe-à-Pitre"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Calcul des frais de livraison -->
                        <div class="p-4 bg-white rounded-xl border border-orange-200">
                            <div v-if="loadingDeliveryCost" class="flex items-center gap-3 text-slate-600">
                                <div class="w-5 h-5 border-2 border-brand-orange border-t-transparent rounded-full animate-spin"></div>
                                <span class="text-sm">Calcul des frais de livraison...</span>
                            </div>
                            <div v-else-if="deliveryError" class="flex items-center gap-3 text-red-600">
                                <i class="ph-fill ph-warning-circle text-xl"></i>
                                <span class="text-sm font-medium">{{ deliveryError }}</span>
                            </div>
                            <div v-else-if="deliveryCost > 0" class="flex items-center justify-between">
                                <div class="flex items-center gap-3 text-green-700">
                                    <i class="ph-fill ph-check-circle text-xl"></i>
                                    <span class="text-sm font-medium">Livraison disponible</span>
                                </div>
                                <span class="text-lg font-bold text-brand-orange">{{ deliveryCost.toFixed(2) }} €</span>
                            </div>
                            <div v-else class="text-sm text-slate-500 italic">
                                Entrez votre code postal pour voir les frais de livraison
                            </div>
                        </div>

                        <!-- Total avec livraison -->
                        <div class="p-4 bg-gradient-to-r from-brand-orange to-brand-red rounded-xl text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-sm opacity-90">Total estimé (1 jour)</span>
                                    <div class="text-xs opacity-75 mt-1">{{ vehicle.rent_price }} € + {{ deliveryCost.toFixed(2) }} € livraison</div>
                                </div>
                                <span class="text-3xl font-extrabold">{{ totalCost.toFixed(2) }} €</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coordonnées -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Nom complet *</label>
                        <input 
                            type="text" 
                            v-model="form.name"
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                            placeholder="Jean Dupont"
                            required
                        >
                        <span v-if="form.errors.name" class="text-sm text-red-600 mt-1 block">{{ form.errors.name }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Email *</label>
                            <input 
                                type="email" 
                                v-model="form.email"
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                                placeholder="jean@example.com"
                                required
                            >
                            <span v-if="form.errors.email" class="text-sm text-red-600 mt-1 block">{{ form.errors.email }}</span>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Téléphone *</label>
                            <input 
                                type="tel" 
                                v-model="form.phone"
                                class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                                placeholder="06 12 34 56 78"
                                required
                            >
                            <span v-if="form.errors.phone" class="text-sm text-red-600 mt-1 block">{{ form.errors.phone }}</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Message (optionnel)</label>
                        <textarea 
                            v-model="form.message"
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all resize-none"
                            placeholder="Questions ou précisions..."
                            rows="3"
                        ></textarea>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-4 pt-4">
                    <button 
                        type="button" 
                        @click="close"
                        class="flex-1 py-3 rounded-xl border-2 border-slate-200 font-bold text-slate-700 hover:bg-slate-50 transition-all"
                    >
                        Annuler
                    </button>
                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="flex-1 py-3 rounded-xl bg-brand-gradient text-white font-bold shadow-lg hover:opacity-90 transition-all disabled:opacity-50 flex items-center justify-center gap-2"
                    >
                        <i class="ph-bold ph-paper-plane-right"></i>
                        <span v-if="!form.processing">Envoyer</span>
                        <span v-else>Envoi...</span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</template>

<style scoped>
/* Styles conservés pour compatibilité */
</style>
