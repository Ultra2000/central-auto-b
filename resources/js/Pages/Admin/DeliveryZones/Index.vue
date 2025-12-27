<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    zones: Array
});

const showModal = ref(false);
const editingId = ref(null);

const form = useForm({
    name: '',
    postal_codes: '',
    cost: 0,
    is_active: true
});

const openCreateModal = () => {
    editingId.value = null;
    form.reset();
    showModal.value = true;
};

const openEditModal = (zone) => {
    editingId.value = zone.id;
    form.name = zone.name;
    form.postal_codes = zone.postal_codes;
    form.cost = zone.cost;
    form.is_active = zone.is_active;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    editingId.value = null;
    form.reset();
};

const submit = () => {
    if (editingId.value) {
        form.put(route('admin.delivery-zones.update', editingId.value), {
            preserveScroll: true,
            onSuccess: () => closeModal()
        });
    } else {
        form.post(route('admin.delivery-zones.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal()
        });
    }
};

const deleteZone = (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette zone ?')) {
        router.delete(route('admin.delivery-zones.destroy', id));
    }
};
</script>

<template>
    <Head title="Zones de Livraison" />

    <AdminLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-2xl text-brand-dark leading-tight">Zones de Livraison</h2>
                    <p class="text-slate-500 mt-1">Gérez les zones et tarifs de livraison pour la location</p>
                </div>
                <button @click="openCreateModal" class="px-6 py-3 bg-brand-gradient text-white font-bold rounded-xl shadow-lg hover:opacity-90 transition-all flex items-center gap-2">
                    <i class="ph-bold ph-plus-circle text-xl"></i>
                    Ajouter une zone
                </button>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Flash Message -->
            <div v-if="$page.props.flash?.success" class="p-4 bg-green-50 border-2 border-green-200 rounded-xl flex items-center gap-3 text-green-700">
                <i class="ph-fill ph-check-circle text-2xl"></i>
                <p class="font-medium">{{ $page.props.flash.success }}</p>
            </div>

            <!-- Zones List -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Zone</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Codes Postaux</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Tarif</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase">Statut</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr v-for="zone in zones" :key="zone.id" class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-brand-orange">
                                        <i class="ph-fill ph-map-pin text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ zone.name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-slate-600 font-mono">{{ zone.postal_codes }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-lg font-bold" :class="zone.cost === 0 ? 'text-green-600' : 'text-brand-orange'">
                                    {{ zone.cost === 0 ? 'Gratuit' : `${zone.cost} €` }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold"
                                    :class="zone.is_active ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600'">
                                    {{ zone.is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="openEditModal(zone)" 
                                        class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium rounded-lg transition-colors flex items-center gap-2">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                        Modifier
                                    </button>
                                    <button @click="deleteZone(zone.id)" 
                                        class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-600 font-medium rounded-lg transition-colors">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" @click.self="closeModal">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full">
                <div class="p-6 border-b border-slate-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-2xl font-bold text-brand-dark">
                            {{ editingId ? 'Modifier' : 'Ajouter' }} une zone de livraison
                        </h3>
                        <button @click="closeModal" class="w-10 h-10 rounded-full hover:bg-slate-100 flex items-center justify-center transition-colors">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nom de la zone *</label>
                        <input 
                            type="text" 
                            v-model="form.name"
                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-brand-blue focus:ring-4 focus:ring-brand-blue/10 transition-all outline-none"
                            placeholder="Guadeloupe Nord"
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Codes postaux *</label>
                        <textarea 
                            v-model="form.postal_codes"
                            rows="3"
                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-brand-blue focus:ring-4 focus:ring-brand-blue/10 transition-all outline-none resize-none"
                            placeholder="97110, 97111, 97112 ou 971* pour tous les codes commençant par 971"
                            required
                        ></textarea>
                        <p class="text-xs text-slate-500 mt-2">
                            Séparez les codes postaux par des virgules. Utilisez * pour les wildcards (ex: 971*)
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tarif de livraison (€) *</label>
                        <input 
                            type="number" 
                            v-model="form.cost"
                            min="0"
                            step="0.01"
                            class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 focus:border-brand-blue focus:ring-4 focus:ring-brand-blue/10 transition-all outline-none"
                            placeholder="25.00"
                            required
                        >
                        <p class="text-xs text-slate-500 mt-1">Mettez 0 pour une livraison gratuite</p>
                    </div>

                    <div v-if="editingId">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input 
                                type="checkbox" 
                                v-model="form.is_active"
                                class="w-5 h-5 rounded border-2 border-slate-300 text-brand-blue focus:ring-2 focus:ring-brand-blue/20"
                            >
                            <span class="text-sm font-bold text-slate-700">Zone active</span>
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
