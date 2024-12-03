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
                sans: ['Helvetica', ...defaultTheme.fontFamily.sans],
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
            },
            keyframes: {
                wiggle: {
                    '0%, 100%': { transform: 'rotate(-10deg)' },
                    '50%': { transform: 'rotate(10deg)' },
                }
            },
            animation: {
                wiggle: 'wiggle 0.5s ease-in-out infinite',
            }
        },

    },

    plugins: [forms, typography],
};
