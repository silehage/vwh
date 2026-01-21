import { fileURLToPath } from 'node:url'
import { wayfinder } from '@laravel/vite-plugin-wayfinder';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';
import { quasar, transformAssetUrls } from '@quasar/vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        vue({
            template: { transformAssetUrls }
        }),
        wayfinder({
            formVariants: true,
        }),
        quasar({
            sassVariables: fileURLToPath(
                new URL('resources/css/quasar-variables.sass', import.meta.url)
            )
        }),
    ],
});
