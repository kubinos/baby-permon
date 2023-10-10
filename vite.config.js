import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { quasar, transformAssetUrls } from '@quasar/vite-plugin';

// reference: https://laravel.com/docs/10.x/vite#vue

export default defineConfig({
  plugins: [
    laravel(['resources/js/app.js']),
    vue({
      template: { transformAssetUrls }
    }),
    quasar({
      sassVariables: 'resources/js/quasar-variables.sass'
    })
  ]
});
