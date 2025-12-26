<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({
    leads: Object
});

const updateStatus = (lead, status) => {
    router.put(route('admin.leads.update', lead.id), { status }, {
        preserveScroll: true
    });
};

const deleteLead = (id) => {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce lead ?')) {
        router.delete(route('admin.leads.destroy', id));
    }
};
</script>

<template>
    <Head title="Gestion des Leads" />

    <AdminLayout>
        <template #header>
            <div>
                <h2 class="font-bold text-2xl text-brand-dark leading-tight">Contacts & Leads</h2>
                <p class="text-slate-500 mt-1">Gérez les demandes clients</p>
            </div>
        </template>

        <div class="w-full max-w-full bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Message / Véhicule</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        <tr v-for="lead in leads.data" :key="lead.id" class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold">
                                        {{ lead.name.charAt(0) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-brand-dark">{{ lead.name }}</div>
                                        <div class="text-xs text-slate-500">{{ lead.email }}</div>
                                        <div class="text-xs text-slate-500">{{ lead.phone }}</div>
                                    </div>
                                </div>
                                <div class="text-xs text-slate-400 mt-2 flex items-center gap-1">
                                    <i class="ph-fill ph-clock"></i>
                                    {{ new Date(lead.created_at).toLocaleString() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border" 
                                    :class="{
                                        'bg-blue-50 text-blue-700 border-blue-100': lead.type === 'contact',
                                        'bg-green-50 text-green-700 border-green-100': lead.type === 'callback',
                                        'bg-purple-50 text-purple-700 border-purple-100': lead.type === 'vehicle_inquiry'
                                    }">
                                    <i class="ph-fill mr-1.5" :class="{
                                        'ph-envelope': lead.type === 'contact',
                                        'ph-phone': lead.type === 'callback',
                                        'ph-car': lead.type === 'vehicle_inquiry'
                                    }"></i>
                                    {{ lead.type === 'contact' ? 'Message' : (lead.type === 'callback' ? 'Rappel' : 'Véhicule') }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div v-if="lead.vehicle" class="flex items-center gap-2 mb-2 p-2 rounded-lg bg-slate-50 border border-slate-100 w-fit">
                                    <i class="ph-fill ph-car text-brand-blue"></i>
                                    <span class="text-xs font-bold text-brand-dark">{{ lead.vehicle.make }} {{ lead.vehicle.model }}</span>
                                </div>
                                <div class="text-sm text-slate-600 italic bg-slate-50 p-3 rounded-lg border border-slate-100 max-w-md">
                                    "{{ lead.message || 'Aucun message' }}"
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select 
                                    :value="lead.status" 
                                    @change="updateStatus(lead, $event.target.value)"
                                    class="text-sm border-slate-200 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-brand-blue/20 cursor-pointer font-medium"
                                    :class="{
                                        'text-red-600 bg-red-50 border-red-100': lead.status === 'new',
                                        'text-orange-600 bg-orange-50 border-orange-100': lead.status === 'contacted',
                                        'text-slate-600 bg-slate-50 border-slate-200': lead.status === 'closed'
                                    }"
                                >
                                    <option value="new">Nouveau</option>
                                    <option value="contacted">Contacté</option>
                                    <option value="closed">Clôturé</option>
                                </select>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="deleteLead(lead.id)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors opacity-0 group-hover:opacity-100">
                                    <i class="ph-bold ph-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="p-4 border-t border-slate-100 flex justify-center" v-if="leads.links.length > 3">
                <div class="flex gap-1">
                    <Link v-for="(link, key) in leads.links" :key="key" 
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