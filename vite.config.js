import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import laravel from "laravel-vite-plugin"

export default defineConfig({
  plugins: [
    laravel({
        input: "resources/js/app.js",
        publicDirectory: "public",
        buildDirectory: "build",
        refresh: true,
        hotFile: '../../../public/hot', 
    }),
    vue(),
  ],
  resolve: {
    alias: {
        '@': path.resolve(__dirname, 'resources/js'),
    },
  },
  build: {
    manifest: true,
    outDir: 'resources/dist/vendor', 
    emptyOutDir: true,
    rollupOptions: {
      input: 'resources/js/app.js'
    }
  },
  publicDir: 'resources/public',

})