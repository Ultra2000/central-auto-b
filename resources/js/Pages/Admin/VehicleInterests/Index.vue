<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    interests: Object,
    reservations: Array
});

const viewMode = ref('list'); // 'list' or 'calendar'
const currentMonth = ref(new Date());

const notification = ref({
    show: false,
    type: 'success',
    message: ''
});

// Calendar Logic
const calendarDays = computed(() => {
    const year = currentMonth.value.getFullYear();
    const month = currentMonth.value.getMonth();
    
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    
    const days = [];
    
    // Padding for days before first day of month (Monday start)
    let firstDayOfWeek = firstDay.getDay() || 7; // 1 (Mon) to 7 (Sun)
    for (let i = 1; i < firstDayOfWeek; i++) {
        days.push({ date: null, events: [] });
    }
    
    // Days of the month
    for (let i = 1; i <= lastDay.getDate(); i++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        const dayEvents = props.reservations.filter(r => r.date === dateStr);
        
        days.push({
            date: i,
            fullDate: dateStr,
            events: dayEvents,
            isToday: new Date().toISOString().split('T')[0] === dateStr
        });
    }
    
    return days;
});

const monthLabel = computed(() => {
    return currentMonth.value.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });
});

const prevMonth = () => {
    currentMonth.value = new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() - 1, 1);
};

const nextMonth = () => {
    currentMonth.value = new Date(currentMonth.value.getFullYear(), currentMonth.value.getMonth() + 1, 1);
};

const showNotification = (type, message) => {
    notification.value = {
        show: true,
        type,
        message
    };
    
    setTimeout(() => {
        notification.value.show = false;
    }, 5000);
};

const closeNotification = () => {
    notification.value.show = false;
};

const updateStatus = (interestId, newStatus) => {
    router.patch(route('admin.vehicle-interests.update-status', interestId), {
        status: newStatus
    }, {
        preserveScroll: true,
        onSuccess: () => {
            showNotification('success', 'Statut mis à jour avec succès');
        },
        onError: () => {
            showNotification('error', 'Erreur lors de la mise à jour du statut');
        }
    });
};

const getStatusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-700',
        contacted: 'bg-blue-100 text-blue-700',
        completed: 'bg-green-100 text-green-700',
        cancelled: 'bg-red-100 text-red-700'
    };
    return colors[status] || 'bg-slate-100 text-slate-700';
};

const getStatusLabel = (status) => {
    const labels = {
        pending: 'En attente',
        contacted: 'Contacté',
        completed: 'Terminé',
        cancelled: 'Annulé'
    };
    return labels[status] || status;
};

const getTypeLabel = (type) => {
    return type === 'callback' ? 'Rappel' : 'Réservation';
};

const getTypeColor = (type) => {
    return type === 'callback' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700';
};

const formatDate = (datetime) => {
    if (!datetime) return '-';
    const date = new Date(datetime);
    return date.toLocaleDateString('fr-FR', { 
        day: '2-digit', 
        month: '2-digit', 
        year: 'numeric' 
    });
};

const formatTime = (time) => {
    if (!time) return '-';
    return time.substring(0, 5);
};
</script>

<template>
    <AdminLayout>
        <div class="container mx-auto p-6">
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold text-brand-dark mb-2">
                        <i class="ph-bold ph-calendar-check mr-2 text-brand-blue"></i>
                        Demandes Véhicules
                    </h1>
                    <p class="text-slate-600">Gérer les demandes de rappel et réservations</p>
                </div>
                
                <div class="flex bg-slate-100 p-1 rounded-xl">
                    <button 
                        @click="viewMode = 'list'"
                        :class="[
                            'px-4 py-2 rounded-lg text-sm font-bold transition-all flex items-center gap-2',
                            viewMode === 'list' ? 'bg-white text-brand-blue shadow-sm' : 'text-slate-500 hover:text-slate-700'
                        ]"
                    >
                        <i class="ph-bold ph-list"></i>
                        Liste
                    </button>
                    <button 
                        @click="viewMode = 'calendar'"
                        :class="[
                            'px-4 py-2 rounded-lg text-sm font-bold transition-all flex items-center gap-2',
                            viewMode === 'calendar' ? 'bg-white text-brand-blue shadow-sm' : 'text-slate-500 hover:text-slate-700'
                        ]"
                    >
                        <i class="ph-bold ph-calendar"></i>
                        Calendrier
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div v-if="viewMode === 'list'" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500 font-semibold">Total</p>
                            <p class="text-3xl font-extrabold text-brand-dark">{{ interests.total }}</p>
                        </div>
                        <div class="w-14 h-14 bg-brand-blue/10 rounded-xl flex items-center justify-center">
                            <i class="ph-fill ph-list-checks text-brand-blue text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500 font-semibold">En attente</p>
                            <p class="text-3xl font-extrabold text-yellow-600">
                                {{ interests.data.filter(i => i.status === 'pending').length }}
                            </p>
                        </div>
                        <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <i class="ph-fill ph-clock text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500 font-semibold">Contactés</p>
                            <p class="text-3xl font-extrabold text-blue-600">
                                {{ interests.data.filter(i => i.status === 'contacted').length }}
                            </p>
                        </div>
                        <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="ph-fill ph-phone text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500 font-semibold">Terminés</p>
                            <p class="text-3xl font-extrabold text-green-600">
                                {{ interests.data.filter(i => i.status === 'completed').length }}
                            </p>
                        </div>
                        <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="ph-fill ph-check-circle text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar View -->
            <div v-if="viewMode === 'calendar'" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
                <!-- Calendar Header -->
                <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-800 capitalize">{{ monthLabel }}</h2>
                    <div class="flex gap-2">
                        <button @click="prevMonth" class="w-10 h-10 rounded-xl bg-slate-50 hover:bg-slate-100 flex items-center justify-center transition-colors">
                            <i class="ph-bold ph-caret-left"></i>
                        </button>
                        <button @click="nextMonth" class="w-10 h-10 rounded-xl bg-slate-50 hover:bg-slate-100 flex items-center justify-center transition-colors">
                            <i class="ph-bold ph-caret-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Calendar Grid -->
                <div class="p-6">
                    <!-- Weekdays -->
                    <div class="grid grid-cols-7 mb-4">
                        <div v-for="day in ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim']" :key="day" class="text-center text-sm font-bold text-slate-400 uppercase">
                            {{ day }}
                        </div>
                    </div>

                    <!-- Days -->
                    <div class="grid grid-cols-7 gap-4">
                        <div v-for="(day, index) in calendarDays" :key="index" 
                            :class="[
                                'min-h-[120px] rounded-xl border p-2 transition-all',
                                day.date ? 'bg-white border-slate-100 hover:border-brand-blue/30' : 'bg-slate-50/50 border-transparent'
                            ]"
                        >
                            <div v-if="day.date">
                                <div class="flex justify-between items-start mb-2">
                                    <span :class="[
                                        'w-7 h-7 flex items-center justify-center rounded-full text-sm font-bold',
                                        day.isToday ? 'bg-brand-blue text-white' : 'text-slate-700'
                                    ]">
                                        {{ day.date }}
                                    </span>
                                    <span v-if="day.events.length > 0" class="text-xs font-bold text-brand-blue bg-brand-blue/10 px-2 py-0.5 rounded-full">
                                        {{ day.events.length }}
                                    </span>
                                </div>

                                <div class="space-y-1">
                                    <div v-for="event in day.events" :key="event.id" 
                                        class="text-xs p-1.5 rounded-lg bg-orange-50 border border-orange-100 hover:bg-orange-100 transition-colors cursor-pointer group relative"
                                    >
                                        <div class="font-bold text-orange-800 truncate">{{ formatTime(event.time) }} - {{ event.title }}</div>
                                        <div class="text-orange-600 truncate text-[10px]">{{ event.vehicle?.make }} {{ event.vehicle?.model }}</div>
                                        
                                        <!-- Tooltip -->
                                        <div class="absolute z-10 bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 bg-slate-800 text-white text-xs p-2 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                                            <p class="font-bold">{{ event.title }}</p>
                                            <p>{{ event.phone }}</p>
                                            <p class="mt-1 text-slate-300">{{ event.vehicle?.make }} {{ event.vehicle?.model }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Interests Table -->
            <div v-if="viewMode === 'list'" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div v-if="interests.data.length === 0" class="p-12 text-center">
                    <div class="w-24 h-24 bg-slate-100 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="ph-duotone ph-calendar-x text-slate-400 text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700 mb-2">Aucune demande</h3>
                    <p class="text-slate-500">Les demandes d'intérêt apparaîtront ici</p>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Véhicule</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Date/Heure</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Message</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-slate-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr v-for="interest in interests.data" :key="interest.id" class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <img 
                                            v-if="interest.vehicle?.main_image" 
                                            :src="interest.vehicle.main_image" 
                                            :alt="interest.vehicle.make" 
                                            class="w-16 h-12 object-cover rounded-lg"
                                        >
                                        <div class="w-16 h-12 bg-slate-200 rounded-lg flex items-center justify-center" v-else>
                                            <i class="ph-fill ph-car text-slate-400"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-brand-dark">{{ interest.vehicle?.make }} {{ interest.vehicle?.model }}</p>
                                            <p class="text-xs text-slate-500">{{ interest.vehicle?.year }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-bold text-slate-800">{{ interest.name }}</p>
                                        <p class="text-sm text-slate-500">
                                            <i class="ph-bold ph-envelope text-xs mr-1"></i>{{ interest.email }}
                                        </p>
                                        <p class="text-sm text-slate-500">
                                            <i class="ph-bold ph-phone text-xs mr-1"></i>{{ interest.phone }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="getTypeColor(interest.interest_type)" class="px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1 w-fit">
                                        <i :class="interest.interest_type === 'callback' ? 'ph-bold ph-phone' : 'ph-bold ph-calendar-check'"></i>
                                        {{ getTypeLabel(interest.interest_type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        <p class="font-semibold text-slate-700">{{ formatDate(interest.preferred_date) }}</p>
                                        <p class="text-slate-500">{{ formatTime(interest.preferred_time) }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-slate-600 max-w-xs truncate" :title="interest.message">
                                        {{ interest.message || '-' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4">
                                    <span :class="getStatusColor(interest.status)" class="px-3 py-1 rounded-full text-xs font-bold">
                                        {{ getStatusLabel(interest.status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex gap-2">
                                        <button 
                                            v-if="interest.status === 'pending'"
                                            @click="updateStatus(interest.id, 'contacted')" 
                                            class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-xs font-bold transition-colors flex items-center gap-1"
                                            title="Marquer comme contacté"
                                        >
                                            <i class="ph-bold ph-phone"></i>
                                            Contacter
                                        </button>
                                        <button 
                                            v-if="interest.status === 'contacted'"
                                            @click="updateStatus(interest.id, 'completed')" 
                                            class="px-3 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg text-xs font-bold transition-colors flex items-center gap-1"
                                            title="Marquer comme terminé"
                                        >
                                            <i class="ph-bold ph-check"></i>
                                            Terminer
                                        </button>
                                        <button 
                                            v-if="['pending', 'contacted'].includes(interest.status)"
                                            @click="updateStatus(interest.id, 'cancelled')" 
                                            class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-bold transition-colors"
                                            title="Annuler"
                                        >
                                            <i class="ph-bold ph-x"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="interests.data.length > 0" class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-slate-600">
                            Affichage de {{ interests.from }} à {{ interests.to }} sur {{ interests.total }} demandes
                        </p>
                        <div class="flex gap-2">
                            <a 
                                v-for="link in interests.links" 
                                :key="link.label"
                                :href="link.url"
                                :class="[
                                    'px-4 py-2 rounded-lg text-sm font-bold transition-all',
                                    link.active ? 'bg-brand-blue text-white' : 'bg-white text-slate-700 hover:bg-slate-100',
                                    !link.url ? 'opacity-50 cursor-not-allowed' : ''
                                ]"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Toast -->
        <Transition name="slide-down">
            <div v-if="notification.show" class="fixed top-6 right-6 z-50 max-w-md w-full">
                <div :class="[
                    'rounded-2xl shadow-2xl p-6 flex items-start gap-4 border-2',
                    notification.type === 'success' ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500'
                ]">
                    <div :class="[
                        'w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0',
                        notification.type === 'success' ? 'bg-green-500' : 'bg-red-500'
                    ]">
                        <i v-if="notification.type === 'success'" class="ph-bold ph-check text-white text-xl"></i>
                        <i v-else class="ph-bold ph-x text-white text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 :class="[
                            'font-bold mb-1',
                            notification.type === 'success' ? 'text-green-900' : 'text-red-900'
                        ]">
                            {{ notification.type === 'success' ? 'Succès' : 'Erreur' }}
                        </h4>
                        <p :class="[
                            'text-sm',
                            notification.type === 'success' ? 'text-green-700' : 'text-red-700'
                        ]">
                            {{ notification.message }}
                        </p>
                    </div>
                    <button 
                        @click="closeNotification"
                        :class="[
                            'w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors',
                            notification.type === 'success' ? 'hover:bg-green-200 text-green-700' : 'hover:bg-red-200 text-red-700'
                        ]"
                    >
                        <i class="ph-bold ph-x"></i>
                    </button>
                </div>
            </div>
        </Transition>
    </AdminLayout>
</template>

<style scoped>
.slide-down-enter-active,
.slide-down-leave-active {
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.slide-down-enter-from {
    opacity: 0;
    transform: translateY(-100px) scale(0.9);
}

.slide-down-leave-to {
    opacity: 0;
    transform: translateX(100px) scale(0.9);
}
</style>
