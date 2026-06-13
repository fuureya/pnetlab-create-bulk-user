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
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                poppins: ['Poppins', 'sans-serif'],
                mono: ['Space Mono', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                shop: {
                    primary: '#BF070F',
                    secondary: '#8F050A',
                    tertiary: '#E60914',
                    background: '#FAFAFA',
                    surface: '#FFFFFF',
                    success: '#22C55E',
                    warning: '#F59E0B',
                    error: '#EF4444',
                    info: '#3B82F6',
                }
            },
            boxShadow: {
                'shop-subtle': '0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04)',
                'shop-md': '0 4px 6px rgba(0,0,0,0.07), 0 2px 4px rgba(0,0,0,0.05)',
                'shop-lg': '0 10px 25px rgba(0,0,0,0.10), 0 6px 10px rgba(0,0,0,0.06)',
                'shop-overlay': '0 25px 50px rgba(0,0,0,0.15), 0 12px 24px rgba(0,0,0,0.08)',
                'shop-hover': '0 14px 32px rgba(191,7,15,0.15), 0 6px 12px rgba(0,0,0,0.06)',
            }
        },
    },

    plugins: [forms],
};
