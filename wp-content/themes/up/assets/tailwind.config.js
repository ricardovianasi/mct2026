const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
  content: [
    './app/*.html',
    './app/styles/**/*.scss',
    './**/*/*.php'
  ],
  theme: {
    container: {
      center: true,
      padding: '16px',
      screens: {
        xl: '1216px'
      }
    },
    extend: {
      colors: {
        neutral: {
          100: '#FFFFFF',
          500: '#1E2525',
          900: '#000000'
        },
        primary: {
          DEFAULT: '#DC3928'
        },
        secondary: {
          DEFAULT: '#FF5800'
        },
        tertiary: {
          DEFAULT: '#FFD600'
        },
        bege: {
          DEFAULT: '#EEEDE7'
        },
        body: '#1E2525'
      },
      maxWidth: {
        content: '951px',
        'content-wrapper': '1008px',
        'page-section': '1200px'
      }
    }
  }
}
