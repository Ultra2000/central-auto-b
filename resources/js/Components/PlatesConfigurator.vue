<script setup>
import { ref, computed, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';

// Load Oswald font for the license plate
onMounted(() => {
    if (!document.querySelector('link[href*="Oswald"]')) {
        const link = document.createElement('link');
        link.href = 'https://fonts.googleapis.com/css2?family=Oswald:wght@400;600&display=swap';
        link.rel = 'stylesheet';
        document.head.appendChild(link);
    }
});

const showOrderForm = ref(false);
const notification = ref({
    show: false,
    type: 'success',
    message: ''
});

const showNotification = (type, message) => {
    notification.value = {
        show: true,
        type,
        message
    };
    
    setTimeout(() => {
        notification.value.show = false;
    }, 5000);
};

const closeNotification = () => {
    notification.value.show = false;
};

const form = useForm({
    plate_type: 'definitive',
    plate_number: 'AA-123-AA',
    department: '75',
    custom_text: '',
    material: 'plexiglass',
    quantity: 1,
    unit_price: 14.90,
    total_price: 14.90,
    customer_name: '',
    customer_email: '',
    customer_phone: '',
    customer_address: '',
});

const price = computed(() => {
    return form.material === 'plexiglass' ? 14.90 : 12.90;
});

const totalPrice = computed(() => {
    const total = price.value * form.quantity;
    form.unit_price = price.value;
    form.total_price = total;
    return total.toFixed(2);
});

const incrementQuantity = () => {
    form.quantity++;
};

const decrementQuantity = () => {
    if (form.quantity > 1) {
        form.quantity--;
    }
};

const formatPlateNumber = (value) => {
    return value.toUpperCase();
};

const handlePlateInput = (e) => {
    form.plate_number = formatPlateNumber(e.target.value);
};

const handlePlateTypeChange = () => {
    if (form.plate_type === 'provisional') {
        form.plate_number = '1234 WW 75';
    } else {
        form.plate_number = 'AA-123-AA';
    }
};

const submitOrder = () => {
    form.post(route('plate-orders.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showOrderForm.value = false;
            form.reset('customer_name', 'customer_email', 'customer_phone', 'customer_address');
            showNotification('success', 'Votre commande a été envoyée avec succès ! Vous recevrez un email de confirmation sous peu.');
        },
        onError: () => {
            showNotification('error', 'Une erreur est survenue lors de l\'envoi de votre commande. Veuillez réessayer.');
        },
    });
};

const getStarPosition = (index) => {
    const angle = (index - 1) * 30 - 90;
    const radius = 13;
    const centerX = 50;
    const centerY = 50;
    
    const x = centerX + radius * Math.cos(angle * Math.PI / 180);
    const y = centerY + radius * Math.sin(angle * Math.PI / 180);
    
    return {
        left: `${x}%`,
        top: `${y}%`,
    };
};

const departments = [
    { code: '01', name: 'Ain' },
    { code: '02', name: 'Aisne' },
    { code: '03', name: 'Allier' },
    { code: '06', name: 'Alpes-Maritimes' },
    { code: '13', name: 'Bouches-du-Rhône' },
    { code: '33', name: 'Gironde' },
    { code: '59', name: 'Nord' },
    { code: '67', name: 'Bas-Rhin' },
    { code: '69', name: 'Rhône' },
    { code: '75', name: 'Paris' },
    { code: '77', name: 'Seine-et-Marne' },
    { code: '78', name: 'Yvelines' },
    { code: '91', name: 'Essonne' },
    { code: '92', name: 'Hauts-de-Seine' },
    { code: '93', name: 'Seine-Saint-Denis' },
    { code: '94', name: 'Val-de-Marne' },
    { code: '95', name: "Val-d'Oise" },
];
</script>

<template>
    <section class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="text-center mb-12">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-blue/10 text-brand-blue font-bold text-sm mb-6">
                    <i class="ph-fill ph-paint-brush-broad"></i>
                    Personnalisation
                </span>
                <h1 class="text-4xl md:text-5xl font-extrabold text-brand-dark mb-4">
                    Créez votre <span class="text-gradient">Plaque</span>
                </h1>
                <p class="text-slate-500 max-w-2xl mx-auto text-lg">
                    Configurez votre plaque d'immatriculation en quelques clics. Homologuée et livrée chez vous.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- Left Column: Preview -->
                <div class="lg:col-span-7 sticky top-32">
                    <div class="bg-slate-200 rounded-3xl p-8 md:p-16 flex items-center justify-center shadow-inner min-h-[300px] plate-preview-container">
                        
                        <!-- The Plate -->
                        <div class="license-plate">
                            <!-- Left Strip (Euro/Provisional) -->
                            <div :class="form.plate_type === 'provisional' ? 'plate-strip-left-provisional' : 'plate-strip-left'">
                                <!-- EU Flag SVG -->
                                <svg v-if="form.plate_type === 'definitive'" class="eu-flag-svg" viewBox="0 0 36 36" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="18" cy="18" r="18" fill="#003399"/>
                                    <g fill="#FFDD00">
                                        <path d="M18 5l.6 1.8h1.9l-1.5 1.1.6 1.8L18 8.6l-1.5 1.1.6-1.8-1.5-1.1h1.9z"/>
                                        <path d="M25.5 7.3l-.6 1.8 1.5 1.1h-1.9l-.6 1.8-.6-1.8h-1.9l1.5-1.1-.6-1.8 1.6 1.2z"/>
                                        <path d="M29.7 13.5l-1.8.6 0 1.9-1.1-1.5-1.8.6 1.1-1.5-1.1-1.5 1.8.6 1.1-1.5 0 1.9z"/>
                                        <path d="M30.7 21.5h-1.9l-.6 1.8-.6-1.8h-1.9l1.5-1.1-.6-1.8 1.6 1.1 1.5-1.1-.6 1.8z"/>
                                        <path d="M27.3 28l-1.1-1.5-1.8.6.6-1.8-1.1-1.5 1.9 0 .6-1.8.6 1.8 1.9 0-1.5 1.1z"/>
                                        <path d="M19.9 31l-.6-1.8h-1.9l1.5-1.1-.6-1.8 1.6 1.1 1.5-1.1-.6 1.8 1.5 1.1h-1.9z"/>
                                        <path d="M10.5 28.7l.6-1.8-1.5-1.1h1.9l.6-1.8.6 1.8h1.9l-1.5 1.1.6 1.8-1.6-1.1z"/>
                                        <path d="M6.3 22.5l1.8-.6 0-1.9 1.1 1.5 1.8-.6-1.1 1.5 1.1 1.5-1.8-.6-1.1 1.5 0-1.9z"/>
                                        <path d="M5.3 14.5h1.9l.6-1.8.6 1.8h1.9l-1.5 1.1.6 1.8-1.6-1.1-1.5 1.1.6-1.8z"/>
                                        <path d="M8.7 8l1.1 1.5 1.8-.6-.6 1.8 1.1 1.5-1.9 0-.6 1.8-.6-1.8-1.9 0 1.5-1.1z"/>
                                    </g>
                                </svg>
                                <!-- Provisional indicator -->
                                <div v-else class="provisional-indicator">
                                    <span class="provisional-text">WW</span>
                                </div>
                                <span class="plate-country">F</span>
                            </div>

                            <!-- Center Content -->
                            <div class="plate-content">
                                <span :class="form.plate_type === 'provisional' ? 'plate-number-provisional' : 'plate-number'">{{ form.plate_number }}</span>
                                <span v-if="form.custom_text" class="plate-text">{{ form.custom_text }}</span>
                            </div>

                            <!-- Right Strip (Region/Provisional) -->
                            <div :class="form.plate_type === 'provisional' ? 'plate-strip-right-provisional' : 'plate-strip-right'">
                                <div v-if="form.plate_type === 'definitive'" class="plate-region-logo">
                                    <i class="ph-fill ph-map-pin text-white text-sm"></i>
                                </div>
                                <div v-else class="provisional-indicator">
                                    <span class="provisional-text">WW</span>
                                </div>
                                <span class="plate-dept">{{ form.department }}</span>
                            </div>
                        </div>

                    </div>
                    
                    <!-- Features List -->
                    <div class="grid grid-cols-3 gap-4 mt-8">
                        <div class="bg-white p-4 rounded-2xl border border-slate-100 text-center">
                            <div class="w-10 h-10 mx-auto bg-green-100 rounded-full flex items-center justify-center text-green-600 mb-2">
                                <i class="ph-bold ph-check-circle"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-700">Homologuée</span>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-slate-100 text-center">
                            <div class="w-10 h-10 mx-auto bg-blue-100 rounded-full flex items-center justify-center text-blue-600 mb-2">
                                <i class="ph-bold ph-truck"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-700">Livraison 48h</span>
                        </div>
                        <div class="bg-white p-4 rounded-2xl border border-slate-100 text-center">
                            <div class="w-10 h-10 mx-auto bg-purple-100 rounded-full flex items-center justify-center text-purple-600 mb-2">
                                <i class="ph-bold ph-shield-check"></i>
                            </div>
                            <span class="text-sm font-bold text-slate-700">Garantie à vie</span>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Controls -->
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8">
                        
                        <form class="space-y-8" @submit.prevent>
                            
                            <!-- Type Selection -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-3">
                                    Type de plaque
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="definitive" v-model="form.plate_type" @change="handlePlateTypeChange" class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-brand-blue peer-checked:bg-brand-blue/5 transition-all text-center">
                                            <div class="w-10 h-10 mx-auto mb-2 bg-green-100 peer-checked:bg-brand-blue/20 rounded-full flex items-center justify-center text-green-600 peer-checked:text-brand-blue transition-colors">
                                                <i class="ph-bold ph-check-circle text-xl"></i>
                                            </div>
                                            <span class="block font-bold text-slate-700">Définitive</span>
                                            <span class="text-xs text-slate-500">Format SIV</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="provisional" v-model="form.plate_type" @change="handlePlateTypeChange" class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-brand-orange peer-checked:bg-brand-orange/5 transition-all text-center">
                                            <div class="w-10 h-10 mx-auto mb-2 bg-orange-100 peer-checked:bg-brand-orange/20 rounded-full flex items-center justify-center text-orange-600 peer-checked:text-brand-orange transition-colors">
                                                <i class="ph-bold ph-clock text-xl"></i>
                                            </div>
                                            <span class="block font-bold text-slate-700">Provisoire</span>
                                            <span class="text-xs text-slate-500">Format WW</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Step 1: Number -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-3">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-brand-blue text-white flex items-center justify-center text-xs">1</span>
                                        Numéro d'immatriculation
                                    </span>
                                </label>
                                <input 
                                    type="text" 
                                    v-model="form.plate_number"
                                    @input="handlePlateInput"
                                    class="w-full px-4 py-4 text-xl font-mono uppercase text-center rounded-xl bg-slate-50 border-2 border-slate-200 focus:border-brand-blue focus:ring-0 outline-none transition-all placeholder-slate-300"
                                    :placeholder="form.plate_type === 'provisional' ? '1234 WW 75' : 'AA-123-AA'" 
                                    :maxlength="form.plate_type === 'provisional' ? 11 : 9"
                                >
                                <p class="text-xs text-slate-400 mt-2 text-center">
                                    <span v-if="form.plate_type === 'definitive'">Format SIV (AA-123-AA) ou FNI (1234 AA 00)</span>
                                    <span v-else>Format provisoire (1234 WW 75)</span>
                                </p>
                            </div>

                            <!-- Step 2: Department -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-3">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-brand-blue text-white flex items-center justify-center text-xs">2</span>
                                        Département
                                    </span>
                                </label>
                                <select 
                                    v-model="form.department"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                                >
                                    <option v-for="dept in departments" :key="dept.code" :value="dept.code">
                                        {{ dept.code }} - {{ dept.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Step 3: Custom Text -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-3">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-brand-blue text-white flex items-center justify-center text-xs">3</span>
                                        Texte personnalisé (Optionnel)
                                    </span>
                                </label>
                                <input 
                                    type="text" 
                                    v-model="form.custom_text"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                                    placeholder="Ex: CENTRAL AUTO B" 
                                    maxlength="40"
                                >
                            </div>

                            <!-- Step 4: Material -->
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-3">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-brand-blue text-white flex items-center justify-center text-xs">4</span>
                                        Matériau
                                    </span>
                                </label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="cursor-pointer">
                                        <input type="radio" value="plexiglass" v-model="form.material" class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-brand-blue peer-checked:bg-brand-blue/5 transition-all text-center">
                                            <span class="block font-bold text-slate-700">Plexiglass</span>
                                            <span class="text-xs text-slate-500">Haut de gamme</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" value="alu" v-model="form.material" class="peer sr-only">
                                        <div class="p-4 rounded-xl border-2 border-slate-200 peer-checked:border-brand-blue peer-checked:bg-brand-blue/5 transition-all text-center">
                                            <span class="block font-bold text-slate-700">Aluminium</span>
                                            <span class="text-xs text-slate-500">Standard</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Summary & Action -->
                            <div class="pt-8 border-t border-slate-100">
                                <div class="flex justify-between items-end mb-6">
                                    <div>
                                        <span class="block text-sm text-slate-500 mb-1">Prix unitaire</span>
                                        <span class="text-3xl font-extrabold text-brand-dark">{{ price.toFixed(2) }} €</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <button 
                                            type="button" 
                                            @click="decrementQuantity"
                                            class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center hover:bg-slate-200 font-bold"
                                        >-</button>
                                        <span class="font-bold w-8 text-center">{{ form.quantity }}</span>
                                        <button 
                                            type="button" 
                                            @click="incrementQuantity"
                                            class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center hover:bg-slate-200 font-bold"
                                        >+</button>
                                    </div>
                                </div>
                                
                                <button 
                                    type="button" 
                                    @click="showOrderForm = true"
                                    class="w-full py-4 rounded-xl bg-brand-gradient text-white font-bold text-lg shadow-lg shadow-brand-orange/20 hover:opacity-90 transition-all flex items-center justify-center gap-2"
                                >
                                    <i class="ph-bold ph-shopping-cart"></i>
                                    Commander ({{ totalPrice }} €)
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <!-- Notification Toast -->
            <Transition name="slide-down">
                <div v-if="notification.show" class="fixed top-6 right-6 z-[60] max-w-md w-full">
                    <div :class="[
                        'rounded-2xl shadow-2xl p-6 flex items-start gap-4 border-2',
                        notification.type === 'success' ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500'
                    ]">
                        <div :class="[
                            'w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0',
                            notification.type === 'success' ? 'bg-green-500' : 'bg-red-500'
                        ]">
                            <i v-if="notification.type === 'success'" class="ph-bold ph-check text-white text-xl"></i>
                            <i v-else class="ph-bold ph-x text-white text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 :class="[
                                'font-bold mb-1',
                                notification.type === 'success' ? 'text-green-900' : 'text-red-900'
                            ]">
                                {{ notification.type === 'success' ? 'Commande envoyée' : 'Erreur' }}
                            </h4>
                            <p :class="[
                                'text-sm',
                                notification.type === 'success' ? 'text-green-700' : 'text-red-700'
                            ]">
                                {{ notification.message }}
                            </p>
                        </div>
                        <button 
                            @click="closeNotification"
                            :class="[
                                'w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-colors',
                                notification.type === 'success' ? 'hover:bg-green-200 text-green-700' : 'hover:bg-red-200 text-red-700'
                            ]"
                        >
                            <i class="ph-bold ph-x"></i>
                        </button>
                    </div>
                </div>
            </Transition>

            <!-- /div>

            </div>

            <!-- Order Form Modal -->
            <div v-if="showOrderForm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="sticky top-0 bg-white border-b border-slate-200 p-6 rounded-t-3xl">
                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl font-bold text-slate-900">Finaliser la commande</h3>
                            <button @click="showOrderForm = false" class="w-10 h-10 rounded-full hover:bg-slate-100 flex items-center justify-center transition-colors">
                                <i class="ph-bold ph-x text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <form @submit.prevent="submitOrder" class="p-6 space-y-6">
                        <!-- Order Summary -->
                        <div class="bg-slate-50 rounded-2xl p-6 space-y-3">
                            <h4 class="font-bold text-slate-900 mb-4">Récapitulatif</h4>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-600">Type:</span>
                                <span class="font-semibold">{{ form.plate_type === 'definitive' ? 'Définitive' : 'Provisoire' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-600">Numéro:</span>
                                <span class="font-mono font-bold">{{ form.plate_number }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-600">Département:</span>
                                <span class="font-semibold">{{ form.department }}</span>
                            </div>
                            <div class="flex justify-between text-sm" v-if="form.custom_text">
                                <span class="text-slate-600">Texte:</span>
                                <span class="font-semibold">{{ form.custom_text }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-600">Matériau:</span>
                                <span class="font-semibold">{{ form.material === 'plexiglass' ? 'Plexiglass' : 'Aluminium' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-600">Quantité:</span>
                                <span class="font-semibold">{{ form.quantity }}</span>
                            </div>
                            <div class="pt-3 border-t border-slate-200 flex justify-between">
                                <span class="font-bold text-slate-900">Total:</span>
                                <span class="text-2xl font-extrabold text-brand-blue">{{ totalPrice }} €</span>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="space-y-4">
                            <h4 class="font-bold text-slate-900">Vos coordonnées</h4>
                            
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Nom complet *</label>
                                <input 
                                    type="text" 
                                    v-model="form.customer_name"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                                    placeholder="Jean Dupont"
                                    required
                                >
                                <span v-if="form.errors.customer_name" class="text-sm text-red-600 mt-1 block">{{ form.errors.customer_name }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Email *</label>
                                <input 
                                    type="email" 
                                    v-model="form.customer_email"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                                    placeholder="jean.dupont@example.com"
                                    required
                                >
                                <span v-if="form.errors.customer_email" class="text-sm text-red-600 mt-1 block">{{ form.errors.customer_email }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Téléphone *</label>
                                <input 
                                    type="tel" 
                                    v-model="form.customer_phone"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all"
                                    placeholder="06 12 34 56 78"
                                    required
                                >
                                <span v-if="form.errors.customer_phone" class="text-sm text-red-600 mt-1 block">{{ form.errors.customer_phone }}</span>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Adresse (optionnel)</label>
                                <textarea 
                                    v-model="form.customer_address"
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-200 focus:border-brand-blue focus:ring-2 focus:ring-brand-blue/20 outline-none transition-all resize-none"
                                    placeholder="123 Rue de la République, 75000 Paris"
                                    rows="3"
                                ></textarea>
                                <span v-if="form.errors.customer_address" class="text-sm text-red-600 mt-1 block">{{ form.errors.customer_address }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-4 pt-4">
                            <button 
                                type="button" 
                                @click="showOrderForm = false"
                                class="flex-1 py-3 rounded-xl border-2 border-slate-200 font-bold text-slate-700 hover:bg-slate-50 transition-all"
                            >
                                Annuler
                            </button>
                            <button 
                                type="submit" 
                                :disabled="form.processing"
                                class="flex-1 py-3 rounded-xl bg-brand-gradient text-white font-bold shadow-lg hover:opacity-90 transition-all disabled:opacity-50"
                            >
                                <span v-if="!form.processing">Valider la commande</span>
                                <span v-else>Envoi en cours...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.plate-preview-container {
    overflow: visible;
}

/* Plate Styles */
.license-plate {
    width: 520px;
    height: 110px;
    background: white;
    border-radius: 10px;
    box-shadow: 
        0 0 0 3px #000,
        0 10px 40px rgba(0, 0, 0, 0.3),
        inset 0 0 20px rgba(0,0,0,0.08);
    display: flex;
    position: relative;
    overflow: hidden;
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
}

/* Left Euro Strip */
.plate-strip-left {
    width: 45px;
    height: 100%;
    background: #003399;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    padding: 8px 0;
    position: relative;
    z-index: 10;
}

/* Left Provisional Strip (Red) */
.plate-strip-left-provisional {
    width: 45px;
    height: 100%;
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    padding: 8px 0;
    position: relative;
    z-index: 10;
}

.eu-flag-svg {
    width: 36px;
    height: 36px;
    margin-top: 2px;
}

.provisional-indicator {
    width: 36px;
    height: 36px;
    margin-top: 2px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
}

.provisional-text {
    font-size: 14px;
    font-weight: 900;
    color: white;
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
    letter-spacing: -1px;
}

.plate-country {
    font-size: 24px;
    font-weight: 700;
    color: white;
    letter-spacing: 0;
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
}

/* Center Content */
.plate-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    position: relative;
    background: white;
    padding: 0 8px;
}

.plate-number {
    font-size: 72px;
    font-weight: 700;
    letter-spacing: 8px;
    color: #000;
    line-height: 1;
    text-transform: uppercase;
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
}

.plate-number-provisional {
    font-size: 58px;
    font-weight: 700;
    letter-spacing: 4px;
    color: #000;
    line-height: 1;
    text-transform: uppercase;
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
}

.plate-text {
    position: absolute;
    bottom: 8px;
    font-size: 11px;
    color: #666;
    font-weight: 500;
    text-transform: uppercase;
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
    letter-spacing: 1px;
}

/* Right Region Strip */
.plate-strip-right {
    width: 45px;
    height: 100%;
    background: #003399;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    padding: 8px 0;
    position: relative;
    z-index: 10;
}

/* Right Provisional Strip (Red) */
.plate-strip-right-provisional {
    width: 45px;
    height: 100%;
    background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: space-between;
    padding: 8px 0;
    position: relative;
    z-index: 10;
}

/* Notification Transitions */
.slide-down-enter-active,
.slide-down-leave-active {
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.slide-down-enter-from {
    opacity: 0;
    transform: translateY(-100px) scale(0.9);
}

.slide-down-leave-to {
    opacity: 0;
    transform: translateX(100px) scale(0.9);
}

.plate-region-logo {
    width: 32px;
    height: 32px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.plate-dept {
    font-size: 22px;
    font-weight: 700;
    color: white;
    font-family: 'Oswald', 'Arial Narrow', sans-serif;
    letter-spacing: 0;
}

/* Responsive Plate */
@media (max-width: 1024px) {
    .license-plate {
        transform: scale(0.85);
        transform-origin: center;
    }
}

@media (max-width: 768px) {
    .license-plate {
        transform: scale(0.7);
        transform-origin: center;
    }
}

@media (max-width: 640px) {
    .license-plate {
        transform: scale(0.55);
        transform-origin: center;
    }
    
    .plate-preview-container {
        min-height: 200px !important;
        padding: 2rem !important;
    }
}
</style>
