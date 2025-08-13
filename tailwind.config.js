/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                dark: {
                    bg: '#0b0f14',
                    surface: '#111827',
                    surface2: '#0f172a',
                },
                accent: {
                    primary: '#FDE047',
                    secondary: '#FACC15',
                }
            },
            fontFamily: {
                'sans': ['Inter', 'Poppins', 'system-ui'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('daisyui'),
    ],
    daisyui: {
        themes: ["dark", "light"],
    },
}