/** @type {import('tailwindcss').Config} */

module.exports = {
    content: [
        './views/*.twig',
        './views/layouts/*.twig'
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}

