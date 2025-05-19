module.exports = {
  content: [
    "./*.php",
    "./assets/**/*.js",
    './blocks/**/*.js',
    './blocks/**/*.jsx',
    './blocks/**/*.php',
    './woocommerce/**/*.php',
  ],
  theme: {
    extend: {
      fontFamily: {
        reg: ['PoppinsRegular', 'sans-serif'],
        light: ['PoppinsLight', 'sans-serif']
      },
      colors: {
        gold: '#ffba00',
        gray: '#555',
        red: '#FF0101',
        green: '#008000'
      }
    },
  },
  plugins: [],
}

