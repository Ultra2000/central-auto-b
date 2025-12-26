<script setup>
import { Head } from '@inertiajs/vue3';
import MainLayout from '@/Layouts/MainLayout.vue';
import PlatesConfigurator from '@/Components/PlatesConfigurator.vue';
import ContactSection from '@/Components/ContactSection.vue';
</script>

<template>
    <Head title="Plaques d'immatriculation - Créez votre plaque" />

    <MainLayout>
        <div class="pt-24">
            <PlatesConfigurator />
            <ContactSection />
        </div>
    </MainLayout>
</template>
