<template>
    <section id="contact" class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-semibold text-sm mb-6">
                    <i class="ph-fill ph-chat-circle-text"></i>
                    Contactez-nous
                </div>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 mb-6">
                    Une question ? Un projet ?
                </h2>
                <p class="text-lg text-slate-600">
                    Notre équipe est à votre écoute pour vous accompagner dans votre projet automobile.
                    Passez nous voir ou envoyez-nous un message.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Contact Info Cards -->
                <div class="space-y-6">
                    <!-- Address -->
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 mb-6">
                            <i class="ph-fill ph-map-pin text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Notre Adresse</h3>
                        <p class="text-slate-600 leading-relaxed">
                            123 Avenue de l'Automobile<br>
                            75000 Paris, France
                        </p>
                        <a href="https://maps.google.com" target="_blank" class="inline-flex items-center gap-2 text-blue-600 font-semibold mt-4 hover:gap-3 transition-all">
                            Itinéraire <i class="ph-bold ph-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Phone & Email -->
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-2xl bg-green-50 flex items-center justify-center text-green-600 mb-6">
                            <i class="ph-fill ph-phone text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Nous Contacter</h3>
                        <div class="space-y-3">
                            <a href="tel:+33123456789" class="flex items-center gap-3 text-slate-600 hover:text-blue-600 transition-colors">
                                <i class="ph-bold ph-phone"></i>
                                01 23 45 67 89
                            </a>
                            <a href="mailto:contact@centralautob.fr" class="flex items-center gap-3 text-slate-600 hover:text-blue-600 transition-colors">
                                <i class="ph-bold ph-envelope"></i>
                                contact@centralautob.fr
                            </a>
                        </div>
                    </div>

                    <!-- Hours -->
                    <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600 mb-6">
                            <i class="ph-fill ph-clock text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-2">Horaires d'ouverture</h3>
                        <ul class="space-y-2 text-slate-600">
                            <li class="flex justify-between">
                                <span>Lundi - Vendredi</span>
                                <span class="font-semibold text-slate-900">9h - 19h</span>
                            </li>
                            <li class="flex justify-between">
                                <span>Samedi</span>
                                <span class="font-semibold text-slate-900">10h - 18h</span>
                            </li>
                            <li class="flex justify-between text-orange-600">
                                <span>Dimanche</span>
                                <span class="font-semibold">Fermé</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white p-8 md:p-10 rounded-3xl shadow-lg border border-slate-100 h-full">
                        <h3 class="text-2xl font-bold text-slate-900 mb-8">Envoyez-nous un message</h3>
                        
                        <form @submit.prevent="submitForm" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label for="name" class="text-sm font-semibold text-slate-700">Nom complet</label>
                                    <input type="text" id="name" v-model="form.name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none" placeholder="Jean Dupont" required>
                                </div>
                                <div class="space-y-2">
                                    <label for="phone" class="text-sm font-semibold text-slate-700">Téléphone</label>
                                    <input type="tel" id="phone" v-model="form.phone" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none" placeholder="06 12 34 56 78" required>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
                                <input type="email" id="email" v-model="form.email" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none" placeholder="jean.dupont@email.com" required>
                            </div>

                            <div class="space-y-2">
                                <label for="subject" class="text-sm font-semibold text-slate-700">Sujet</label>
                                <select id="subject" v-model="form.subject" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none bg-white">
                                    <option value="info">Demande d'information</option>
                                    <option value="rdv">Prise de rendez-vous</option>
                                    <option value="reprise">Estimation reprise</option>
                                    <option value="autre">Autre demande</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label for="message" class="text-sm font-semibold text-slate-700">Message</label>
                                <textarea id="message" v-model="form.message" rows="5" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all outline-none resize-none" placeholder="Comment pouvons-nous vous aider ?" required></textarea>
                            </div>

                            <button type="submit" :disabled="form.processing" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-blue-600/30 hover:-translate-y-1 disabled:opacity-70 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                                <span v-if="!form.processing">Envoyer le message</span>
                                <span v-else>Envoi en cours...</span>
                                <i v-if="!form.processing" class="ph-bold ph-paper-plane-right"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    phone: '',
    email: '',
    subject: 'info',
    message: ''
});

const submitForm = () => {
    // In a real app, you would post to a route
    // form.post(route('contact.send'));
    console.log('Form submitted:', form.data());
    alert('Message envoyé (simulation) !');
    form.reset();
};
</script>
