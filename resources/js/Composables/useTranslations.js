import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export function useTranslations() {
    const page = usePage();
    
    const translations = computed(() => page.props.translations || {});
    const locale = computed(() => page.props.locale || 'fr');
    
    const __ = (key, replacements = {}) => {
        let translation = translations.value[key] || key;
        
        // Remplacer les placeholders :name par les valeurs
        Object.keys(replacements).forEach(placeholder => {
            translation = translation.replace(`:${placeholder}`, replacements[placeholder]);
        });
        
        return translation;
    };
    
    return {
        __,
        translations,
        locale
    };
}
