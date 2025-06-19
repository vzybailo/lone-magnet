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
        grey: '#555',
        red: '#FF0101',
        green: '#008000',
        blue: {
          DEFAULT: '#002868', 
          light: '#334a99',  
          dark: '#001f4d',  
        },
        wine: {
          DEFAULT: '#bf0a30',
          light: '#d84358',
          dark: '#80051f',
        }
      }
    },
  },
  plugins: [],
}

