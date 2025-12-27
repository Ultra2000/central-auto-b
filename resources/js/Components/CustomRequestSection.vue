<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const currentStep = ref(1);

const notification = ref({
    show: false,
    type: 'success',
    message: ''
});

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

const form = useForm({
    type: 'achat',
    category: '',
    budget: '',
    brand: '',
    model: '',
    transmission: '',
    name: '',
    email: '',
    phone: '',
    message: ''
});

const nextStep = () => {
    if (currentStep.value < 3) currentStep.value++;
};

const prevStep = () => {
    if (currentStep.value > 1) currentStep.value--;
};

const submit = () => {
    form.post(route('custom-requests.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            currentStep.value = 1;
            showNotification('success', 'Votre demande a été envoyée avec succès ! Nous vous contacterons rapidement.');
        },
        onError: (errors) => {
            console.log('Validation errors:', errors);
            const firstError = Object.values(errors)[0];
            showNotification('error', firstError || 'Une erreur est survenue lors de l\'envoi de votre demande. Veuillez vérifier les champs et réessayer.');
        },
    });
};
</script>

<template>
    <section id="custom-request" class="py-28 relative overflow-hidden">
        <!-- Background Gradient moderne -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-brand-dark z-0"></div>
        
        <!-- Decorative blobs améliorés -->
        <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-brand-blue/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 float-animation"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-brand-orange/15 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 float-animation" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 w-[400px] h-[400px] bg-brand-red/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <div class="inline-flex items-center mb-6 px-6 py-2.5 rounded-full border border-brand-orange/30 bg-white/5 backdrop-blur-sm">
                    <span class="w-2 h-2 bg-brand-orange rounded-full mr-3 animate-pulse"></span>
                    <span class="text-brand-orange font-bold tracking-widest uppercase text-sm">Service Premium</span>
                </div>
                <h2 class="text-4xl md:text-6xl font-extrabold mt-6 mb-6 text-white">
                    Configurateur de <span class="text-gradient">Demande</span>
                </h2>
                <p class="text-slate-300 max-w-2xl mx-auto text-lg leading-relaxed">
                    Dites-nous tout. Que ce soit pour un achat ou une location, nous activons notre réseau pour satisfaire votre demande.
                </p>
            </div>

            <div class="bg-white/5 backdrop-blur-2xl border-2 border-white/10 rounded-3xl p-8 md:p-12 shadow-2xl">
                
                <!-- Barre de progression -->
                <div class="flex items-center justify-between mb-12">
                    <div class="flex-1 flex items-center justify-between relative">
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg mb-2 shadow-lg transition-all"
                                :class="currentStep >= 1 ? 'bg-brand-orange text-white' : 'bg-slate-700 text-slate-400'">
                                1
                            </div>
                            <span class="text-xs font-semibold" :class="currentStep >= 1 ? 'text-white' : 'text-slate-400'">Type</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg mb-2 transition-all"
                                :class="currentStep >= 2 ? 'bg-brand-orange text-white' : 'bg-slate-700 text-slate-400'">
                                2
                            </div>
                            <span class="text-xs font-semibold" :class="currentStep >= 2 ? 'text-white' : 'text-slate-400'">Critères</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg mb-2 transition-all"
                                :class="currentStep >= 3 ? 'bg-brand-orange text-white' : 'bg-slate-700 text-slate-400'">
                                3
                            </div>
                            <span class="text-xs font-semibold" :class="currentStep >= 3 ? 'text-white' : 'text-slate-400'">Coordonnées</span>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="submit">
                    
                    <!-- Étape 1: Type de demande -->
                    <div v-show="currentStep === 1">
                        <h3 class="text-2xl font-bold text-white mb-6 text-center">Quel est votre projet ?</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                            <label class="cursor-pointer group">
                                <input type="radio" v-model="form.type" value="achat" class="peer sr-only">
                                <div class="h-full p-6 rounded-2xl bg-slate-800/50 border-2 border-transparent hover:border-brand-orange/50 peer-checked:border-brand-orange peer-checked:bg-slate-800 transition-all flex flex-col items-center justify-center text-center">
                                    <div class="w-16 h-16 rounded-full bg-brand-orange/20 text-brand-orange flex items-center justify-center mb-4 text-3xl peer-checked:bg-brand-orange peer-checked:text-white transition-colors">
                                        <i class="ph-fill ph-currency-eur"></i>
                                    </div>
                                    <span class="text-xl font-bold text-white mb-2">Achat</span>
                                    <span class="text-sm text-slate-400">Neuf ou Occasion</span>
                                </div>
                            </label>

                            <label class="cursor-pointer group">
                                <input type="radio" v-model="form.type" value="location" class="peer sr-only">
                                <div class="h-full p-6 rounded-2xl bg-slate-800/50 border-2 border-transparent hover:border-brand-blue/50 peer-checked:border-brand-blue peer-checked:bg-slate-800 transition-all flex flex-col items-center justify-center text-center">
                                    <div class="w-16 h-16 rounded-full bg-brand-blue/20 text-brand-blue flex items-center justify-center mb-4 text-3xl peer-checked:bg-brand-blue peer-checked:text-white transition-colors">
                                        <i class="ph-fill ph-calendar-check"></i>
                                    </div>
                                    <span class="text-xl font-bold text-white mb-2">Location</span>
                                    <span class="text-sm text-slate-400">Courte ou Longue durée</span>
                                </div>
                            </label>

                            <label class="cursor-pointer group">
                                <input type="radio" v-model="form.type" value="vente" class="peer sr-only">
                                <div class="h-full p-6 rounded-2xl bg-slate-800/50 border-2 border-transparent hover:border-brand-red/50 peer-checked:border-brand-red peer-checked:bg-slate-800 transition-all flex flex-col items-center justify-center text-center">
                                    <div class="w-16 h-16 rounded-full bg-brand-red/20 text-brand-red flex items-center justify-center mb-4 text-3xl peer-checked:bg-brand-red peer-checked:text-white transition-colors">
                                        <i class="ph-fill ph-car-profile"></i>
                                    </div>
                                    <span class="text-xl font-bold text-white mb-2">Vente / Reprise</span>
                                    <span class="text-sm text-slate-400">Estimation gratuite</span>
                                </div>
                            </label>
                        </div>
                        
                        <button type="button" @click="nextStep" class="w-full bg-brand-gradient hover:opacity-90 text-white text-lg font-bold py-5 rounded-2xl transition-all shadow-xl flex items-center justify-center gap-3">
                            Continuer
                            <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>

                    <!-- Étape 2: Critères -->
                    <div v-show="currentStep === 2">
                        <h3 class="text-2xl font-bold text-white mb-6 text-center">Vos critères de recherche</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-sm font-semibold text-slate-300 mb-2">Budget Max / Loyer</label>
                                <input v-model="form.budget" type="text" placeholder="Ex: 25000" class="w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl px-4 py-3.5 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors placeholder-slate-600">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-300 mb-2">Marque</label>
                                <select v-model="form.brand" class="w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl px-4 py-3.5 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue appearance-none transition-colors">
                                    <option value="">Sélectionner une marque</option>
                                    <option>Peugeot</option>
                                    <option>Renault</option>
                                    <option>Citroën</option>
                                    <option>Volkswagen</option>
                                    <option>BMW</option>
                                    <option>Mercedes</option>
                                    <option>Audi</option>
                                    <option>Toyota</option>
                                    <option>Autre</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-300 mb-2">Modèle</label>
                                <input v-model="form.model" type="text" placeholder="Ex: Clio, A3, 3008..." class="w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl px-4 py-3.5 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue transition-colors placeholder-slate-600">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-300 mb-2">Boîte de vitesse</label>
                                <select v-model="form.transmission" class="w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl px-4 py-3.5 focus:outline-none focus:border-brand-blue focus:ring-1 focus:ring-brand-blue appearance-none transition-colors">
                                    <option>Peu importe</option>
                                    <option>Automatique</option>
                                    <option>Manuelle</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" @click="prevStep" class="bg-slate-700 hover:bg-slate-600 text-white font-bold py-5 rounded-2xl transition-all flex items-center justify-center gap-2">
                                <i class="ph-bold ph-arrow-left"></i>
                                Retour
                            </button>
                            <button type="button" @click="nextStep" class="bg-brand-gradient hover:opacity-90 text-white font-bold py-5 rounded-2xl transition-all flex items-center justify-center gap-2">
                                Continuer
                                <i class="ph-bold ph-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Étape 3: Coordonnées -->
                    <div v-show="currentStep === 3">
                        <h3 class="text-2xl font-bold text-white mb-6 text-center">Vos coordonnées</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div>
                                <input v-model="form.name" type="text" required placeholder="Nom complet" class="w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl px-4 py-3.5 focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange placeholder-slate-500">
                            </div>
                            <div>
                                <input v-model="form.email" type="email" required placeholder="Email" class="w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl px-4 py-3.5 focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange placeholder-slate-500">
                            </div>
                            <div>
                                <input v-model="form.phone" type="tel" required placeholder="Téléphone" class="w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl px-4 py-3.5 focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange placeholder-slate-500">
                            </div>
                            <div class="md:col-span-3">
                                <textarea v-model="form.message" rows="3" class="w-full bg-slate-900/50 border border-slate-700 text-white rounded-xl px-4 py-3.5 focus:outline-none focus:border-brand-orange focus:ring-1 focus:ring-brand-orange placeholder-slate-500" placeholder="Précisions supplémentaires (Couleur, Kilométrage max, options...)"></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <button type="button" @click="prevStep" class="bg-slate-700 hover:bg-slate-600 text-white font-bold py-5 rounded-2xl transition-all flex items-center justify-center gap-2">
                                <i class="ph-bold ph-arrow-left"></i>
                                Retour
                            </button>
                            <button type="submit" :disabled="form.processing" class="bg-brand-gradient hover:opacity-90 text-white text-lg font-bold py-5 rounded-2xl transition-all shadow-2xl shadow-brand-red/40 flex items-center justify-center gap-3 transform hover:-translate-y-1 btn-glow relative overflow-hidden disabled:opacity-50">
                                <span class="relative z-10">{{ form.processing ? 'Envoi...' : 'Envoyer' }}</span>
                                <i class="ph-bold ph-paper-plane-right text-xl relative z-10"></i>
                            </button>
                        </div>
                        <p class="text-center text-slate-400 text-sm">
                            <i class="ph-fill ph-lock-simple text-brand-orange"></i> Vos données sont sécurisées et confidentielles
                        </p>
                    </div>
                </form>
            </div>
        </div>

        <!-- Notification Toast -->
        <Transition name="slide-down">
            <div v-if="notification.show" class="fixed top-6 right-6 z-[60] max-w-md w-full">
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
                            {{ notification.type === 'success' ? 'Demande envoyée' : 'Erreur' }}
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
    </section>
</template>

<style scoped>
/* Notification Transitions */
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
