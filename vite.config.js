import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', // This has Tailwind directives
                'resources/sass/app.scss', // This has your custom SASS
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});