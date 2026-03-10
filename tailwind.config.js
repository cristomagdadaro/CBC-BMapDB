import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Roboto', ...defaultTheme.fontFamily.sans],
                display: ['Montserrat', 'Roboto', ...defaultTheme.fontFamily.sans],
                body: ['Inter', 'Roboto', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                'xs': '.75rem',
                'sm': '.875rem',
                'base': '1rem',
                'lg': '1.125rem',
                'xl': '1.25rem',
                '2xl': '1.5rem',
                '3xl': '1.875rem',
                '4xl': '2.25rem',
                '5xl': '3rem',
                '6xl': '4rem',
            },
            colors: {
                'cbc-yellow-green': '#acc638',
                'cbc-dark-green': '#006837',
                'cbc-light-green': '#d3d75e',
                'cbc-olive-green': '#acc638',
                'cbc-yellow': '#F7C806',
                'cbc-brown': '#41291d',
                'cbc-footer': '#212120',
                'add': '#005B41',
                'view': '#F7C806',
                'edit': '#95BB76',
                'delete': '#F23A3A',
                'refresh': '#219C90',
                'export': '#00a9ff',
                'import': '#ff8e00',
                'select': '#E08E6D',
                'deselect': '#D25380',
                'pin-green': '#036701',
                'pin-green-dark': '#014001',
                'pin-green-light': '#e6f2e6',
                'pin-lime': '#65e701',
                'pin-gray': '#f5f5f5',
            },
            borderRadius: {
                'xl': '0.875rem',
                '2xl': '1rem',
            },
            boxShadow: {
                'xs': '0 1px 2px 0 rgb(0 0 0 / 0.05)',
                'glass': '0 8px 32px 0 rgba(0, 0, 0, 0.1)',
                'card': '0 4px 24px 0 rgba(0, 0, 0, 0.08)',
            },
            keyframes: {
                wiggle: {
                    '0%, 100%': { transform: 'rotate(-10deg)' },
                    '50%': { transform: 'rotate(10deg)' },
                },
                'fade-up': {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                'slide-in-right': {
                    '0%': { transform: 'translateX(100%)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                },
                'slide-in-left': {
                    '0%': { transform: 'translateX(-100%)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                },
                'scale-in': {
                    '0%': { transform: 'scale(0.9)', opacity: '0' },
                    '100%': { transform: 'scale(1)', opacity: '1' },
                },
                'marquee': {
                    '0%': { transform: 'translateX(0%)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
                'float': {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                'pulse-ring': {
                    '0%': { transform: 'scale(0.8)', opacity: '1' },
                    '100%': { transform: 'scale(2)', opacity: '0' },
                },
            },
            animation: {
                wiggle: 'wiggle 0.5s ease-in-out infinite',
                'fade-up': 'fade-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'fade-in': 'fade-in 0.4s ease-out forwards',
                'slide-in-right': 'slide-in-right 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'slide-in-left': 'slide-in-left 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'scale-in': 'scale-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'marquee': 'marquee 30s linear infinite',
                'float': 'float 6s ease-in-out infinite',
                'pulse-ring': 'pulse-ring 1.5s cubic-bezier(0.215, 0.61, 0.355, 1) infinite',
            }
        },

    },

    plugins: [forms, typography],
};
