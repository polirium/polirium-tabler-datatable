import { defineConfig } from 'vite';
import { resolve } from 'path';
import fs from 'fs';

export default defineConfig({
    build: {
        lib: {
            entry: resolve(__dirname, 'resources/js/powergrid.js'),
            name: 'PowerGrid',
            fileName: () => 'powergrid.js',
            formats: ['iife']
        },
        rollupOptions: {
            output: {
                globals: {},
                assetFileNames: '[name][extname]'
            }
        },
        outDir: 'dist',
        emptyOutDir: true,
        cssCodeSplit: false
    },
    plugins: [
        {
            name: 'copy-css',
            closeBundle() {
                // Copy CSS files to dist
                const cssDir = resolve(__dirname, 'resources/css');
                const distDir = resolve(__dirname, 'dist');

                const cssFiles = ['base.css', 'editable.css', 'tabler.css', 'bootstrap5.css', 'mobile-card.css'];

                cssFiles.forEach(file => {
                    const src = resolve(cssDir, file);
                    const dest = resolve(distDir, file);
                    if (fs.existsSync(src)) {
                        fs.copyFileSync(src, dest);
                        console.log(`Copied: ${file}`);
                    }
                });
            }
        }
    ]
});
