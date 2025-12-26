<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const props = defineProps({
    vehicle: Object
});

const form = useForm({
    make: props.vehicle?.make || '',
    model: props.vehicle?.model || '',
    year: props.vehicle?.year || new Date().getFullYear(),
    type: props.vehicle?.type || 'sale',
    price: props.vehicle?.price || '',
    rent_price: props.vehicle?.rent_price || '',
    fuel: props.vehicle?.fuel || 'Essence',
    transmission: props.vehicle?.transmission || 'Manuelle',
    mileage: props.vehicle?.mileage || '',
    seats: props.vehicle?.seats || 5,
    main_image: null,
    gallery_images: [],
    badge_type: props.vehicle?.badge_type || '',
    is_available: props.vehicle?.is_available ?? true,
    is_featured: props.vehicle?.is_featured ?? false,
    remove_main_image: false,
    removed_gallery_images: [],
});

const mainImagePreview = ref(props.vehicle?.main_image || null);
const galleryPreviews = ref(props.vehicle?.images || []);
const newGalleryPreviews = ref([]);

const submit = () => {
    // Vérifier si au moins les champs requis sont remplis
    if (!form.make || !form.model) {
        alert('Veuillez remplir la marque et le modèle du véhicule');
        return;
    }

    const options = {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            console.log('Véhicule créé/mis à jour avec succès');
        },
        onError: (errors) => {
            console.error('Erreurs:', errors);
        }
    };
    
    if (props.vehicle) {
        // En mode édition, ne pas envoyer main_image si null (pour conserver l'existante)
        form.transform((data) => {
            const cleanData = { ...data };
            
            // Ne pas inclure main_image si null (pour conserver l'image existante)
            if (!data.main_image && !data.remove_main_image) {
                delete cleanData.main_image;
            }
            
            // Convertir gallery_images en tableau si nécessaire
            if (data.gallery_images && data.gallery_images.length > 0) {
                cleanData.gallery_images = Array.from(data.gallery_images);
            } else {
                delete cleanData.gallery_images;
            }
            
            cleanData._method = 'PUT';
            return cleanData;
        }).post(route('admin.vehicles.update', props.vehicle.id), options);
    } else {
        // En mode création
        form.transform((data) => {
            const cleanData = { ...data };
            
            // Convertir gallery_images en tableau
            if (data.gallery_images && data.gallery_images.length > 0) {
                cleanData.gallery_images = Array.from(data.gallery_images);
            }
            
            return cleanData;
        }).post(route('admin.vehicles.store'), options);
    }
};

const handleMainImageUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.main_image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            mainImagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handleGalleryUpload = (event) => {
    const files = event.target.files;
    if (!files || files.length === 0) return;
    
    // Ajouter les nouveaux fichiers
    const currentFiles = Array.from(form.gallery_images || []);
    const newFiles = Array.from(files);
    form.gallery_images = [...currentFiles, ...newFiles];
    
    // Créer des aperçus pour les nouveaux fichiers
    newFiles.forEach(file => {
        const reader = new FileReader();
        reader.onload = (e) => {
            newGalleryPreviews.value.push(e.target.result);
        };
        reader.readAsDataURL(file);
    });
    
    // Réinitialiser l'input pour permettre de sélectionner les mêmes fichiers à nouveau
    event.target.value = '';
};

const removeMainImage = () => {
    form.main_image = null;
    form.remove_main_image = true;
    mainImagePreview.value = null;
};

const removeGalleryImage = (index) => {
    const totalExisting = galleryPreviews.value.length;
    const newIndex = index - totalExisting;
    
    // Si c'est une image existante
    if (index < totalExisting) {
        const removedImage = galleryPreviews.value[index];
        form.removed_gallery_images.push(removedImage);
        galleryPreviews.value.splice(index, 1);
    } else {
        // Si c'est une nouvelle image
        newGalleryPreviews.value.splice(newIndex, 1);
        const filesArray = Array.from(form.gallery_images);
        filesArray.splice(newIndex, 1);
        form.gallery_images = filesArray;
    }
};
</script>

<template>
    <Head :title="vehicle ? 'Modifier Véhicule' : 'Ajouter Véhicule'" />

    <AdminLayout>
        <template #header>
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <h2 class="font-bold text-2xl text-brand-dark leading-tight">
                        {{ vehicle ? 'Modifier Véhicule' : 'Ajouter un Véhicule' }}
                    </h2>
                    <p class="text-slate-500 mt-1">Remplissez les informations du véhicule</p>
                </div>
                <Link :href="route('admin.vehicles.index')" class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 hover:text-brand-dark transition-colors font-medium flex items-center justify-center gap-2 w-full md:w-auto">
                    <i class="ph-bold ph-arrow-left"></i>
                    Retour
                </Link>
            </div>
        </template>

        <div class="w-full max-w-full bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-4 md:p-8">
                <form @submit.prevent="submit" class="space-y-6 md:space-y-8">
                    <!-- Section: Informations Principales -->
                    <div>
                        <h3 class="text-lg font-bold text-brand-dark mb-4 flex items-center gap-2">
                            <i class="ph-fill ph-info text-brand-blue"></i>
                            Informations Principales
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Make -->
                            <div>
                                <InputLabel for="make" value="Marque" class="text-slate-700 font-medium" />
                                <TextInput id="make" type="text" class="mt-1 block w-full border-slate-200 focus:border-brand-blue focus:ring-brand-blue/20 rounded-lg" v-model="form.make" required placeholder="Ex: Peugeot" />
                                <InputError class="mt-2" :message="form.errors.make" />
                            </div>

                            <!-- Model -->
                            <div>
                                <InputLabel for="model" value="Modèle" class="text-slate-700 font-medium" />
                                <TextInput id="model" type="text" class="mt-1 block w-full border-slate-200 focus:border-brand-blue focus:ring-brand-blue/20 rounded-lg" v-model="form.model" required placeholder="Ex: 308" />
                                <InputError class="mt-2" :message="form.errors.model" />
                            </div>

                            <!-- Year -->
                            <div>
                                <InputLabel for="year" value="Année" class="text-slate-700 font-medium" />
                                <TextInput id="year" type="number" class="mt-1 block w-full border-slate-200 focus:border-brand-blue focus:ring-brand-blue/20 rounded-lg" v-model="form.year" required />
                                <InputError class="mt-2" :message="form.errors.year" />
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100" />

                    <!-- Section: Détails Techniques -->
                    <div>
                        <h3 class="text-lg font-bold text-brand-dark mb-4 flex items-center gap-2">
                            <i class="ph-fill ph-engine text-brand-orange"></i>
                            Détails Techniques
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Fuel -->
                            <div>
                                <InputLabel for="fuel" value="Carburant" class="text-slate-700 font-medium" />
                                <select id="fuel" class="mt-1 block w-full border-slate-200 focus:border-brand-blue focus:ring-brand-blue/20 rounded-lg shadow-sm" v-model="form.fuel">
                                    <option value="Essence">Essence</option>
                                    <option value="Diesel">Diesel</option>
                                    <option value="Hybride">Hybride</option>
                                    <option value="Électrique">Électrique</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.fuel" />
                            </div>

                            <!-- Transmission -->
                            <div>
                                <InputLabel for="transmission" value="Boîte de vitesse" class="text-slate-700 font-medium" />
                                <select id="transmission" class="mt-1 block w-full border-slate-200 focus:border-brand-blue focus:ring-brand-blue/20 rounded-lg shadow-sm" v-model="form.transmission">
                                    <option value="Manuelle">Manuelle</option>
                                    <option value="Auto">Automatique</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.transmission" />
                            </div>

                            <!-- Mileage -->
                            <div>
                                <InputLabel for="mileage" value="Kilométrage" class="text-slate-700 font-medium" />
                                <div class="relative mt-1">
                                    <TextInput id="mileage" type="number" class="block w-full border-slate-200 focus:border-brand-blue focus:ring-brand-blue/20 rounded-lg pr-8" v-model="form.mileage" />
                                    <span class="absolute right-3 top-2.5 text-slate-400 text-sm">km</span>
                                </div>
                                <InputError class="mt-2" :message="form.errors.mileage" />
                            </div>

                            <!-- Seats -->
                            <div>
                                <InputLabel for="seats" value="Places" class="text-slate-700 font-medium" />
                                <TextInput id="seats" type="number" class="mt-1 block w-full border-slate-200 focus:border-brand-blue focus:ring-brand-blue/20 rounded-lg" v-model="form.seats" />
                                <InputError class="mt-2" :message="form.errors.seats" />
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-100" />

                    <!-- Section: Offre & Prix -->
                    <div>
                        <h3 class="text-lg font-bold text-brand-dark mb-4 flex items-center gap-2">
                            <i class="ph-fill ph-tag text-brand-red"></i>
                            Offre & Prix
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Type -->
                            <div>
                                <InputLabel for="type" value="Type d'offre" class="text-slate-700 font-medium" />
                                <select id="type" class="mt-1 block w-full border-slate-200 focus:border-brand-blue focus:ring-brand-blue/20 rounded-lg shadow-sm" v-model="form.type">
                                    <option value="sale">Vente</option>
                                    <option value="rent">Location</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.type" />
                            </div>

                            <!-- Price (Sale) -->
                            <div v-if="form.type === 'sale'">
                                <InputLabel for="price" value="Prix Vente" class="text-slate-700 font-medium" />
                                <div class="relative mt-1">
                                    <TextInput id="price" type="number" step="0.01" class="block w-full border-slate-200 focus:border-brand-blue focus:ring-brand-blue/20 rounded-lg pr-8" v-model="form.price" />
                                    <span class="absolute right-3 top-2.5 text-slate-400 text-sm">€</span>
                                </div>
                                <InputError class="mt-2" :message="form.errors.price" />
                            </div>

                            <!-- Price (Rent) -->
                            <div v-if="form.type === 'rent'">
                                <InputLabel for="rent_price" value="Prix Location" class="text-slate-700 font-medium" />
                                <div class="relative mt-1">
                                    <TextInput id="rent_price" type="number" step="0.01" class="block w-full border-slate-200 focus:border-brand-blue focus:ring-brand-blue/20 rounded-lg pr-12" v-model="form.rent_price" />
                                    <span class="absolute right-3 top-2.5 text-slate-400 text-sm">€/jour</span>
                                </div>
                                <InputError class="mt-2" :message="form.errors.rent_price" />
                            </div>
                            
                            <!-- Badge -->
                            <div>
                                <InputLabel for="badge_type" value="Badge (Optionnel)" class="text-slate-700 font-medium" />
                                <TextInput id="badge_type" type="text" class="mt-1 block w-full border-slate-200 focus:border-brand-blue focus:ring-brand-blue/20 rounded-lg" v-model="form.badge_type" placeholder="Ex: COUP DE CŒUR" />
                                <InputError class="mt-2" :message="form.errors.badge_type" />
                            </div>
                        </div>

                        <!-- Availability -->
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center p-4 border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50 transition-colors">
                                <input type="checkbox" class="rounded border-slate-300 text-brand-blue shadow-sm focus:ring-brand-blue" v-model="form.is_available" />
                                <span class="ml-3 text-sm font-medium text-slate-700 flex items-center gap-2">
                                    <i class="ph-fill ph-eye text-brand-blue"></i>
                                    Disponible sur le site
                                </span>
                            </label>
                            <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all" 
                                :class="form.is_featured ? 'border-brand-orange bg-brand-orange/5' : 'border-slate-200 hover:bg-slate-50'">
                                <input type="checkbox" class="rounded border-slate-300 text-brand-orange shadow-sm focus:ring-brand-orange" v-model="form.is_featured" />
                                <span class="ml-3 text-sm font-medium flex items-center gap-2" 
                                    :class="form.is_featured ? 'text-brand-orange' : 'text-slate-700'">
                                    <i class="ph-fill ph-star"></i>
                                    Véhicule vedette (Hero)
                                </span>
                            </label>
                        </div>
                    </div>

                    <hr class="border-slate-100" />

                    <!-- Images -->
                    <div>
                        <h3 class="text-lg font-bold text-brand-dark mb-4 flex items-center gap-2">
                            <i class="ph-fill ph-image text-purple-500"></i>
                            Photos du Véhicule
                        </h3>
                        
                        <!-- Main Image -->
                        <div class="mb-6">
                            <InputLabel value="Image principale (Photo de couverture)" class="text-slate-700 font-medium mb-3" />
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                <div v-if="mainImagePreview" class="relative group mb-3">
                                    <img :src="mainImagePreview" class="w-full h-64 object-cover rounded-lg border-2 border-brand-blue shadow-lg" alt="Image principale"/>
                                    <button type="button" @click="removeMainImage" class="absolute top-3 right-3 bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors shadow-lg opacity-0 group-hover:opacity-100">
                                        <i class="ph-bold ph-trash text-lg"></i>
                                    </button>
                                    <div class="absolute bottom-3 left-3 bg-brand-blue text-white px-3 py-1 rounded-lg text-xs font-bold">
                                        <i class="ph-fill ph-star mr-1"></i>
                                        Photo principale
                                    </div>
                                </div>
                                
                                <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 rounded-lg p-6 cursor-pointer hover:border-brand-blue hover:bg-blue-50 transition-all group">
                                    <div class="w-16 h-16 rounded-full bg-brand-blue/10 flex items-center justify-center mb-3 group-hover:bg-brand-blue group-hover:scale-110 transition-all">
                                        <i class="ph-bold ph-image text-3xl text-brand-blue group-hover:text-white"></i>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-600 group-hover:text-brand-blue">Choisir l'image principale</span>
                                    <span class="text-xs text-slate-400 mt-1">JPG, PNG ou WEBP (max 5 MB)</span>
                                    <input type="file" class="hidden" accept="image/jpeg,image/png,image/jpg,image/webp" @change="handleMainImageUpload" />
                                </label>
                                <InputError class="mt-2" :message="form.errors.main_image" />
                                <InputError v-if="form.errors['gallery_images.0']" class="mt-2" message="Une ou plusieurs images de la galerie sont trop volumineuses (max 5 MB par image)" />
                            </div>
                        </div>

                        <!-- Gallery Images -->
                        <div>
                            <InputLabel value="Galerie d'images (Plusieurs photos)" class="text-slate-700 font-medium mb-3" />
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-200">
                                <div v-if="galleryPreviews.length > 0 || newGalleryPreviews.length > 0" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-4">
                                    <!-- Images existantes -->
                                    <div v-for="(preview, index) in galleryPreviews" :key="'existing-' + index" class="relative group aspect-[4/3]">
                                        <img :src="preview" class="w-full h-full object-cover rounded-lg border border-slate-200 shadow-sm" />
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                            <button type="button" @click="removeGalleryImage(index)" class="bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors transform hover:scale-110">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </div>
                                        <div class="absolute top-2 left-2 bg-brand-blue/80 text-white px-2 py-1 rounded text-xs font-bold">
                                            #{{ index + 1 }}
                                        </div>
                                    </div>
                                    
                                    <!-- Nouvelles images -->
                                    <div v-for="(preview, index) in newGalleryPreviews" :key="'new-' + index" class="relative group aspect-[4/3]">
                                        <img :src="preview" class="w-full h-full object-cover rounded-lg border-2 border-green-400 shadow-sm" />
                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                                            <button type="button" @click="removeGalleryImage(galleryPreviews.length + index)" class="bg-red-500 text-white rounded-full p-2 hover:bg-red-600 transition-colors transform hover:scale-110">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </div>
                                        <div class="absolute top-2 left-2 bg-green-500 text-white px-2 py-1 rounded text-xs font-bold">
                                            <i class="ph-fill ph-plus mr-1"></i> NEW
                                        </div>
                                    </div>
                                </div>
                                
                                <label class="flex flex-col items-center justify-center border-2 border-dashed border-slate-300 rounded-lg p-6 cursor-pointer hover:border-purple-500 hover:bg-purple-50 transition-all group">
                                    <div class="w-16 h-16 rounded-full bg-purple-500/10 flex items-center justify-center mb-3 group-hover:bg-purple-500 group-hover:scale-110 transition-all">
                                        <i class="ph-bold ph-images text-3xl text-purple-500 group-hover:text-white"></i>
                                    </div>
                                    <span class="text-sm font-semibold text-slate-600 group-hover:text-purple-600">Ajouter des photos à la galerie</span>
                                    <span class="text-xs text-slate-400 mt-1">Sélectionnez plusieurs images (JPG, PNG ou WEBP, max 5 MB chacune)</span>
                                    <input type="file" class="hidden" accept="image/jpeg,image/png,image/jpg,image/webp" multiple @change="handleGalleryUpload" />
                                </label>
                                <div v-if="Object.keys(form.errors).some(key => key.startsWith('gallery_images.'))" class="mt-2 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3">
                                    <i class="ph-fill ph-warning-circle mr-2"></i>
                                    Une ou plusieurs images de la galerie sont trop volumineuses (max 5 MB par image)
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-6 border-t border-slate-100 gap-4">
                        <Link :href="route('admin.vehicles.index')" class="text-slate-500 hover:text-slate-700 font-medium">
                            Annuler
                        </Link>
                        <button 
                            type="submit" 
                            class="bg-brand-blue hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 px-8 py-3 text-base rounded-lg text-white font-semibold inline-flex items-center justify-center transition-colors disabled:opacity-50 disabled:cursor-not-allowed" 
                            :disabled="form.processing"
                        >
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <i v-else class="ph-bold ph-check mr-2"></i>
                            <span v-if="form.processing">Enregistrement...</span>
                            <span v-else>{{ vehicle ? 'Mettre à jour le véhicule' : 'Créer le véhicule' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>