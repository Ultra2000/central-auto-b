<template>
    <section id="plates" class="py-24 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900 rounded-3xl overflow-hidden shadow-2xl relative">
                <!-- Background & Overlay -->
                <div class="absolute inset-0">
                    <img src="https://images.unsplash.com/photo-1617788138017-80ad40651399?q=80&w=1200&auto=format&fit=crop" alt="Plaques immatriculation" class="w-full h-full object-cover opacity-40">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-slate-900/90 to-transparent"></div>
                </div>

                <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-12 p-8 md:p-16 items-center">
                    <div class="space-y-8">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-purple-500/20 border border-purple-500/30 text-purple-300 font-semibold text-sm">
                            <i class="ph-fill ph-article"></i>
                            Service Express
                        </div>
                        
                        <div>
                            <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">
                                Vos Plaques d'Immatriculation <br>
                                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-pink-400">en 5 minutes chrono</span>
                            </h2>
                            <p class="text-slate-300 text-lg leading-relaxed">
                                Ne perdez plus de temps ! Nous fabriquons et posons vos plaques d'immatriculation homologuées SIV directement sur place. Qualité plexiglas haut de gamme garantie.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400">
                                    <i class="ph-fill ph-clock text-2xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-white">Immédiat</p>
                                    <p class="text-sm text-slate-400">Fabrication sur place</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400">
                                    <i class="ph-fill ph-wrench text-2xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-white">Pose Offerte</p>
                                    <p class="text-sm text-slate-400">Par nos experts</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400">
                                    <i class="ph-fill ph-shield-check text-2xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-white">Homologué</p>
                                    <p class="text-sm text-slate-400">Normes SIV France</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center text-purple-400">
                                    <i class="ph-fill ph-map-pin text-2xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-white">Tous Départements</p>
                                    <p class="text-sm text-slate-400">Choix libre</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-4">
                            <a href="#contact" class="inline-flex items-center justify-center gap-3 bg-purple-600 hover:bg-purple-700 text-white font-bold px-8 py-4 rounded-2xl transition-all shadow-lg shadow-purple-600/30 hover:-translate-y-1">
                                <span>Commander mes plaques</span>
                                <i class="ph-bold ph-arrow-right"></i>
                            </a>
                            <div class="flex items-center gap-3 px-6 py-4 rounded-2xl bg-white/5 border border-white/10">
                                <span class="text-slate-300 text-sm">À partir de</span>
                                <span class="text-2xl font-bold text-white">15€</span>
                            </div>
                        </div>
                    </div>

                    <!-- Visual Representation of a Plate -->
                    <div class="hidden lg:flex items-center justify-center relative">
                        <div class="relative z-10 transform rotate-2 hover:rotate-0 transition-transform duration-500">
                            <!-- Plate Container -->
                            <div class="bg-white p-2 rounded-lg shadow-2xl w-[400px]">
                                <div class="border-4 border-black rounded flex h-[85px] items-center bg-white overflow-hidden relative">
                                    <!-- Euroband Left -->
                                    <div class="w-12 h-full bg-blue-700 flex flex-col items-center justify-between py-1">
                                        <span class="text-white text-xs font-bold">F</span>
                                    </div>
                                    
                                    <!-- Number -->
                                    <div class="flex-1 flex items-center justify-center gap-4 text-4xl font-bold font-mono tracking-wider text-black">
                                        <span>AA</span>
                                        <span>-</span>
                                        <span>123</span>
                                        <span>-</span>
                                        <span>BB</span>
                                    </div>
                                    
                                    <!-- Region Right -->
                                    <div class="w-12 h-full bg-blue-700 flex flex-col items-center justify-between py-1 relative">
                                        <div class="w-8 h-8 bg-white/20 rounded-full mt-1"></div> <!-- Logo region placeholder -->
                                        <span class="text-white text-lg font-bold">75</span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Reflection effect -->
                            <div class="absolute top-0 right-0 w-full h-full bg-gradient-to-tr from-transparent via-white/10 to-transparent pointer-events-none rounded-lg"></div>
                        </div>
                        
                        <!-- Decorative elements -->
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-500 rounded-full blur-[60px] opacity-40"></div>
                        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-blue-500 rounded-full blur-[60px] opacity-40"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
