import vuePlugin from 'eslint-plugin-vue';
import tsPlugin from '@typescript-eslint/eslint-plugin';

export default [
  {
    // Match all JS, TS, Vue files
    files: ['**/*.{ts,js,vue}'],
    languageOptions: {
      parser: require('vue-eslint-parser'), // parser for Vue SFCs
      parserOptions: {
        parser: require('@typescript-eslint/parser'), // parser for <script setup lang="ts">
        ecmaVersion: 2024,
        sourceType: 'module',
        ecmaFeatures: { jsx: true }, // for TSX/JSX support
      },
    },
    plugins: {
      vue: vuePlugin,
      '@typescript-eslint': tsPlugin,
    },
    rules: {
      'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
      'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
      'vue/multi-word-component-names': 'off',
    },
  },
  {
    ignores: ['node_modules/**', '.nuxt/**', 'dist/**', '*.md'],
  },
]