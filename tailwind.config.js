/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./views/app.blade.php",
    "./resources/**/*.{vue,js,ts,jsx,tsx,html,php}",
  ],
  theme: {
      container: {
        center: true,
    }
  },
  plugins: [],
}