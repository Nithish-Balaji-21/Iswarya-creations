/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Livewire/**/*.php',
        './app/Enums/**/*.php',
    ],
    theme: {
        extend: {
            colors: {
                maroon: {
                    DEFAULT: '#b1474b',
                    hover: '#8f1f23',
                    tint: '#fbebee',
                    dark: '#681518',
                },
                gold: {
                    DEFAULT: '#c59b27',
                    light: '#fef9e7',
                    dark: '#9a7516',
                },
                brand: {
                    white: '#ffffff',
                    warm: '#fdf7f3',
                    pearl: '#f8f6f4',
                    text: '#1a1a1a',
                    secondary: '#575757',
                    muted: '#8c8c8c',
                },
            },
            fontFamily: {
                sans: ['Jost', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                jost: ['Jost', 'sans-serif'],
            },
        },
    },
    plugins: [],
};
