<script setup>
import { ref, onMounted } from 'vue';

const visible = ref(false);
const message = ref('');
const type = ref('success'); // success, error, info

const show = (msg, msgType = 'success') => {
    message.value = msg;
    type.value = msgType;
    visible.value = true;
    setTimeout(() => {
        visible.value = false;
    }, 4000);
};

// Expose show method to parent
defineExpose({ show });

// Global event listener for toast events
onMounted(() => {
    window.addEventListener('show-toast', (event) => {
        show(event.detail.message, event.detail.type);
    });
});
</script>

<template>
    <div 
        class="fixed bottom-8 left-1/2 transform -translate-x-1/2 z-[100] transition-all duration-500"
        :class="[
            visible ? 'translate-y-0 opacity-100' : 'translate-y-20 opacity-0'
        ]"
    >
        <div class="bg-slate-900 text-white px-6 py-3 rounded-full shadow-2xl flex items-center gap-3 border border-slate-700">
            <i v-if="type === 'success'" class="ph-fill ph-check-circle text-green-400 text-xl"></i>
            <i v-else-if="type === 'error'" class="ph-fill ph-warning-circle text-red-400 text-xl"></i>
            <i v-else class="ph-fill ph-info text-blue-400 text-xl"></i>
            <p class="font-medium text-sm">{{ message }}</p>
        </div>
    </div>
</template>
