const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./src/Resources/**/*.blade.php", "./src/Resources/**/*.js"],

    theme: {
        container: {
            center: true,

            screens: {
                "2xl": "1920px",
            },

            padding: {
                DEFAULT: "16px",
            },
        },

        screens: {
            sm: "525px",
            md: "768px",
            lg: "1024px",
            xl: "1240px",
            "2xl": "1920px",
        },

        extend: {
            colors: {
                darkGreen: '#40994A',
                darkBlue: '#0044F2',
                darkPink: '#F85156',

                primary: {
                    DEFAULT: '#FF9F43',
                    50: '#FFF5EC',
                    100: '#FFF5EC',
                    200: '#FFECD9',
                    300: '#FFE2C7',
                    400: '#FFD9B4',
                    500: '#FFCFA1',
                    600: '#FFC58E',
                    700: '#FFBC7B',
                    800: '#FFB269',
                    900: '#FFA956',
                },

                secondary: {
                    DEFAULT: '#092C4C',
                    100: '#E6EAED',
                    200: '#CED5DB',
                    300: '#B5C0C9',
                    400: '#9DABB7',
                    500: '#8496A6',
                    600: '#6B8094',
                    700: '#536B82',
                    800: '#3A5670',
                    900: '#22415E',
                },

                success: {
                    DEFAULT: '#28C76F',
                    100: '#EAF9F1',
                    200: '#D4F4E2',
                    300: '#BFEED4',
                    400: '#A9E9C5',
                    500: '#94E3B7',
                    600: '#7EDDA9',
                    700: '#69D89A',
                    800: '#53D28C',
                    900: '#3ECD7D',
                },

                warning: {
                    DEFAULT: '#FF9900',
                    100: '#FFF5E6',
                    200: '#FFEBCC',
                    300: '#FFE0B3',
                    400: '#FFD699',
                    500: '#FFCC80',
                    600: '#FFC266',
                    700: '#FFB84D',
                    800: '#FFAD33',
                    900: '#FFA31A',
                },

                danger: {
                    DEFAULT: '#FF0000',
                    100: '#FFE6E6',
                    200: '#FFCCCC',
                    300: '#FFB3B3',
                    400: '#FF9999',
                    500: '#FF8080',
                    600: '#FF6666',
                    700: '#FF4D4D',
                    800: '#FF3333',
                    900: '#FF1A1A',
                },

                info: '#17A2B8',
            },

            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
                inter: ['Inter'],
                icon: ['icomoon'],
                nunito: ['Nunito', ...defaultTheme.fontFamily.sans],
                poppins: ['Poppins', ...defaultTheme.fontFamily.sans],
            },

            borderRadius: {
                sm: '3px',
                DEFAULT: '4px',
                lg: '5px',
                xl: '8px',
                '2xl': '12px',
            },

            boxShadow: {
                DEFAULT: '0px 4px 60px 0px rgba(231, 231, 231, 0.47)',
                sm: '0px 4px 60px 0px rgba(190, 190, 190, 0.27)',
                lg: '0 5px 10px rgba(30, 32, 37, 0.12)',
            },
        },
    },
    
    darkMode: 'class',

    plugins: [],

    safelist: [
        {
            pattern: /icon-/,
        }
    ]
};
