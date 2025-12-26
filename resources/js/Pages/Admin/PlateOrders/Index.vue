<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineProps({
    orders: Object,
});

const updateStatus = (orderId, newStatus) => {
    if (confirm('Voulez-vous vraiment changer le statut de cette commande ?')) {
        router.patch(route('admin.plate-orders.update-status', orderId), {
            status: newStatus
        }, {
            preserveScroll: true,
        });
    }
};

const getStatusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-800',
        processing: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
    };
    return colors[status] || 'bg-gray-100 text-gray-800';
};

const getStatusLabel = (status) => {
    const labels = {
        pending: 'En attente',
        processing: 'En cours',
        completed: 'Terminée',
        cancelled: 'Annulée',
    };
    return labels[status] || status;
};
</script>

<template>
    <Head title="Commandes de Plaques" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-2xl font-bold text-gray-900">Commandes de Plaques d'Immatriculation</h2>
                    </div>

                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Numéro</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Détails</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="order in orders.data" :key="order.id" class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ order.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="order.plate_type === 'definitive' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 text-xs font-semibold rounded-full">
                                                {{ order.plate_type === 'definitive' ? 'Définitive' : 'Provisoire' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-mono font-bold text-gray-900">{{ order.plate_number }}</div>
                                            <div class="text-xs text-gray-500">Dept: {{ order.department }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ order.customer_name }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ order.customer_email }}</div>
                                            <div class="text-xs text-gray-500">{{ order.customer_phone }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ order.material === 'plexiglass' ? 'Plexiglass' : 'Aluminium' }}</div>
                                            <div class="text-xs text-gray-500">Qté: {{ order.quantity }}</div>
                                            <div v-if="order.custom_text" class="text-xs text-gray-500 italic">{{ order.custom_text }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-gray-900">{{ order.total_price }} €</div>
                                            <div class="text-xs text-gray-500">{{ order.unit_price }}€/u</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span :class="getStatusColor(order.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                                                {{ getStatusLabel(order.status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ new Date(order.created_at).toLocaleDateString('fr-FR') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <select 
                                                @change="updateStatus(order.id, $event.target.value)"
                                                :value="order.status"
                                                class="text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                            >
                                                <option value="pending">En attente</option>
                                                <option value="processing">En cours</option>
                                                <option value="completed">Terminée</option>
                                                <option value="cancelled">Annulée</option>
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="orders.links.length > 3" class="mt-6 flex justify-center">
                            <nav class="flex gap-2">
                                <template v-for="(link, index) in orders.links" :key="index">
                                    <button
                                        v-if="link.url"
                                        @click="router.visit(link.url)"
                                        :class="[
                                            'px-4 py-2 text-sm font-medium rounded-md transition-colors',
                                            link.active 
                                                ? 'bg-blue-600 text-white' 
                                                : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
                                        ]"
                                        v-html="link.label"
                                    />
                                    <span
                                        v-else
                                        class="px-4 py-2 text-sm font-medium text-gray-400"
                                        v-html="link.label"
                                    />
                                </template>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
