/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
    "./src/**/*.{html,js}",
    ".node_modules/flowbite-vue/**/*.{js,jsx,ts,tsx}",
    ".node_modules/flowbite/**/*.{js,jsx,ts,tsx}",
    "./node_modules/flowbite/**/*.js",
  ],
  theme: {
    screens: {
      sm: "640px",
      md: "768px",
      lg: "1024px",
      xl: "1280px",
    },
    extend: {
      gridTemplateColumns: {
        4: "repeat(4, minmax(0, 1fr))",
      },
    },
  },
  plugins: [require("flowbite/plugin")],
};
