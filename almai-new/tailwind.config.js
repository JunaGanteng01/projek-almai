/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./app/Views/**/*.php",
        "./public/**/*.{html,js}",
    ],
    theme: {
        extend: {
            colors: {
                'accent': '#33e818',
            },
            fontFamily: {
                sans: ['Montserrat', 'sans-serif'],
            },
        },
    },
    plugins: [],
}
