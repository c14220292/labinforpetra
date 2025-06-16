/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'petra-orange': '#f7941d',
        'petra-blue': '#1d3c74',
        'petra-dark-blue': '#24345C',
      }
    },
  },
  plugins: [],
}