/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
	   './vitepress/theme/**/*.{vue,js,ts,jsx,tsx}',
	   './src/**/*.{md,svg}',
	],
  theme: {
    extend: {},
  },
  plugins: [
	    require('@tailwindcss/typography'),
	    require('@tailwindcss/forms')
	],
}
