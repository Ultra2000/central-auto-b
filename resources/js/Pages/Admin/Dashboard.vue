<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  ArcElement
} from 'chart.js';
import { Line, Doughnut } from 'vue-chartjs';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  ArcElement
);

const props = defineProps({
    stats: Object,
    charts: Object,
    recent_activities: Array
});

// Chart Options
const lineChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                color: '#f1f5f9'
            }
        },
        x: {
            grid: {
                display: false
            }
        }
    }
};

const doughnutChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                usePointStyle: true,
                padding: 20
            }
        }
    }
};

// Chart Data
const lineChartData = {
    labels: props.charts?.leads_evolution?.labels || [],
    datasets: [{
        label: 'Demandes',
        backgroundColor: '#3b82f6',
        borderColor: '#3b82f6',
        data: props.charts?.leads_evolution?.data || [],
        tension: 0.4,
        fill: true
    }]
};

const doughnutChartData = {
    labels: props.charts?.stock_by_make?.labels || [],
    datasets: [{
        backgroundColor: ['#3b82f6', '#f97316', '#10b981', '#8b5cf6', '#ef4444'],
        data: props.charts?.stock_by_make?.data || []
    }]
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short',
        hour: '2-digit',
        minute: '2-digit'
    });
};
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

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Evolution Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h3 class="font-bold text-lg text-slate-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-trend-up text-brand-blue"></i>
                    Évolution des demandes (30 jours)
                </h3>
                <div class="h-[300px]">
                    <Line :data="lineChartData" :options="lineChartOptions" />
                </div>
            </div>

            <!-- Stock Distribution -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                <h3 class="font-bold text-lg text-slate-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-chart-pie-slice text-brand-orange"></i>
                    Répartition par Marque
                </h3>
                <div class="h-[300px]">
                    <Doughnut :data="doughnutChartData" :options="doughnutChartOptions" />
                </div>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-lg text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-clock-counter-clockwise text-slate-400"></i>
                    Dernières activités
                </h3>
            </div>
            <div class="divide-y divide-slate-100">
                <div v-for="(activity, index) in recent_activities" :key="index" class="p-4 hover:bg-slate-50 transition-colors flex items-center gap-4">
                    <div :class="['w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0', activity.color]">
                        <i :class="['ph-fill text-lg', activity.icon]"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800">{{ activity.title }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ activity.description }}</p>
                    </div>
                    <div class="text-xs text-slate-400 whitespace-nowrap">
                        {{ formatDate(activity.date) }}
                    </div>
                </div>
                <div v-if="recent_activities.length === 0" class="p-8 text-center text-slate-500">
                    Aucune activité récente
                </div>
            </div>
        </div>
    </AdminLayout>
</template>