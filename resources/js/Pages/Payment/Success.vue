<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    order: Object
});
</script>

<template>
    <Head title="Paiement réussi" />
    
    <MainLayout>
        <div class="min-h-screen bg-gradient-to-br from-green-50 via-white to-blue-50 py-16 px-4">
            <div class="max-w-2xl mx-auto">
                <!-- Icône de succès -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-4">
                        <i class="ph-bold ph-check-circle text-5xl text-green-600"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">
                        Paiement réussi !
                    </h1>
                    <p class="text-lg text-slate-600">
                        Votre commande a été confirmée
                    </p>
                </div>

                <!-- Carte de confirmation -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <div class="border-b border-slate-200 pb-6 mb-6">
                        <h2 class="text-xl font-semibold text-slate-900 mb-4">
                            Détails de la commande
                        </h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Numéro de commande</p>
                                <p class="font-semibold text-slate-900">#{{ order.id }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500 mb-1">Statut</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="ph-bold ph-check mr-1"></i>
                                    Payé
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-brand-blue/10 rounded-lg flex items-center justify-center">
                                <i class="ph-bold ph-identification-card text-2xl text-brand-blue"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-slate-900">
                                    Plaque {{ order.plate_type === 'definitive' ? 'définitive' : 'provisoire' }}
                                </p>
                                <p class="text-sm text-slate-600">
                                    {{ order.plate_number }} - {{ order.material === 'plexiglass' ? 'Plexiglass' : 'Aluminium' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-slate-900">{{ order.total_price }}€</p>
                                <p class="text-sm text-slate-600">Quantité: {{ order.quantity }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-200">
                        <div class="flex justify-between items-center text-lg font-bold">
                            <span class="text-slate-900">Total payé</span>
                            <span class="text-brand-blue">{{ order.total_price }}€</span>
                        </div>
                    </div>
                </div>

                <!-- Informations de livraison -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">
                        <i class="ph-bold ph-truck mr-2"></i>
                        Mode de récupération
                    </h3>
                    <div v-if="order.delivery_option === 'pickup'" class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ph-bold ph-storefront text-xl text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-900 mb-1">Retrait en magasin</p>
                            <p class="text-sm text-slate-600">
                                Votre commande sera prête sous 2-3 jours ouvrés.<br>
                                Nous vous contacterons par email dès qu'elle sera disponible.
                            </p>
                        </div>
                    </div>
                    <div v-else class="flex items-start gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ph-bold ph-package text-xl text-green-600"></i>
                        </div>
                        <div>
                            <p class="font-medium text-slate-900 mb-1">Livraison à domicile</p>
                            <p class="text-sm text-slate-600 mb-2">
                                {{ order.delivery_address }}<br>
                                {{ order.delivery_postal_code }} {{ order.delivery_city }}
                            </p>
                            <p class="text-sm text-slate-500">
                                Délai de livraison estimé: 3-5 jours ouvrés
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Email de confirmation -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                    <div class="flex items-start gap-3">
                        <i class="ph-bold ph-envelope-simple text-2xl text-blue-600 mt-1"></i>
                        <div>
                            <p class="font-medium text-blue-900 mb-1">Email de confirmation envoyé</p>
                            <p class="text-sm text-blue-700">
                                Un récapitulatif de votre commande a été envoyé à <strong>{{ order.customer_email }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex gap-4">
                    <a
                        href="/"
                        class="flex-1 bg-brand-blue text-white px-6 py-3 rounded-xl font-semibold hover:bg-brand-blue/90 transition-colors text-center"
                    >
                        Retour à l'accueil
                    </a>
                    <a
                        href="/plaques"
                        class="flex-1 bg-white border-2 border-slate-200 text-slate-700 px-6 py-3 rounded-xl font-semibold hover:border-brand-blue hover:text-brand-blue transition-colors text-center"
                    >
                        Commander d'autres plaques
                    </a>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
