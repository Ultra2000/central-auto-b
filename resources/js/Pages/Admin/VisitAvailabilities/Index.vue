<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    availabilities: Array
});

const showModal = ref(false);
const editingId = ref(null);

const form = useForm({
    date: '',
    start_time: '09:00',
    end_time: '10:00',
    max_bookings: 1,
    is_active: true
});

const openCreateModal = () => {
    editingId.value = null;
    form.reset();
    form.date = new Date().toISOString().split('T')[0];
    showModal.value = true;
};

const openEditModal = (availability) => {
    editingId.value = availability.id;
    form.date = availability.date;
    form.start_time = availability.start_time;
    form.end_time = availability.end_time;
    form.max_bookings = availability.max_bookings;
    form.is_active = availability.is_active;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingId.value = null;
    form.reset();
};

const submit = () => {
    if (editingId.value) {
        form.put(route('admin.visit-availabilities.update', editingId.value), {
            preserveScroll: true,
            onSuccess: () => closeModal()
        });
    } else {
        form.post(route('admin.visit-availabilities.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal()
        });
    }
};

const deleteAvailability = (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette disponibilité ?')) {
        router.delete(route('admin.visit-availabilities.destroy', id));
    }
};

const groupedAvailabilities = computed(() => {
    const groups = {};
    props.availabilities.forEach(availability => {
        if (!groups[availability.date]) {
            groups[availability.date] = [];
        }
        groups[availability.date].push(availability);
    });
    return groups;
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
};

const formatTime = (time) => {
    return time.substring(0, 5);
};
</script>

<template>
    <Head title="Disponibilités des Visites" />

    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-brand-dark leading-tight">Disponibilités des Visites</h2>
                    <p class="text-slate-500 mt-1">Gérez les créneaux horaires pour les réservations de visite</p>
                </div>
                <button @click="openCreateModal" class="px-6 py-3 bg-brand-gradient text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition-all flex items-center gap-2">
                    <i class="ph-bold ph-plus-circle text-xl"></i>
                    Ajouter un créneau
                </button>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Flash Message -->
            <div v-if="$page.props.flash?.success" class="p-4 bg-green-50 border-2 border-green-200 rounded-xl flex items-center gap-3 text-green-700">
                <i class="ph-fill ph-check-circle text-2xl"></i>
                <p class="font-medium">{{ $page.props.flash.success }}</p>
            </div>

            <!-- Availabilities List -->
            <div v-if="Object.keys(groupedAvailabilities).length > 0" class="space-y-6">
                <div v-for="(slots, date) in groupedAvailabilities" :key="date" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-200">
                        <h3 class="text-lg font-bold text-brand-dark flex items-center gap-3">
                            <i class="ph-fill ph-calendar text-brand-blue"></i>
                            {{ formatDate(date) }}
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div v-for="availability in slots" :key="availability.id" 
                                class="p-5 rounded-xl border-2 transition-all"
                                :class="availability.is_active ? 'border-slate-200 bg-white hover:border-brand-blue hover:shadow-md' : 'border-slate-200 bg-slate-50'">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center"
                                            :class="availability.is_active ? 'bg-brand-blue/10 text-brand-blue' : 'bg-slate-200 text-slate-400'">
                                            <i class="ph-fill ph-clock text-xl"></i>
                                        </div>
                                        <div>
                                            <div class="font-bold text-lg text-slate-900">
                                                {{ formatTime(availability.start_time) }} - {{ formatTime(availability.end_time) }}
                                            </div>
                                            <div class="text-sm text-slate-500">
                                                {{ availability.current_bookings }} / {{ availability.max_bookings }} réservations
                                            </div>
                                        </div>
                                    </div>
                                    <span v-if="!availability.is_active" class="px-3 py-1 bg-slate-200 text-slate-600 text-xs font-bold rounded-full">
                                        Inactif
                                    </span>
                                </div>

                                <!-- Progress Bar -->
                                <div class="mb-4">
                                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-brand-blue rounded-full transition-all duration-300"
                                            :style="{ width: `${(availability.current_bookings / availability.max_bookings) * 100}%` }">
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <button @click="openEditModal(availability)" 
                                        class="flex-1 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                        Modifier
                                    </button>
                                    <button @click="deleteAvailability(availability.id)" 
                                        class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-medium rounded-lg transition-colors">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-else class="bg-white rounded-2xl shadow-sm border border-slate-100 p-16 text-center">
                <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mx-auto mb-6">
                    <i class="ph-fill ph-calendar-x text-5xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-700 mb-2">Aucune disponibilité</h3>
                <p class="text-slate-500 mb-6">Commencez par ajouter des créneaux horaires pour les visites</p>
                <button @click="openCreateModal" class="px-6 py-3 bg-brand-gradient text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition-all inline-flex items-center gap-2">
                    <i class="ph-bold ph-plus-circle text-xl"></i>
                    Ajouter un créneau
                </button>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="closeModal">
            <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-brand-dark">
                            {{ editingId ? 'Modifier' : 'Ajouter' }} une disponibilité
                        </h3>
                        <button @click="closeModal" class="w-10 h-10 rounded-full hover:bg-slate-100 flex items-center justify-center transition-colors">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Date *</label>
                        <input 
                            type="date" 
                            v-model="form.date"
                            :min="new Date().toISOString().split('T')[0]"
                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-brand-blue focus:ring-4 focus:ring-brand-blue/10 transition-all outline-none"
                            required
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Heure de début *</label>
                            <input 
                                type="time" 
                                v-model="form.start_time"
                                class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-brand-blue focus:ring-4 focus:ring-brand-blue/10 transition-all outline-none"
                                required
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Heure de fin *</label>
                            <input 
                                type="time" 
                                v-model="form.end_time"
                                class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-brand-blue focus:ring-4 focus:ring-brand-blue/10 transition-all outline-none"
                                required
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nombre de réservations maximum *</label>
                        <input 
                            type="number" 
                            v-model="form.max_bookings"
                            min="1"
                            max="10"
                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-brand-blue focus:ring-4 focus:ring-brand-blue/10 transition-all outline-none"
                            required
                        >
                        <p class="text-xs text-slate-500 mt-1">Nombre de clients pouvant réserver ce créneau</p>
                    </div>

                    <div v-if="editingId">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                v-model="form.is_active"
                                class="w-5 h-5 rounded border-2 border-slate-300 text-brand-blue focus:ring-2 focus:ring-brand-blue/20"
                            >
                            <span class="text-sm font-bold text-slate-700">Créneau actif</span>
                        </label>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button 
                            type="button" 
                            @click="closeModal"
                            class="flex-1 px-6 py-3 border-2 border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-all"
                        >
                            Annuler
                        </button>
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="flex-1 px-6 py-3 bg-brand-gradient text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition-all disabled:opacity-50 flex items-center justify-center gap-2"
                        >
                            <i class="ph-bold ph-check"></i>
                            {{ editingId ? 'Mettre à jour' : 'Créer' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
