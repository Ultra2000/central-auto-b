<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    vehicles: Object
});

const deleteVehicle = (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce véhicule ?')) {
        router.delete(route('admin.vehicles.destroy', id));
    }
};
</script>

<template>
    <Head title="Gestion des Véhicules" />

    <AdminLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="font-bold text-2xl text-brand-dark leading-tight">Véhicules</h2>
                    <p class="text-slate-500 mt-1">Gérez votre stock de véhicules</p>
                </div>
                <Link :href="route('admin.vehicles.create')" class="bg-brand-gradient text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-brand-red/20 hover:shadow-brand-red/40 hover:-translate-y-1 transition-all flex items-center justify-center gap-2 w-full md:w-auto">
                    <i class="ph-bold ph-plus"></i>
                    Ajouter un véhicule
                </Link>
            </div>
        </template>

        <div class="w-full max-w-full bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Véhicule</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Prix</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        <tr v-for="vehicle in vehicles.data" :key="vehicle.id" class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-12 w-12 flex-shrink-0 relative">
                                        <img v-if="vehicle.main_image || (vehicle.images && vehicle.images.length)" :src="vehicle.main_image || vehicle.images[0]" class="h-12 w-12 rounded-xl object-cover shadow-sm border border-slate-100" alt="">
                                        <div v-else class="h-12 w-12 rounded-xl bg-slate-100 flex items-center justify-center border border-slate-200">
                                            <i class="ph-fill ph-car text-slate-400 text-xl"></i>
                                        </div>
                                        <div v-if="vehicle.is_featured" class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-brand-orange flex items-center justify-center shadow-lg">
                                            <i class="ph-fill ph-star text-white text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-brand-dark">{{ vehicle.make }} {{ vehicle.model }}</div>
                                        <div class="text-xs text-slate-500">{{ vehicle.year }} • {{ vehicle.fuel }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border" 
                                    :class="vehicle.type === 'sale' ? 'bg-brand-orange/10 text-brand-orange border-brand-orange/20' : 'bg-brand-blue/10 text-brand-blue border-brand-blue/20'">
                                    {{ vehicle.type === 'sale' ? 'Vente' : 'Location' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-brand-dark">
                                {{ vehicle.type === 'sale' ? vehicle.price + ' €' : vehicle.rent_price + ' €/j' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border"
                                    :class="vehicle.is_available ? 'bg-green-50 text-green-700 border-green-100' : 'bg-red-50 text-red-700 border-red-100'">
                                    <span class="w-1.5 h-1.5 rounded-full mr-2 my-auto" :class="vehicle.is_available ? 'bg-green-500' : 'bg-red-500'"></span>
                                    {{ vehicle.is_available ? 'Disponible' : 'Indisponible' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <Link :href="route('admin.vehicles.edit', vehicle.id)" class="w-8 h-8 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center hover:bg-brand-blue hover:text-white transition-colors">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </Link>
                                    <button @click="deleteVehicle(vehicle.id)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors">
                                        <i class="ph-bold ph-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100 flex justify-center" v-if="vehicles.links.length > 3">
                <div class="flex gap-1">
                    <Link v-for="(link, key) in vehicles.links" :key="key" 
                        :href="link.url" 
                        v-html="link.label"
                        class="px-3 py-1 rounded-lg text-sm font-medium transition-colors"
                        :class="link.active ? 'bg-brand-blue text-white shadow-md shadow-brand-blue/20' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200'"
                        :preserve-scroll="true"
                    />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>