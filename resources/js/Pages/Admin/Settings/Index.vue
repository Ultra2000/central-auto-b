<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    settings: Object
});

const form = useForm({
    plate_price_plexiglass: props.settings.plate_price_plexiglass,
    plate_price_alu: props.settings.plate_price_alu,
    contact_phone: props.settings.contact_phone,
    contact_email: props.settings.contact_email,
    contact_address: props.settings.contact_address,
    opening_hours_week: props.settings.opening_hours_week,
    opening_hours_weekend: props.settings.opening_hours_weekend,
});

const submit = () => {
    form.post(route('admin.settings.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-extrabold text-slate-900 mb-2">
                        <i class="ph-bold ph-gear text-brand-blue mr-2"></i>
                        Paramètres
                    </h1>
                    <p class="text-slate-600">Gérez les paramètres de l'application</p>
                </div>

                <!-- Settings Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    
                    <!-- Section: Prix des Plaques -->
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                                <i class="ph-bold ph-currency-circle-dollar text-blue-600 text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">Prix des plaques</h2>
                                <p class="text-sm text-slate-500">Définissez les prix unitaires des plaques d'immatriculation</p>
                            </div>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            
                            <!-- Plexiglass Price -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Prix Plexiglass (€)
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        step="0.01"
                                        v-model="form.plate_price_plexiglass"
                                        class="w-full px-4 py-3 pr-12 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                                        placeholder="14.90"
                                        required
                                    >
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">€</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-2">Prix unitaire pour une plaque en plexiglass</p>
                                <span v-if="form.errors.plate_price_plexiglass" class="text-sm text-red-600 mt-1 block">{{ form.errors.plate_price_plexiglass }}</span>
                            </div>

                            <!-- Aluminium Price -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Prix Aluminium (€)
                                </label>
                                <div class="relative">
                                    <input 
                                        type="number" 
                                        step="0.01"
                                        v-model="form.plate_price_alu"
                                        class="w-full px-4 py-3 pr-12 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                                        placeholder="12.90"
                                        required
                                    >
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">€</span>
                                </div>
                                <p class="text-xs text-slate-500 mt-2">Prix unitaire pour une plaque en aluminium</p>
                                <span v-if="form.errors.plate_price_alu" class="text-sm text-red-600 mt-1 block">{{ form.errors.plate_price_alu }}</span>
                            </div>

                            <!-- Price Preview -->
                            <div class="bg-slate-50 rounded-xl p-6 space-y-3">
                                <h3 class="font-bold text-slate-900 mb-3">Aperçu des prix</h3>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                            <i class="ph-fill ph-diamond text-blue-600"></i>
                                        </div>
                                        <span class="font-medium text-slate-700">Plexiglass</span>
                                    </div>
                                    <span class="text-2xl font-extrabold text-brand-blue">{{ parseFloat(form.plate_price_plexiglass).toFixed(2) }} €</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-slate-200 flex items-center justify-center">
                                            <i class="ph-fill ph-square text-slate-600"></i>
                                        </div>
                                        <span class="font-medium text-slate-700">Aluminium</span>
                                    </div>
                                    <span class="text-2xl font-extrabold text-slate-900">{{ parseFloat(form.plate_price_alu).toFixed(2) }} €</span>
                                </div>
                            </div>

                            <!-- Section: Coordonnées -->
                            <div class="pt-8 border-t border-slate-100">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center">
                                        <i class="ph-bold ph-address-book text-orange-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-slate-900">Coordonnées</h2>
                                        <p class="text-sm text-slate-500">Informations de contact affichées sur le site</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Téléphone</label>
                                        <input 
                                            type="text" 
                                            v-model="form.contact_phone"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                                            placeholder="01 23 45 67 89"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
                                        <input 
                                            type="email" 
                                            v-model="form.contact_email"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                                            placeholder="contact@example.com"
                                        >
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Adresse</label>
                                        <input 
                                            type="text" 
                                            v-model="form.contact_address"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                                            placeholder="123 Rue de l'Exemple, 75000 Paris"
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Section: Horaires -->
                            <div class="pt-8 border-t border-slate-100">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                                        <i class="ph-bold ph-clock text-green-600 text-xl"></i>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-slate-900">Horaires d'ouverture</h2>
                                        <p class="text-sm text-slate-500">Horaires affichés en bas de page</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Semaine (Lun-Ven)</label>
                                        <input 
                                            type="text" 
                                            v-model="form.opening_hours_week"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                                            placeholder="09h00 - 18h00"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Week-end (Sam)</label>
                                        <input 
                                            type="text" 
                                            v-model="form.opening_hours_weekend"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                                            placeholder="09h00 - 12h00"
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end pt-4">
                                <button 
                                    type="submit" 
                                    :disabled="form.processing"
                                    class="px-8 py-3 rounded-xl bg-brand-gradient text-white font-bold shadow-lg shadow-brand-orange/20 hover:opacity-90 transition-all disabled:opacity-50 flex items-center gap-2"
                                >
                                    <i class="ph-bold ph-floppy-disk"></i>
                                    <span v-if="!form.processing">Enregistrer</span>
                                    <span v-else>Enregistrement...</span>
                                </button>
                            </div>

                        </form>
                    </div>

                </div>

                <!-- Info Card -->
                <div class="mt-6 bg-blue-50 rounded-2xl p-6 border border-blue-100">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                            <i class="ph-bold ph-info text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-blue-900 mb-1">Note importante</h3>
                            <p class="text-sm text-blue-700">
                                Les prix modifiés ici seront immédiatement appliqués sur le configurateur de plaques visible par les clients. 
                                Les commandes existantes conserveront leurs prix d'origine.
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AdminLayout>
</template>
