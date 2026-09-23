import js from '@eslint/js';
import globals from 'globals';

export default [
    {
        ignores: ['public/build/**', 'node_modules/**'],
    },
    js.configs.recommended,
    {
        files: ['resources/js/**/*.js'],
        languageOptions: {
            ecmaVersion: 'latest',
            globals: globals.browser,
            sourceType: 'module',
        },
    },
];
