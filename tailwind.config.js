import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    dark: '#1a1a1a',
                    blue: '#004aad',
                    red: '#e63946',
                    orange: '#f4a261',
                    light: '#f8fafc'
                }
            },
            backgroundImage: {
                'brand-gradient': 'linear-gradient(135deg, #f4a261 0%, #e63946 50%, #004aad 100%)',
                'brand-gradient-hover': 'linear-gradient(135deg, #e76f51 0%, #d62828 50%, #023e8a 100%)',
            }
        },
    },

    plugins: [forms],
};
