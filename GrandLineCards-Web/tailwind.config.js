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
            colors: {
                grand: {
                    900: '#001524', // Deep Navy (Background)
                    800: '#0F172A', // Dark Navy
                    700: '#15616D', // Ocean Teal (Secondary)
                    500: '#3B82F6', // Ocean Blue
                    gold: '#FFC300', // Epic Gold
                    accent: '#FFD60A', // Bright Gold
                    bone: '#FFF8E7', // Bone White (Text)
                }
            },
            fontFamily: {
                sans: ['Montserrat', 'sans-serif'],
                display: ['Cinzel', 'serif'],
                body: ['Poppins', 'sans-serif'],
            },
            backgroundImage: {
                // 'sea-pattern': "url('/images/sea-pattern.svg')", // Removed for optimization
                // 'paper-texture': "url('/images/paper-texture.png')", // Removed for optimization
            },
            dropShadow: {
                'foil': '0 0 10px rgba(255, 195, 0, 0.5)',
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
