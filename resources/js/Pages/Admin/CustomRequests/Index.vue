<script setup>
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    requests: Object
});

const updateStatus = (requestId, status) => {
    router.patch(route('admin.custom-requests.update-status', requestId), {
        status: status
    }, {
        preserveScroll: true
    });
};

const getStatusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-800 border-yellow-200',
        processing: 'bg-blue-100 text-blue-800 border-blue-200',
        completed: 'bg-green-100 text-green-800 border-green-200',
        cancelled: 'bg-red-100 text-red-800 border-red-200'
    };
    return colors[status] || colors.pending;
};

const getStatusLabel = (status) => {
    const labels = {
        pending: 'En attente',
        processing: 'En cours',
        completed: 'Terminé',
        cancelled: 'Annulé'
    };
    return labels[status] || status;
};

const getTypeLabel = (type) => {
    const labels = {
        achat: 'Achat',
        location: 'Location',
        expertise: 'Expertise'
    };
    return labels[type] || type;
};

const getTypeColor = (type) => {
    const colors = {
        achat: 'bg-orange-100 text-orange-800',
        location: 'bg-blue-100 text-blue-800',
        expertise: 'bg-purple-100 text-purple-800'
    };
    return colors[type] || colors.achat;
};
</script>

<template>
    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <!-- Header -->
                <div class="mb-8 flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 mb-2">
                            <i class="ph-bold ph-sparkle text-brand-orange mr-2"></i>
                            Demandes Personnalisées
                        </h1>
                        <p class="text-slate-600">Gérez les demandes de vos clients</p>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 bg-white rounded-xl border border-slate-200 shadow-sm">
                        <span class="text-3xl font-extrabold text-brand-blue">{{ requests.total }}</span>
                        <span class="text-sm text-slate-500">demandes</span>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    
                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Client</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Détails</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr v-for="request in requests.data" :key="request.id" class="hover:bg-slate-50 transition-colors">
                                    
                                    <!-- ID -->
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm font-bold text-slate-900">#{{ request.id }}</span>
                                    </td>

                                    <!-- Type -->
                                    <td class="px-6 py-4">
                                        <span :class="getTypeColor(request.type)" class="px-3 py-1 rounded-full text-xs font-bold">
                                            {{ getTypeLabel(request.type) }}
                                        </span>
                                    </td>

                                    <!-- Client -->
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-slate-900">{{ request.name }}</div>
                                    </td>

                                    <!-- Contact -->
                                    <td class="px-6 py-4">
                                        <div class="space-y-1 text-sm">
                                            <div class="flex items-center gap-2 text-slate-600">
                                                <i class="ph-fill ph-envelope text-brand-blue"></i>
                                                <a :href="'mailto:' + request.email" class="hover:text-brand-blue transition-colors">
                                                    {{ request.email }}
                                                </a>
                                            </div>
                                            <div class="flex items-center gap-2 text-slate-600">
                                                <i class="ph-fill ph-phone text-brand-orange"></i>
                                                <a :href="'tel:' + request.phone" class="hover:text-brand-orange transition-colors">
                                                    {{ request.phone }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Détails -->
                                    <td class="px-6 py-4">
                                        <div class="space-y-1 text-sm">
                                            <div v-if="request.brand" class="text-slate-700">
                                                <span class="font-semibold">Marque:</span> {{ request.brand }}
                                            </div>
                                            <div v-if="request.model" class="text-slate-700">
                                                <span class="font-semibold">Modèle:</span> {{ request.model }}
                                            </div>
                                            <div v-if="request.category" class="text-slate-700">
                                                <span class="font-semibold">Catégorie:</span> {{ request.category }}
                                            </div>
                                            <div v-if="request.budget" class="text-slate-700">
                                                <span class="font-semibold">Budget:</span> {{ request.budget }}
                                            </div>
                                            <div v-if="request.transmission" class="text-slate-700">
                                                <span class="font-semibold">Transmission:</span> {{ request.transmission }}
                                            </div>
                                            <div v-if="request.message" class="text-slate-600 italic mt-2">
                                                "{{ request.message.substring(0, 100) }}{{ request.message.length > 100 ? '...' : '' }}"
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Statut -->
                                    <td class="px-6 py-4">
                                        <select 
                                            :value="request.status"
                                            @change="updateStatus(request.id, $event.target.value)"
                                            :class="getStatusColor(request.status)"
                                            class="px-3 py-1.5 rounded-lg text-xs font-bold border cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue"
                                        >
                                            <option value="pending">En attente</option>
                                            <option value="processing">En cours</option>
                                            <option value="completed">Terminé</option>
                                            <option value="cancelled">Annulé</option>
                                        </select>
                                    </td>

                                    <!-- Date -->
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-slate-600">
                                            {{ new Date(request.created_at).toLocaleDateString('fr-FR', { 
                                                day: 'numeric', 
                                                month: 'short', 
                                                year: 'numeric' 
                                            }) }}
                                        </div>
                                        <div class="text-xs text-slate-400">
                                            {{ new Date(request.created_at).toLocaleTimeString('fr-FR', { 
                                                hour: '2-digit', 
                                                minute: '2-digit' 
                                            }) }}
                                        </div>
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="requests.links && requests.links.length > 3" class="bg-slate-50 px-6 py-4 border-t border-slate-200">
                        <div class="flex justify-center gap-2">
                            <button 
                                v-for="link in requests.links" 
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
                                            : 'bg-slate-100 text-slate-400 cursor-not-allowed'
                                ]"
                            ></button>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="!requests.data || requests.data.length === 0" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <i class="ph-fill ph-sparkle text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Aucune demande</h3>
                    <p class="text-slate-500">Les demandes personnalisées de vos clients apparaîtront ici.</p>
                </div>

            </div>
        </div>
    </AdminLayout>
</template>
