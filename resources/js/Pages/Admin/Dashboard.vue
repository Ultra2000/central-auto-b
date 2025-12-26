<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    stats: Object,
    recent_leads: Array
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="font-bold text-2xl text-brand-dark leading-tight">Tableau de bord</h2>
                    <p class="text-slate-500 mt-1">Bienvenue sur votre espace d'administration</p>
                </div>
                <div class="text-sm text-slate-500 bg-white px-4 py-2 rounded-xl border border-slate-100 shadow-sm w-fit flex items-center gap-2">
                    <i class="ph-fill ph-calendar-blank text-brand-blue"></i>
                    {{ new Date().toLocaleDateString('fr-FR', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                </div>
            </div>
        </template>

        <!-- Stats Grid -->
        <div class="w-full max-w-full grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <!-- Total Vehicles -->
            <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg hover:border-brand-blue/20 transition-all duration-300">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-brand-blue/10 to-transparent rounded-bl-full"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-brand-blue/10 flex items-center justify-center text-brand-blue group-hover:scale-110 transition-transform">
                            <i class="ph-fill ph-car text-2xl"></i>
                        </div>
                        <span class="text-xs font-medium text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">Stock</span>
                    </div>
                    <div class="text-slate-500 text-sm font-medium mb-1">Total Véhicules</div>
                    <div class="text-3xl font-black text-brand-dark">{{ stats?.vehicles_count ?? 0 }}</div>
                </div>
            </div>

            <!-- Vehicles for Sale -->
            <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg hover:border-brand-orange/20 transition-all duration-300">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-brand-orange/10 to-transparent rounded-bl-full"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-brand-orange/10 flex items-center justify-center text-brand-orange group-hover:scale-110 transition-transform">
                            <i class="ph-fill ph-tag text-2xl"></i>
                        </div>
                        <span class="text-xs font-medium text-brand-orange bg-brand-orange/10 px-2 py-1 rounded-lg">Vente</span>
                    </div>
                    <div class="text-slate-500 text-sm font-medium mb-1">En Vente</div>
                    <div class="text-3xl font-black text-brand-dark">{{ stats?.vehicles_sale_count ?? 0 }}</div>
                </div>
            </div>

            <!-- Vehicles for Rent -->
            <div class="bg-white rounded-2xl p-5 md:p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-lg hover:border-blue-500/20 transition-all duration-300">
                <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-bl from-blue-500/10 to-transparent rounded-bl-full"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                            <i class="ph-fill ph-calendar-check text-2xl"></i>
                        </div>
                        <span class="text-xs font-medium text-blue-500 bg-blue-500/10 px-2 py-1 rounded-lg">Location</span>
                    </div>
                    <div class="text-slate-500 text-sm font-medium mb-1">En Location</div>
                    <div class="text-3xl font-black text-brand-dark">{{ stats?.vehicles_rent_count ?? 0 }}</div>
                </div>
            </div>

            <!-- New Leads -->
            <div class="bg-gradient-to-br from-brand-orange via-brand-red to-red-600 rounded-2xl p-5 md:p-6 shadow-lg shadow-brand-red/20 text-white relative overflow-hidden group hover:shadow-xl hover:shadow-brand-red/30 transition-all duration-300">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-3xl -mr-10 -mt-10"></div>
                <div class="absolute bottom-0 left-0 w-20 h-20 bg-black/10 rounded-full blur-2xl -ml-6 -mb-6"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-white backdrop-blur-sm group-hover:scale-110 transition-transform">
                            <i class="ph-fill ph-users text-2xl"></i>
                        </div>
                        <span v-if="stats?.leads_new_count > 0" class="bg-white text-brand-red px-2 py-1 rounded-lg text-xs font-bold animate-pulse">
                            +{{ stats?.leads_new_count }} nouveaux
                        </span>
                    </div>
                    <div class="text-white/80 text-sm font-medium mb-1">Total Contacts</div>
                    <div class="text-3xl font-black">{{ stats?.leads_total_count ?? 0 }}</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="w-full max-w-full grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-8">
            <Link :href="route('admin.vehicles.create')" class="bg-white border border-slate-100 rounded-xl p-4 flex items-center gap-3 hover:border-brand-blue hover:shadow-md transition-all group">
                <div class="w-10 h-10 rounded-lg bg-brand-blue/10 flex items-center justify-center text-brand-blue group-hover:bg-brand-blue group-hover:text-white transition-colors">
                    <i class="ph-bold ph-plus text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-slate-600 group-hover:text-brand-dark">Ajouter véhicule</span>
            </Link>
            <Link :href="route('admin.vehicles.index')" class="bg-white border border-slate-100 rounded-xl p-4 flex items-center gap-3 hover:border-brand-orange hover:shadow-md transition-all group">
                <div class="w-10 h-10 rounded-lg bg-brand-orange/10 flex items-center justify-center text-brand-orange group-hover:bg-brand-orange group-hover:text-white transition-colors">
                    <i class="ph-bold ph-car text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-slate-600 group-hover:text-brand-dark">Voir le stock</span>
            </Link>
            <Link :href="route('admin.leads.index')" class="bg-white border border-slate-100 rounded-xl p-4 flex items-center gap-3 hover:border-green-500 hover:shadow-md transition-all group">
                <div class="w-10 h-10 rounded-lg bg-green-500/10 flex items-center justify-center text-green-500 group-hover:bg-green-500 group-hover:text-white transition-colors">
                    <i class="ph-bold ph-users text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-slate-600 group-hover:text-brand-dark">Tous les leads</span>
            </Link>
            <a href="/" target="_blank" class="bg-white border border-slate-100 rounded-xl p-4 flex items-center gap-3 hover:border-purple-500 hover:shadow-md transition-all group">
                <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-500 group-hover:bg-purple-500 group-hover:text-white transition-colors">
                    <i class="ph-bold ph-globe text-lg"></i>
                </div>
                <span class="text-sm font-semibold text-slate-600 group-hover:text-brand-dark">Voir le site</span>
            </a>
        </div>

        <!-- Recent Leads -->
        <div class="w-full max-w-full bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-4 md:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                <h3 class="text-lg font-bold text-brand-dark flex items-center gap-2">
                    <i class="ph-fill ph-clock-counter-clockwise text-brand-blue"></i>
                    Derniers contacts reçus
                </h3>
                <Link :href="route('admin.leads.index')" class="text-sm font-semibold text-brand-blue hover:text-brand-dark transition-colors flex items-center gap-1">
                    Voir tout
                    <i class="ph-bold ph-arrow-right"></i>
                </Link>
            </div>
            
            <div v-if="!recent_leads || recent_leads.length === 0" class="p-12 text-center">
                <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center mb-4">
                    <i class="ph-duotone ph-inbox text-3xl text-slate-400"></i>
                </div>
                <p class="text-slate-500 font-medium">Aucun contact récent</p>
                <p class="text-slate-400 text-sm mt-1">Les nouveaux leads apparaîtront ici</p>
            </div>
            
            <!-- Mobile Cards View -->
            <div v-else class="md:hidden divide-y divide-slate-100">
                <div v-for="lead in recent_leads" :key="lead.id" class="p-4 hover:bg-slate-50 transition-colors">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-sm">
                                {{ lead.name?.charAt(0)?.toUpperCase() || '?' }}
                            </div>
                            <div>
                                <div class="font-bold text-brand-dark text-sm">{{ lead.name }}</div>
                                <div class="text-xs text-slate-400">{{ lead.email || lead.phone }}</div>
                            </div>
                        </div>
                        <span class="px-2 py-1 text-xs font-bold rounded-full"
                            :class="{
                                'bg-red-100 text-red-700': lead.status === 'new',
                                'bg-orange-100 text-orange-700': lead.status === 'contacted',
                                'bg-slate-100 text-slate-600': lead.status === 'closed'
                            }">
                            {{ lead.status === 'new' ? 'Nouveau' : (lead.status === 'contacted' ? 'Traité' : 'Clôturé') }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between mt-3">
                        <span class="px-2 py-1 inline-flex text-xs font-semibold rounded-lg" 
                            :class="{
                                'bg-blue-50 text-blue-600': lead.type === 'contact',
                                'bg-green-50 text-green-600': lead.type === 'callback',
                                'bg-purple-50 text-purple-600': lead.type === 'vehicle_inquiry'
                            }">
                            <i class="ph-fill mr-1" :class="{
                                'ph-envelope': lead.type === 'contact',
                                'ph-phone': lead.type === 'callback',
                                'ph-car': lead.type === 'vehicle_inquiry'
                            }"></i>
                            {{ lead.type === 'contact' ? 'Message' : (lead.type === 'callback' ? 'Rappel' : 'Véhicule') }}
                        </span>
                        <span class="text-xs text-slate-400">
                            {{ new Date(lead.created_at).toLocaleDateString('fr-FR') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Desktop Table View -->
            <div v-if="recent_leads && recent_leads.length > 0" class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-100">
                        <tr v-for="lead in recent_leads" :key="lead.id" class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-sm">
                                        {{ lead.name?.charAt(0)?.toUpperCase() || '?' }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-brand-dark">{{ lead.name }}</div>
                                        <div class="text-xs text-slate-400">{{ lead.email || lead.phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1.5 inline-flex text-xs leading-5 font-bold rounded-lg" 
                                    :class="{
                                        'bg-blue-50 text-blue-700': lead.type === 'contact',
                                        'bg-green-50 text-green-700': lead.type === 'callback',
                                        'bg-purple-50 text-purple-700': lead.type === 'vehicle_inquiry'
                                    }">
                                    <i class="ph-fill mr-1.5" :class="{
                                        'ph-envelope': lead.type === 'contact',
                                        'ph-phone': lead.type === 'callback',
                                        'ph-car': lead.type === 'vehicle_inquiry'
                                    }"></i>
                                    {{ lead.type === 'contact' ? 'Message' : (lead.type === 'callback' ? 'Rappel' : 'Véhicule') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-600 font-medium">
                                    {{ new Date(lead.created_at).toLocaleDateString('fr-FR') }}
                                </div>
                                <div class="text-xs text-slate-400">
                                    {{ new Date(lead.created_at).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1.5 inline-flex items-center gap-1.5 text-xs leading-5 font-bold rounded-lg"
                                    :class="{
                                        'bg-red-100 text-red-700': lead.status === 'new',
                                        'bg-orange-100 text-orange-700': lead.status === 'contacted',
                                        'bg-slate-100 text-slate-600': lead.status === 'closed'
                                    }">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="{
                                        'bg-red-500': lead.status === 'new',
                                        'bg-orange-500': lead.status === 'contacted',
                                        'bg-slate-400': lead.status === 'closed'
                                    }"></span>
                                    {{ lead.status === 'new' ? 'Nouveau' : (lead.status === 'contacted' ? 'Traité' : 'Clôturé') }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>