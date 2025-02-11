const defaultTheme = require("tailwindcss/defaultTheme");

import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography' 
import colors from 'tailwindcss/colors'


/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./node_modules/tw-elements/dist/js/**/*.js",
        './vendor/filament/**/*.blade.php', 

    ],

    theme: {
        extend: {
            boxShadow: {
                'top': '0px -3px 20px rgba(0, 0, 0, 0.1)',
            },
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            colors:{
                // danger:' #DF1B1B',
                // primary: '#057A55',
                secondary: '#41B02F',
                accent : '#BEEDB6',
                biru: '#104378',
                danger: colors.rose,
                primary: colors.sky,
                success: colors.green,
                warning: colors.yellow,

            }
        },
    },

    plugins: [
        forms,
        typography  
    ],
};