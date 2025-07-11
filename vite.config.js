import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'


export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/icons.css',
                'resources/css/tailwind.css',
                'resources/js/app.js',
                'resources/js/smooth-scroll.polyfills.min.js',
                'resources/js/gumshoe.polyfills.min.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        https: true,
    },
});

