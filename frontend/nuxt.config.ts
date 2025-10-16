import tsconfigPaths from 'vite-tsconfig-paths'
import { fileURLToPath } from 'url'

export default defineNuxtConfig({
  css: ['@/assets/css/main.css'],
  devServer: {
    port: 3000
  },
  vite: {
    plugins: [
      tsconfigPaths()
    ],
    resolve: {
      alias: {
        '~': fileURLToPath(new URL('./', import.meta.url)),
        '@': fileURLToPath(new URL('./', import.meta.url)),
      },
    },
  },
  modules: [
    '@pinia/nuxt',
    '@nuxtjs/tailwindcss'
  ],
  typescript: {
    strict: true
  },
  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8080',
    },
  },
  nitro: {
    output: {
      dir: '../public/_nuxt', // build into Laravel's public dir
    }
  }
})