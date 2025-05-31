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
        host: '0.0.0.0', // <--- Add this line
        port: 5173, // Ensure this is the port your Vite server runs on
        // Optionally, if you're using HTTPS locally:
        // https: true,
        hmr: {
            host: 'localhost', // For HMR, keep localhost or your specific IP
            clientPort: 5173,
        },
    },
});
