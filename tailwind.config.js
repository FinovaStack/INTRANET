const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
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
                primary: '#22c55e',
                secondary: '#95a0c5',
                success: '#22c5ad',
                info: '#41cbd8',
                warning: '#ff9f43',
                danger: '#ef4d56',
                pink: '#fd3c97',
                purple: '#7367f0',
                blue: '#0d6efd',
                light: '#f4f6f9',
                dark: '#2b2d3b',
                gray: {
                    100: '#f4f6f9',
                    200: '#eaeff5',
                    300: '#d9e1ec',
                    400: '#c1cde0',
                    500: '#a8b5d1',
                    600: '#95a0c5',
                    700: '#7a82b1',
                    800: '#555b7e',
                    900: '#2b2d3b',
                },
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
