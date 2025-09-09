module.exports = {
    root: true,
    env: {
        browser: true,
        node: true,
    },
    parser: 'vue-eslint-parser',
    parserOptions: {
        parser: '@typescript-eslint/parser',
        ecmaVersion: 2024,
        sourceType: 'module',
    },
    extends: [
        'eslint:recommended',
        'plugin:vue/vue3-recommended',
        'plugin:@typescript-eslint/recommended',
    ],
    plugins: ['vue', '@typescript-eslint'],
    rules: {
        'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
        'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
        'vue/multi-word-component-names': 'off', // disable for now
    },
    overrides: [
        {
        files: ['*.vue'], // only apply Vue rules to Vue files
        rules: {
            'vue/multi-word-component-names': 'error', // enable only for Vue files
        },
        },
        {
        files: ['*.ts', '*.js'], // disable Vue rules for JS/TS files
        rules: {
            'vue/multi-word-component-names': 'off',
        },
        },
        {
        files: ['*.md'], // ignore markdown files
        rules: {
            'vue/*': 'off',
        },
        },
    ]
}  