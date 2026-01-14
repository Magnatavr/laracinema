import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/sass/app.scss'
            ],
            refresh: [
                {
                    paths: ['resources/views/**', 'resources/lang/**'],
                    config: { delay: 300 },
                },
            ],
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',
            port: 5173,
            protocol: 'ws',
        },
        cors: {
            origin: '*', // разрешить все origin
            credentials: true,
        },
        allowedHosts: ['.localhost'], // разрешить localhost и поддомены
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, PATCH, OPTIONS',
            'Access-Control-Allow-Headers': 'X-Requested-With, Content-Type, Authorization',
        },
        proxy: {}, // пустой proxy для предотвращения конфликтов
    },
    build: {
        rollupOptions: {
            external: [], // убедитесь что нет external модулей
        },
    },
});
