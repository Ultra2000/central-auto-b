<script setup>
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import PlatesConfigurator from '@/Components/PlatesConfigurator.vue';
import ContactSection from '@/Components/ContactSection.vue';

const props = defineProps({
    prices: {
        type: Object,
        default: () => ({
            plexiglass: '14.90',
            alu: '12.90'
        })
    }
});
</script>

<template>
    <Head title="Plaques d'immatriculation - Créez votre plaque" />

    <MainLayout>
        <div class="pt-24">
            <PlatesConfigurator :prices="prices" />
            <ContactSection />
        </div>
    </MainLayout>
</template>
