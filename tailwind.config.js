import defaultTheme from 'tailwindcss/defaultTheme';
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Montserrat', ...defaultTheme.fontFamily.sans],
                orbitron: ['Orbitron', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                zenith: {
                    bg: 'var(--zenith-bg)',
                    sidebar: 'var(--zenith-sidebar)',
                    text: 'var(--zenith-text)',
                    textLight: 'var(--zenith-text-light)',
                    card: 'var(--zenith-card)',
                    border: 'var(--zenith-border)',
                    primary: 'var(--zenith-primary)',
                    hover: 'var(--zenith-hover)',
                    orange: 'var(--zenith-orange)',
                    teal: 'var(--zenith-teal)',
                    blue: 'var(--zenith-blue)',
                    yellow: 'var(--zenith-yellow)'
                }
            },
            animation: {
                'spin-slow': 'spin 3s linear infinite',
                'pulse-glow': 'pulse-glow 2s ease-in-out infinite',
                'fade-in-up': 'fade-in-up 0.6s ease-out',
                'slide-in': 'slide-in-from-left 0.5s ease-out',
                'bounce-subtle': 'bounce-subtle 2s ease-in-out infinite',
                'float': 'float 3s ease-in-out infinite',
                'scale-pulse': 'scale-pulse 2s ease-in-out infinite',
            },
            keyframes: {
                'spin-slow': {
                    'from': { transform: 'rotate(0deg)' },
                    'to': { transform: 'rotate(360deg)' },
                },
                'pulse-glow': {
                    '0%, 100%': { opacity: '1', boxShadow: '0 0 20px rgba(14, 165, 233, 0.5)' },
                    '50%': { opacity: '0.7', boxShadow: '0 0 40px rgba(14, 165, 233, 0.8)' },
                },
                'fade-in-up': {
                    'from': { opacity: '0', transform: 'translateY(20px)' },
                    'to': { opacity: '1', transform: 'translateY(0)' },
                },
                'slide-in-from-left': {
                    'from': { opacity: '0', transform: 'translateX(-30px)' },
                    'to': { opacity: '1', transform: 'translateX(0)' },
                },
                'bounce-subtle': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-8px)' },
                },
                'float': {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
                'scale-pulse': {
                    '0%, 100%': { transform: 'scale(1)' },
                    '50%': { transform: 'scale(1.05)' },
                },
            },
        },
    },

    plugins: [],
};
