<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const isVisible = ref(false);

const checkScroll = () => {
    isVisible.value = window.scrollY > 300;
};

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
};

onMounted(() => {
    window.addEventListener('scroll', checkScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', checkScroll);
});
</script>

<template>
    <button 
        @click="scrollToTop"
        class="fixed bottom-8 right-8 z-40 w-12 h-12 bg-brand-blue text-white rounded-full shadow-lg flex items-center justify-center transition-all duration-300 hover:bg-brand-dark hover:-translate-y-1"
        :class="[
            isVisible ? 'opacity-100 translate-y-0 pointer-events-auto' : 'opacity-0 translate-y-10 pointer-events-none'
        ]"
        aria-label="Retour en haut"
    >
        <i class="ph-bold ph-arrow-up text-xl"></i>
    </button>
</template>
