<script setup>
import { ref, onMounted } from 'vue';

const isOpen = ref(false);
const hasBeenShown = ref(false);

const close = () => {
    isOpen.value = false;
};

const submitOffer = () => {
    close();
    window.dispatchEvent(new CustomEvent('show-toast', { 
        detail: { message: 'Offre activée ! Nous vous contactons sous 24h 🎉' } 
    }));
};

onMounted(() => {
    // Check localStorage
    const lastShown = localStorage.getItem('exitModalShownTime');
    if (lastShown) {
        const timeSince = new Date().getTime() - parseInt(lastShown);
        if (timeSince < 86400000) { // 24h
            hasBeenShown.value = true;
        }
    }

    // Mouse leave detection
    document.addEventListener('mouseleave', (e) => {
        if (e.clientY <= 0 && !hasBeenShown.value && !isOpen.value) {
            openModal();
        }
    });

    // Timer fallback (60s)
    setTimeout(() => {
        if (!hasBeenShown.value && !isOpen.value) {
            openModal();
        }
    }, 60000);
});

const openModal = () => {
    isOpen.value = true;
    hasBeenShown.value = true;
    localStorage.setItem('exitModalShownTime', new Date().getTime().toString());
};
</script>

<template>
    <div v-if="isOpen" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click.self="close">
        <div class="bg-white rounded-3xl max-w-lg w-full overflow-hidden shadow-2xl transform transition-all scale-100 opacity-100 relative">
            <button @click="close" class="absolute top-4 right-4 w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-200 transition-colors z-10">
                <i class="ph-bold ph-x"></i>
            </button>
            
            <div class="relative h-48 bg-brand-dark overflow-hidden">
                <img src="https://images.unsplash.com/photo-1560958089-b8a1929cea89?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Offre spéciale" class="w-full h-full object-cover opacity-60">
                <div class="absolute inset-0 bg-gradient-to-t from-brand-dark to-transparent"></div>
                <div class="absolute bottom-6 left-8">
                    <span class="bg-brand-orange text-white text-xs font-bold px-3 py-1 rounded-full mb-2 inline-block">OFFRE LIMITÉE</span>
                    <h3 class="text-3xl font-bold text-white">Attendez !</h3>
                </div>
            </div>
            
            <div class="p-8">
                <p class="text-slate-600 text-lg mb-6">
                    Ne partez pas sans votre offre de bienvenue. Bénéficiez de <span class="font-bold text-brand-dark">500€ de remise</span> sur votre premier achat ou <span class="font-bold text-brand-dark">1 mois offert</span> sur votre location.
                </p>
                
                <form @submit.prevent="submitOffer" class="space-y-4">
                    <input type="email" placeholder="Votre email" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all">
                    <button type="submit" class="w-full bg-brand-gradient text-white font-bold py-4 rounded-xl hover:shadow-lg hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                        <span>Profiter de l'offre</span>
                        <i class="ph-bold ph-gift"></i>
                    </button>
                </form>
                
                <button @click="close" class="w-full text-center text-slate-400 text-sm mt-4 hover:text-slate-600">
                    Non merci, je ne veux pas économiser
                </button>
            </div>
        </div>
    </div>
</template>
