export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        'fnbb-blue': '#009CB4',
        'fnbb-blue-dark': '#007A8C',
        'fnbb-orange': '#F6921E',
        'fnbb-orange-dark': '#E67E00',
        'fnbb-navy': '#003865',
        'fnbb-light-grey': '#F5F5F5',
        'fnbb-grey': '#757575',
        'fnbb-black': '#222222',
      },
      fontFamily: {
        'montserrat': ['Montserrat', 'sans-serif'],
        'open-sans': ['Open Sans', 'sans-serif'],
      }
    },
  },
  plugins: [],
}