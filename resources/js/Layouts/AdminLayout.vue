<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex">
        <!-- Sidebar - Desktop -->
        <aside class="w-64 bg-brand-dark text-white hidden md:flex flex-col fixed inset-y-0 left-0 z-40 transition-all duration-300">
            <!-- Logo Area -->
            <div class="h-20 flex items-center px-6 border-b border-white/10 flex-shrink-0">
                <Link :href="route('admin.dashboard')" class="flex items-center gap-3 group w-full">
                    <img src="/images/unnamed.png" alt="Central Auto B" class="h-12 w-auto object-contain brightness-0 invert group-hover:scale-105 transition-transform duration-300">
                </Link>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 py-8 px-4 space-y-2 overflow-y-auto">
                <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Menu Principal</p>
                
                <Link :href="route('admin.dashboard')" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
                    :class="route().current('admin.dashboard') ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/30' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                >
                    <i class="ph-fill ph-squares-four text-xl" :class="route().current('admin.dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-brand-orange'"></i>
                    <span class="font-medium">Tableau de bord</span>
                </Link>

                <Link :href="route('admin.vehicles.index')" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
                    :class="route().current('admin.vehicles.*') ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/30' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                >
                    <i class="ph-fill ph-car text-xl" :class="route().current('admin.vehicles.*') ? 'text-white' : 'text-slate-500 group-hover:text-brand-orange'"></i>
                    <span class="font-medium">Véhicules</span>
                </Link>

                <Link :href="route('admin.leads.index')" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
                    :class="route().current('admin.leads.*') ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/30' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                >
                    <i class="ph-fill ph-users text-xl" :class="route().current('admin.leads.*') ? 'text-white' : 'text-slate-500 group-hover:text-brand-orange'"></i>
                    <span class="font-medium">Contacts & Leads</span>
                    <span v-if="$page.props.unread_leads_count > 0" class="ml-auto bg-brand-red text-white text-xs font-bold px-2 py-0.5 rounded-full">
                        {{ $page.props.unread_leads_count }}
                    </span>
                </Link>

                <Link :href="route('admin.plate-orders.index')" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
                    :class="route().current('admin.plate-orders.*') ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/30' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                >
                    <i class="ph-fill ph-article text-xl" :class="route().current('admin.plate-orders.*') ? 'text-white' : 'text-slate-500 group-hover:text-brand-orange'"></i>
                    <span class="font-medium">Commandes Plaques</span>
                </Link>

                <Link :href="route('admin.custom-requests.index')" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
                    :class="route().current('admin.custom-requests.*') ? 'bg-brand-gradient text-white shadow-lg shadow-brand-orange/30' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                >
                    <i class="ph-fill ph-sparkle text-xl" :class="route().current('admin.custom-requests.*') ? 'text-white' : 'text-slate-500 group-hover:text-brand-orange'"></i>
                    <span class="font-medium">Demandes Personnalisées</span>
                </Link>

                <Link :href="route('admin.vehicle-interests.index')" 
                    class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group"
                    :class="route().current('admin.vehicle-interests.*') ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/30' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                >
                    <i class="ph-fill ph-calendar-check text-xl" :class="route().current('admin.vehicle-interests.*') ? 'text-white' : 'text-slate-500 group-hover:text-brand-orange'"></i>
                    <span class="font-medium">Demandes Véhicules</span>
                </Link>

                <div class="pt-8">
                    <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Configuration</p>
                    <Link :href="route('admin.visit-availabilities.index')" 
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group mb-2"
                        :class="route().current('admin.visit-availabilities.*') ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/30' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                    >
                        <i class="ph-fill ph-calendar-dots text-xl" :class="route().current('admin.visit-availabilities.*') ? 'text-white' : 'text-slate-500 group-hover:text-brand-orange'"></i>
                        <span class="font-medium" :class="route().current('admin.visit-availabilities.*') ? 'text-white' : 'text-slate-300'">Disponibilités Visites</span>
                    </Link>
                    <Link :href="route('admin.delivery-zones.index')" 
                        class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group mb-2"
                        :class="route().current('admin.delivery-zones.*') ? 'bg-brand-orange text-white shadow-lg shadow-brand-orange/30' : 'text-slate-400 hover:bg-white/5 hover:text-white'"
                    >
                        <i class="ph-fill ph-truck text-xl" :class="route().current('admin.delivery-zones.*') ? 'text-white' : 'text-slate-500 group-hover:text-brand-orange'"></i>
                        <span class="font-medium" :class="route().current('admin.delivery-zones.*') ? 'text-white' : 'text-slate-300'">Zones de Livraison</span>
                    </Link>
                    <Link :href="route('admin.settings.index')" :class="route().current('admin.settings.*') ? 'bg-brand-gradient shadow-lg shadow-brand-orange/20' : 'hover:bg-white/5'" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group mb-2">
                        <i class="ph-fill ph-gear text-xl" :class="route().current('admin.settings.*') ? 'text-white' : 'text-slate-500 group-hover:text-brand-orange'"></i>
                        <span class="font-medium" :class="route().current('admin.settings.*') ? 'text-white' : 'text-slate-300'">Paramètres</span>
                    </Link>
                </div>

                <div class="pt-8">
                    <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Site Web</p>
                    <a href="/" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-400 hover:bg-white/5 hover:text-white transition-all duration-200 group">
                        <i class="ph-bold ph-arrow-square-out text-xl text-slate-500 group-hover:text-brand-orange"></i>
                        <span class="font-medium">Voir le site</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile Bottom -->
            <div class="p-4 border-t border-white/10 flex-shrink-0">
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-white/5 border border-white/5">
                    <div class="w-10 h-10 rounded-full bg-brand-blue flex items-center justify-center text-white font-bold flex-shrink-0">
                        {{ $page.props.auth.user.name.charAt(0) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ $page.props.auth.user.name }}</p>
                        <p class="text-xs text-slate-400 truncate">Administrateur</p>
                    </div>
                    <Link :href="route('logout')" method="post" as="button" class="text-slate-400 hover:text-brand-red transition-colors flex-shrink-0">
                        <i class="ph-bold ph-sign-out text-xl"></i>
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="w-full md:w-auto md:flex-1 md:ml-64 flex flex-col min-h-screen">
            <!-- Mobile Header -->
            <header class="bg-white border-b border-slate-200 sticky top-0 z-50 px-4 py-3 flex justify-between items-center md:hidden">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-brand-orange to-brand-red flex items-center justify-center shadow-md">
                        <span class="font-bold text-white text-sm">C</span>
                    </div>
                    <span class="font-bold text-brand-dark">Admin</span>
                </div>
                <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="w-10 h-10 flex items-center justify-center rounded-lg text-slate-500 hover:bg-slate-100 hover:text-brand-blue transition-colors">
                    <i class="ph-bold text-2xl" :class="showingNavigationDropdown ? 'ph-x' : 'ph-list'"></i>
                </button>
            </header>

            <!-- Mobile Menu Overlay -->
            <Transition
                enter-active-class="transition-opacity duration-200"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-show="showingNavigationDropdown" class="fixed inset-0 bg-black/50 z-40 md:hidden" @click="showingNavigationDropdown = false"></div>
            </Transition>

            <!-- Mobile Menu Drawer -->
            <Transition
                enter-active-class="transition-transform duration-300 ease-out"
                enter-from-class="-translate-x-full"
                enter-to-class="translate-x-0"
                leave-active-class="transition-transform duration-300 ease-in"
                leave-from-class="translate-x-0"
                leave-to-class="-translate-x-full"
            >
                <div v-show="showingNavigationDropdown" class="fixed inset-y-0 left-0 w-72 bg-brand-dark text-white z-50 md:hidden overflow-y-auto">
                    <!-- Mobile Menu Header -->
                    <div class="h-16 flex items-center justify-between px-6 border-b border-white/10">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-brand-orange to-brand-red flex items-center justify-center">
                                <span class="font-bold text-white text-sm">C</span>
                            </div>
                            <span class="font-bold text-white">Central Auto B</span>
                        </div>
                        <button @click="showingNavigationDropdown = false" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-white/10 hover:text-white">
                            <i class="ph-bold ph-x text-xl"></i>
                        </button>
                    </div>
                    
                    <!-- Mobile Navigation -->
                    <nav class="p-4 space-y-2">
                        <p class="px-4 text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Menu</p>
                        
                        <Link :href="route('admin.dashboard')" @click="showingNavigationDropdown = false" 
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors"
                            :class="route().current('admin.dashboard') ? 'bg-brand-blue text-white' : 'text-slate-300 hover:bg-white/10'">
                            <i class="ph-fill ph-squares-four text-xl"></i>
                            <span class="font-medium">Dashboard</span>
                        </Link>
                        
                        <Link :href="route('admin.vehicles.index')" @click="showingNavigationDropdown = false"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors"
                            :class="route().current('admin.vehicles.*') ? 'bg-brand-blue text-white' : 'text-slate-300 hover:bg-white/10'">
                            <i class="ph-fill ph-car text-xl"></i>
                            <span class="font-medium">Véhicules</span>
                        </Link>
                        
                        <Link :href="route('admin.leads.index')" @click="showingNavigationDropdown = false"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors"
                            :class="route().current('admin.leads.*') ? 'bg-brand-blue text-white' : 'text-slate-300 hover:bg-white/10'">
                            <i class="ph-fill ph-users text-xl"></i>
                            <span class="font-medium">Leads</span>
                        </Link>
                        
                        <a href="/" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-white/10 transition-colors">
                            <i class="ph-bold ph-globe text-xl"></i>
                            <span class="font-medium">Voir le site</span>
                        </a>
                        
                        <div class="pt-4 border-t border-white/10 mt-4">
                            <Link :href="route('logout')" method="post" as="button" class="flex items-center gap-3 px-4 py-3 rounded-xl text-red-400 hover:bg-red-500/10 transition-colors w-full">
                                <i class="ph-bold ph-sign-out text-xl"></i>
                                <span class="font-medium">Déconnexion</span>
                            </Link>
                        </div>
                    </nav>
                </div>
            </Transition>

            <!-- Page Content -->
            <main class="flex-1 w-full p-4 md:p-8 overflow-x-hidden">
                <!-- Header Slot -->
                <div v-if="$slots.header" class="mb-6 md:mb-8">
                    <slot name="header" />
                </div>

                <!-- Flash Messages -->
                <div v-if="$page.props.flash && $page.props.flash.success" class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                    {{ $page.props.flash.success }}
                </div>

                <slot />
            </main>
        </div>
    </div>
</template>