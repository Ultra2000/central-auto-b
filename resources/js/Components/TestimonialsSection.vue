<template>
    <section class="py-24 bg-gradient-to-b from-slate-50 to-white relative overflow-hidden">
        <div class="absolute top-1/2 left-0 w-[400px] h-[400px] bg-brand-blue/5 rounded-full blur-[100px] -translate-y-1/2 -translate-x-1/2"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 font-semibold text-sm mb-6">
                    <i class="ph-fill ph-star"></i>
                    Avis Clients Vérifiés
                </span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-brand-dark mb-4">
                    La satisfaction client,<br><span class="text-gradient">notre priorité</span>
                </h2>
                <p class="text-slate-500 max-w-2xl mx-auto text-lg">Découvrez les retours de nos clients satisfaits</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Témoignage 1 -->
                <div class="group bg-white p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 border border-slate-100 hover:border-brand-orange/30 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-brand-orange/5 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                    
                    <div class="flex gap-1 mb-6">
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                    </div>
                    
                    <p class="text-slate-600 leading-relaxed mb-6">"Excellent service ! J'ai trouvé la voiture de mes rêves en quelques jours. L'équipe est à l'écoute et très professionnelle. Je recommande vivement !"</p>
                    
                    <div class="flex items-center gap-4 pt-6 border-t border-slate-100">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-orange to-brand-red flex items-center justify-center text-white text-xl font-bold">
                            MF
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-dark">Marie Fontaine</h4>
                            <p class="text-slate-400 text-sm">Achat Renault Clio</p>
                        </div>
                    </div>
                </div>

                <!-- Témoignage 2 -->
                <div class="group bg-white p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 border border-slate-100 hover:border-brand-blue/30 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-brand-blue/5 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                    
                    <div class="flex gap-1 mb-6">
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                    </div>
                    
                    <p class="text-slate-600 leading-relaxed mb-6">"Service de chasseur auto impeccable. Ils ont trouvé exactement ce que je cherchais à un prix imbattable. Je recommande vivement Central Auto B !"</p>
                    
                    <div class="flex items-center gap-4 pt-6 border-t border-slate-100">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-blue to-brand-dark flex items-center justify-center text-white text-xl font-bold">
                            JD
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-dark">Jean Dupont</h4>
                            <p class="text-slate-400 text-sm">Chasseur Auto - Peugeot 3008</p>
                        </div>
                    </div>
                </div>

                <!-- Témoignage 3 -->
                <div class="group bg-white p-8 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 border border-slate-100 hover:border-brand-red/30 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-brand-red/5 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-500"></div>
                    
                    <div class="flex gap-1 mb-6">
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                        <i class="ph-fill ph-star text-yellow-400 text-xl"></i>
                    </div>
                    
                    <p class="text-slate-600 leading-relaxed mb-6">"Location simple et rapide. La voiture était impeccable et la reprise s'est faite sans accroc. Équipe réactive et tarifs compétitifs !"</p>
                    
                    <div class="flex items-center gap-4 pt-6 border-t border-slate-100">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-red to-brand-orange flex items-center justify-center text-white text-xl font-bold">
                            SL
                        </div>
                        <div>
                            <h4 class="font-bold text-brand-dark">Sophie Laurent</h4>
                            <p class="text-slate-400 text-sm">Location Citroën C3</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Trust Indicators -->
            <div class="mt-16 flex flex-wrap justify-center items-center gap-8 md:gap-16">
                <div class="flex items-center gap-3 text-slate-500">
                    <i class="ph-fill ph-google-logo text-3xl"></i>
                    <div>
                        <div class="font-bold text-brand-dark">4.9/5</div>
                        <div class="text-sm">120+ avis Google</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-slate-500">
                    <i class="ph-fill ph-facebook-logo text-3xl text-blue-600"></i>
                    <div>
                        <div class="font-bold text-brand-dark">4.8/5</div>
                        <div class="text-sm">85+ recommandations</div>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-slate-500">
                    <i class="ph-fill ph-seal-check text-3xl text-green-500"></i>
                    <div>
                        <div class="font-bold text-brand-dark">100%</div>
                        <div class="text-sm">Clients satisfaits</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
