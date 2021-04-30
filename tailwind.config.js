const colors = require('tailwindcss/colors')

module.exports = {
  purge: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  darkMode: 'media', // or 'media' or 'class'
  theme: {
    extend: {},
    colors: {
      transparent: 'transparent',
      current: 'currentColor',
      black: colors.black,
      white: colors.white,
      gray: colors.trueGray,
      yellow: colors.amber,
      red: colors.red,
      green: colors.emerald,
    }
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
