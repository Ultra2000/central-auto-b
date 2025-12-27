<script setup>
import MainLayout from '@/Layouts/MainLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({
    orderId: [String, Number]
});
</script>

<template>
    <Head title="Paiement annulé" />
    
    <MainLayout>
        <div class="min-h-screen bg-gradient-to-br from-red-50 via-white to-orange-50 py-16 px-4">
            <div class="max-w-2xl mx-auto">
                <!-- Icône d'annulation -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full mb-4">
                        <i class="ph-bold ph-x-circle text-5xl text-red-600"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">
                        Paiement annulé
                    </h1>
                    <p class="text-lg text-slate-600">
                        Votre commande n'a pas été finalisée
                    </p>
                </div>

                <!-- Carte d'information -->
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                    <div class="flex items-start gap-4 mb-6">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ph-bold ph-warning text-2xl text-orange-600"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900 mb-2">
                                Que s'est-il passé ?
                            </h2>
                            <p class="text-slate-600 mb-4">
                                Vous avez annulé le processus de paiement ou une erreur s'est produite lors de la transaction.
                            </p>
                            <p class="text-sm text-slate-500">
                                <strong>Note:</strong> Aucun montant n'a été débité de votre compte.
                            </p>
                        </div>
                    </div>

                    <div v-if="orderId" class="bg-slate-50 rounded-xl p-4 mb-6">
                        <p class="text-sm text-slate-600">
                            Numéro de commande: <span class="font-semibold text-slate-900">#{{ orderId }}</span>
                        </p>
                        <p class="text-xs text-slate-500 mt-1">
                            La commande a été enregistrée mais n'est pas encore payée.
                        </p>
                    </div>

                    <!-- Raisons possibles -->
                    <div class="border-t border-slate-200 pt-6">
                        <h3 class="font-semibold text-slate-900 mb-3">Raisons possibles :</h3>
                        <ul class="space-y-2 text-sm text-slate-600">
                            <li class="flex items-start gap-2">
                                <i class="ph-bold ph-dot text-brand-blue mt-1"></i>
                                <span>Vous avez cliqué sur le bouton "Retour" ou "Annuler"</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="ph-bold ph-dot text-brand-blue mt-1"></i>
                                <span>Informations de carte bancaire incorrectes</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="ph-bold ph-dot text-brand-blue mt-1"></i>
                                <span>Problème de connexion internet</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="ph-bold ph-dot text-brand-blue mt-1"></i>
                                <span>Session expirée</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Que faire maintenant ? -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                    <h3 class="font-semibold text-blue-900 mb-2 flex items-center gap-2">
                        <i class="ph-bold ph-lightbulb"></i>
                        Que faire maintenant ?
                    </h3>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li class="flex items-start gap-2">
                            <i class="ph-bold ph-check text-blue-600 mt-1"></i>
                            <span>Vérifiez vos informations bancaires et réessayez</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="ph-bold ph-check text-blue-600 mt-1"></i>
                            <span>Contactez votre banque si le problème persiste</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="ph-bold ph-check text-blue-600 mt-1"></i>
                            <span>Essayez un autre mode de paiement</span>
                        </li>
                    </ul>
                </div>

                <!-- Besoin d'aide -->
                <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 mb-6">
                    <div class="flex items-start gap-3">
                        <i class="ph-bold ph-headset text-2xl text-slate-700 mt-1"></i>
                        <div>
                            <h3 class="font-semibold text-slate-900 mb-2">Besoin d'aide ?</h3>
                            <p class="text-sm text-slate-600 mb-3">
                                Notre équipe est là pour vous aider. N'hésitez pas à nous contacter.
                            </p>
                            <a 
                                href="tel:+33123456789"
                                class="inline-flex items-center gap-2 text-brand-blue hover:text-brand-blue/80 font-medium text-sm"
                            >
                                <i class="ph-bold ph-phone"></i>
                                01 23 45 67 89
                            </a>
                            <span class="mx-2 text-slate-400">•</span>
                            <a 
                                href="mailto:contact@centralauto-b.com"
                                class="inline-flex items-center gap-2 text-brand-blue hover:text-brand-blue/80 font-medium text-sm"
                            >
                                <i class="ph-bold ph-envelope"></i>
                                contact@centralauto-b.com
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Boutons d'action -->
                <div class="flex gap-4">
                    <a
                        href="/plaques"
                        class="flex-1 bg-brand-blue text-white px-6 py-3 rounded-xl font-semibold hover:bg-brand-blue/90 transition-colors text-center flex items-center justify-center gap-2"
                    >
                        <i class="ph-bold ph-arrow-counter-clockwise"></i>
                        Réessayer la commande
                    </a>
                    <a
                        href="/"
                        class="flex-1 bg-white border-2 border-slate-200 text-slate-700 px-6 py-3 rounded-xl font-semibold hover:border-brand-blue hover:text-brand-blue transition-colors text-center"
                    >
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
