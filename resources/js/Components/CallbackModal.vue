<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const isOpen = ref(false);
const showSuccess = ref(false);

const form = useForm({
    name: '',
    phone: '',
    email: '',
    message: '',
    type: 'callback'
});

const open = () => {
    isOpen.value = true;
};

const close = () => {
    isOpen.value = false;
    showSuccess.value = false;
};

const submitCallback = () => {
    form.post(route('leads.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showSuccess.value = true;
            setTimeout(() => {
                form.reset();
                showSuccess.value = false;
                close();
            }, 2500);
        }
    });
};

// Expose open method
defineExpose({ open });
</script>

<template>
    <div v-if="isOpen" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click.self="close">
        <div class="bg-white rounded-3xl max-w-md w-full overflow-hidden shadow-2xl transform transition-all scale-100 opacity-100 relative">
            <button @click="close" class="absolute top-4 right-4 w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors z-10">
                <i class="ph-bold ph-x"></i>
            </button>
            
            <div class="p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-green-600 text-3xl mx-auto mb-6">
                    <i class="ph-fill ph-phone-call"></i>
                </div>
                
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Rappel Gratuit</h3>
                <p class="text-slate-500 mb-8">Laissez-nous vos coordonnées, un conseiller vous rappelle rapidement.</p>
                
                <!-- Success Message -->
                <div v-if="showSuccess" class="mb-6 p-4 bg-green-50 border-2 border-green-200 rounded-xl flex items-center gap-3 text-green-700 animate-fade-in">
                    <i class="ph-fill ph-check-circle text-2xl"></i>
                    <p class="font-medium">Demande enregistrée ! Nous vous appelons très bientôt.</p>
                </div>

                <form @submit.prevent="submitCallback" class="space-y-4">
                    <div class="text-left">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nom complet *</label>
                        <input 
                            type="text" 
                            v-model="form.name"
                            placeholder="Jean Dupont" 
                            required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                        >
                    </div>

                    <div class="text-left">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Téléphone *</label>
                        <input 
                            type="tel" 
                            v-model="form.phone"
                            placeholder="06 12 34 56 78" 
                            required 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                        >
                    </div>

                    <div class="text-left">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Email (optionnel)</label>
                        <input 
                            type="email" 
                            v-model="form.email"
                            placeholder="jean.dupont@email.com" 
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                        >
                    </div>
                    
                    <div class="text-left">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Créneau préférentiel</label>
                        <select 
                            v-model="form.message"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all bg-white"
                        >
                            <option value="Immédiatement">Immédiatement</option>
                            <option value="Dans 1 heure">Dans 1 heure</option>
                            <option value="Ce matin (9h-12h)">Ce matin (9h-12h)</option>
                            <option value="Cet après-midi (14h-18h)">Cet après-midi (14h-18h)</option>
                            <option value="Demain matin">Demain matin</option>
                        </select>
                    </div>

                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-r from-brand-orange to-brand-red text-white font-bold py-4 rounded-xl hover:shadow-xl transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed disabled:scale-100"
                    >
                        <i class="ph-bold ph-phone"></i>
                        <span>{{ form.processing ? 'Envoi...' : 'Demander un rappel' }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
