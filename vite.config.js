import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            // Nothing Vite serves lives here. Skipping these keeps file watching
            // light, which matters when Docker has to poll a Windows drive.
            ignored: ['**/vendor/**', '**/storage/**', '**/bootstrap/cache/**'],
        },
    },
    css: {
        postcss: {
            plugins: [
                require('tailwindcss'),
                require('autoprefixer'),
            ],
        },
    },
});
