import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/css/app.css',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        chunkSizeWarningLimit: 1024,
        rollupOptions: {
            onwarn(warning, warn) {
                if (warning.code === 'EVAL' && warning.id?.includes('exceljs')) return;
                warn(warning);
            }
        }
    },
    test: {
        environment: 'jsdom',
        globals: true,
        setupFiles: 'tests/setup.ts',
        css: true,
    },
});
