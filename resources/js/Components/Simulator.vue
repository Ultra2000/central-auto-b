<script setup>
import { ref, computed } from 'vue';

const activeTab = ref('registration');

// Registration State
const cv = ref('');
const region = ref('46.15');
const energy = ref('essence');
const registrationResult = ref(0);

// Financing State
const price = ref('');
const deposit = ref('');
const duration = ref('48');
const rate = ref(4.9);
const financingResult = ref(0);

const calculateRegistration = () => {
    const cvValue = parseFloat(cv.value) || 0;
    const regionPrice = parseFloat(region.value) || 45;
    
    let total = cvValue * regionPrice;
    
    if (energy.value === 'electric') {
        total = 0;
    }
    
    total += 11 + 2.76;
    registrationResult.value = total;
};

const calculateFinancing = () => {
    const priceValue = parseFloat(price.value) || 0;
    const depositValue = parseFloat(deposit.value) || 0;
    const durationValue = parseInt(duration.value) || 48;
    const rateValue = parseFloat(rate.value) || 4.9;
    
    const amount = priceValue - depositValue;
    if (amount <= 0) {
        financingResult.value = 0;
        return;
    }
    
    const monthlyRate = rateValue / 100 / 12;
    const monthlyPayment = (amount * monthlyRate) / (1 - Math.pow(1 + monthlyRate, -durationValue));
    
    financingResult.value = monthlyPayment;
};

const formattedRegistrationResult = computed(() => {
    return registrationResult.value.toFixed(2).replace('.', ',') + ' €';
});

const formattedFinancingResult = computed(() => {
    return financingResult.value.toFixed(2).replace('.', ',') + ' €';
});
</script>

<template>
    <section id="simulator" class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-blue/5 border border-brand-blue/10 text-brand-blue font-semibold text-sm mb-6">
                    <i class="ph-fill ph-calculator"></i>
                    Simulateur
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-brand-dark mb-4">
                    Estimez vos <span class="text-gradient">Frais</span>
                </h2>
                <p class="text-slate-600 max-w-2xl mx-auto text-lg">Calculez le coût de votre carte grise ou simulez votre financement en quelques clics.</p>
            </div>

            <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
                <!-- Tabs -->
                <div class="flex border-b border-slate-100">
                    <button 
                        @click="activeTab = 'registration'"
                        :class="['flex-1 py-6 text-center font-bold text-lg transition-all', activeTab === 'registration' ? 'text-brand-blue border-b-2 border-brand-blue bg-brand-blue/5' : 'text-slate-500 hover:text-brand-blue']"
                    >
                        <i class="ph-bold ph-cardholder mr-2"></i> Carte Grise
                    </button>
                    <button 
                        @click="activeTab = 'financing'"
                        :class="['flex-1 py-6 text-center font-bold text-lg transition-all', activeTab === 'financing' ? 'text-brand-blue border-b-2 border-brand-blue bg-brand-blue/5' : 'text-slate-500 hover:text-brand-blue']"
                    >
                        <i class="ph-bold ph-bank mr-2"></i> Financement
                    </button>
                </div>

                <!-- Registration Simulator -->
                <div v-if="activeTab === 'registration'" class="p-8 md:p-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Puissance Fiscale (CV)</label>
                                <input v-model="cv" type="number" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all" placeholder="Ex: 6">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Région</label>
                                <select v-model="region" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all">
                                    <option value="46.15">Auvergne-Rhône-Alpes</option>
                                    <option value="55">Bourgogne-Franche-Comté</option>
                                    <option value="55">Bretagne</option>
                                    <option value="49.80">Centre-Val de Loire</option>
                                    <option value="27">Corse</option>
                                    <option value="44">Grand Est</option>
                                    <option value="33">Hauts-de-France</option>
                                    <option value="54.95">Île-de-France</option>
                                    <option value="30">Normandie</option>
                                    <option value="51">Nouvelle-Aquitaine</option>
                                    <option value="47">Occitanie</option>
                                    <option value="51.20">Pays de la Loire</option>
                                    <option value="51.20">Provence-Alpes-Côte d'Azur</option>
                                    <option value="30">DOM-TOM</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Énergie</label>
                                <select v-model="energy" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all">
                                    <option value="essence">Essence (ES)</option>
                                    <option value="diesel">Diesel (GO)</option>
                                    <option value="hybrid">Hybride (EE, EH, GL...)</option>
                                    <option value="electric">Électrique (EL)</option>
                                </select>
                            </div>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-8 flex flex-col justify-center items-center text-center border border-slate-100">
                            <div class="w-16 h-16 bg-brand-blue/10 rounded-full flex items-center justify-center text-brand-blue text-3xl mb-4">
                                <i class="ph-fill ph-receipt"></i>
                            </div>
                            <span class="text-slate-500 font-medium mb-2">Coût estimé de la carte grise</span>
                            <span class="text-4xl font-extrabold text-brand-dark">{{ formattedRegistrationResult }}</span>
                            <p class="text-xs text-slate-400 mt-4">*Estimation hors frais de dossier éventuels et malus écologique.</p>
                            <button @click="calculateRegistration" class="mt-6 w-full bg-brand-blue text-white font-bold py-3 rounded-xl hover:bg-brand-dark transition-all shadow-lg shadow-brand-blue/20">
                                Calculer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Financing Simulator -->
                <div v-if="activeTab === 'financing'" class="p-8 md:p-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Prix du véhicule (€)</label>
                                <input v-model="price" type="number" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all" placeholder="Ex: 15000">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Apport (€)</label>
                                <input v-model="deposit" type="number" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all" placeholder="Ex: 3000">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Durée (mois)</label>
                                    <select v-model="duration" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all">
                                        <option value="12">12 mois</option>
                                        <option value="24">24 mois</option>
                                        <option value="36">36 mois</option>
                                        <option value="48">48 mois</option>
                                        <option value="60">60 mois</option>
                                        <option value="72">72 mois</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">TAEG (%)</label>
                                    <input v-model="rate" type="number" step="0.1" class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all">
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-8 flex flex-col justify-center items-center text-center border border-slate-100">
                            <div class="w-16 h-16 bg-brand-orange/10 rounded-full flex items-center justify-center text-brand-orange text-3xl mb-4">
                                <i class="ph-fill ph-coins"></i>
                            </div>
                            <span class="text-slate-500 font-medium mb-2">Mensualité estimée</span>
                            <span class="text-4xl font-extrabold text-brand-dark">{{ formattedFinancingResult }}</span>
                            <span class="text-sm text-slate-500 font-semibold mt-1">/ mois</span>
                            <p class="text-xs text-slate-400 mt-4">*Un crédit vous engage et doit être remboursé. Vérifiez vos capacités de remboursement avant de vous engager.</p>
                            <button @click="calculateFinancing" class="mt-6 w-full bg-brand-orange text-white font-bold py-3 rounded-xl hover:bg-brand-dark transition-all shadow-lg shadow-brand-orange/20">
                                Calculer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
