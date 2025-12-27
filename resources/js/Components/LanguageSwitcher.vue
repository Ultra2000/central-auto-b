<script setup>
import { onMounted, ref } from 'vue';

const isOpen = ref(false);
const dropdownRef = ref(null);

const languages = [
    { code: 'fr', name: 'Français', flag: '🇫🇷' },
    { code: 'en', name: 'English', flag: '🇬🇧' },
    { code: 'es', name: 'Español', flag: '🇪🇸' },
    { code: 'de', name: 'Deutsch', flag: '🇩🇪' },
    { code: 'it', name: 'Italiano', flag: '🇮🇹' },
    { code: 'pt', name: 'Português', flag: '🇵🇹' },
    { code: 'ru', name: 'Русский', flag: '🇷🇺' },
    { code: 'zh-CN', name: '中文', flag: '🇨🇳' },
    { code: 'ja', name: '日本語', flag: '🇯🇵' },
    { code: 'ar', name: 'العربية', flag: '🇸🇦' },
    { code: 'nl', name: 'Nederlands', flag: '🇳🇱' },
];

const currentLang = ref(languages[0]);

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const switchLanguage = (lang) => {
    currentLang.value = lang;
    isOpen.value = false;
    
    // Attendre que Google Translate soit prêt
    const checkAndSwitch = () => {
        const selectElement = document.querySelector('.goog-te-combo');
        if (selectElement) {
            selectElement.value = lang.code;
            selectElement.dispatchEvent(new Event('change', { bubbles: true }));
        } else {
            // Réessayer après 200ms si le widget n'est pas encore prêt
            setTimeout(checkAndSwitch, 200);
        }
    };
    
    checkAndSwitch();
};

const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});
</script>

<template>
    <div class="relative inline-block" ref="dropdownRef">
        <button 
            @click="toggleDropdown"
            class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-100 transition-colors cursor-pointer"
        >
            <span class="text-2xl">{{ currentLang.flag }}</span>
            <span class="hidden md:inline text-sm font-medium text-slate-700">
                {{ currentLang.name }}
            </span>
            <i 
                class="ph-bold ph-caret-down text-sm text-slate-500 transition-transform"
                :class="{ 'rotate-180': isOpen }"
            ></i>
        </button>
        
        <Transition
            enter-active-class="transition ease-out duration-100"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div 
                v-if="isOpen"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-slate-200 z-50 max-h-96 overflow-y-auto"
            >
                <div class="py-2">
                    <button
                        v-for="lang in languages"
                        :key="lang.code"
                        @click="switchLanguage(lang)"
                        :class="[
                            'w-full px-4 py-2 text-left flex items-center gap-3 hover:bg-slate-50 transition-colors',
                            currentLang.code === lang.code ? 'bg-brand-blue/5 text-brand-blue font-semibold' : 'text-slate-700'
                        ]"
                    >
                        <span class="text-xl">{{ lang.flag }}</span>
                        <span class="text-sm">{{ lang.name }}</span>
                        <i v-if="currentLang.code === lang.code" class="ph-bold ph-check ml-auto text-brand-blue"></i>
                    </button>
                </div>
            </div>
        </Transition>
    </div>
</template>



