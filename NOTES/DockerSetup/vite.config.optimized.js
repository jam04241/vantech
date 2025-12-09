// vite.config.js optimization
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    // Split vendor code for better caching
                    vendor: ['alpinejs'],
                },
            },
        },
        // Minify and optimize
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Remove console.logs in production
            },
        },
        // Generate source maps for debugging
        sourcemap: false,
        // Chunk size warnings
        chunkSizeWarningLimit: 1000,
    },
    // Optimize dependencies
    optimizeDeps: {
        include: ['alpinejs'],
    },
});
