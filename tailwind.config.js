/** @type {import('tailwindcss').Config} */

module.exports = {
    content: [
        './views/*.twig',
        './views/layouts/*.twig',
        './views/admin/*.twig',
        './node_modules/preline/dist/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                chinese: ['Hiragino Sans GB', 'Microsoft YaHei', '微軟正黑體', '蘋果儷中黑', 'SimSun', 'sans-serif'],
            }
        },
    },
    darkMode: 'media',
    plugins: [
        require('@tailwindcss/forms'),
        require('preline/plugin'),
    ],
}