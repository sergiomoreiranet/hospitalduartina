import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    100: '#e6f7ff',
                    600: '#1a7894',
                    900: '#004358'
                },
                success: {
                    100: '#e6fff3',
                    600: '#00a86b',
                    900: '#004d31'
                },
                warning: {
                    100: '#fff7e6',
                    600: '#f6ad37',
                    900: '#8c5600'
                },
                info: {
                    100: '#e6f3ff',
                    600: '#0077cc',
                    900: '#003366'
                }
            }
        },
    },

    plugins: [forms],
};
