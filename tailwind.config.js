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
                // GreenRoute — dark-teal primary, with bright-green accent
                primary: {
                    50:  '#e6f4f4',
                    100: '#cfe9e9',
                    200: '#9fd3d3',
                    300: '#6fbcbc',
                    400: '#3fa6a6',
                    500: '#1f8f8f',   /* bright dark-teal */
                    600: '#177575',   /* main brand dark-teal */
                    700: '#0f5c5c',   /* deeper dark-teal */
                    800: '#0a4747',
                    900: '#063232',
                    950: '#031f1f',
                },
                // Keep a "brand" alias for legacy CSS that used #055c5c
                brand: {
                    DEFAULT: '#055c5c',
                    50:  '#e6f4f4',
                    100: '#cfe9e9',
                    200: '#9fd3d3',
                    300: '#6fbcbc',
                    400: '#3fa6a6',
                    500: '#1f8f8f',
                    600: '#177575',
                    700: '#0f5c5c',
                    800: '#0a4747',
                    900: '#063232',
                },
                teal: {
                    50:  '#e6f4f4',
                    100: '#cfe9e9',
                    200: '#9fd3d3',
                    300: '#6fbcbc',
                    400: '#3fa6a6',
                    500: '#1f8f8f',
                    600: '#177575',
                    700: '#0f5c5c',
                    800: '#0a4747',
                    900: '#063232',
                },
                // Bright green accent (the "main" color in the brief)
                accent: {
                    DEFAULT: '#22c55e',
                    50:  '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                },
            },
        },
    },
    plugins: [forms],
};
