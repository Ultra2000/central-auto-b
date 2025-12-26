<script setup>
import { ref } from 'vue';

const isOpen = ref(false);

const open = () => {
    isOpen.value = true;
};

const close = () => {
    isOpen.value = false;
};

const submitCallback = () => {
    close();
    window.dispatchEvent(new CustomEvent('show-toast', { 
        detail: { message: 'Demande enregistrée ! Nous vous appelons dans quelques minutes 📞' } 
    }));
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
                <p class="text-slate-500 mb-8">Laissez-nous votre numéro, un conseiller vous rappelle immédiatement.</p>
                
                <form @submit.prevent="submitCallback" class="space-y-4">
                    <div class="text-left">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Votre numéro</label>
                        <input type="tel" placeholder="06 12 34 56 78" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all">
                    </div>
                    
                    <div class="text-left">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Créneau préférentiel</label>
                        <select class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all bg-white">
                            <option>Immédiatement</option>
                            <option>Dans 1 heure</option>
                            <option>Ce matin (9h-12h)</option>
                            <option>Cet après-midi (14h-18h)</option>
                            <option>Demain matin</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-brand-orange to-brand-red text-white font-bold py-4 rounded-xl hover:shadow-xl transition-all duration-300 hover:scale-105 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-phone"></i>
                        <span>Demander un rappel</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
