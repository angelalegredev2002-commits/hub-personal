// vite.config.js

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // AÑADIR ESTO PARA FORZAR LA ESCUCHA EN TODAS LAS INTERFACES
    server: {
        host: '0.0.0.0', // Escuchar en todas las interfaces
        hmr: {
            host: 'localhost', // Decirle al navegador que busque en localhost
        },
    },
});