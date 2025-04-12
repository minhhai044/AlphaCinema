import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    define: {
        'process.env': {}, // Cần thiết để Echo không lỗi
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/roomChat.js',
                'resources/js/notification.js',
            ],
            refresh: true,
        }),
    ],
});
