/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './public/templates/**/*.html',
    './public/js/**/*.js',
    './includes/**/*.php',
    './admin/**/*.php'
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#007bff',
          50: '#e6f2ff',
          100: '#cce5ff',
          200: '#99ccff',
          300: '#66b2ff',
          400: '#3399ff',
          500: '#007bff',
          600: '#0062cc',
          700: '#004a99',
          800: '#003166',
          900: '#001933'
        },
        secondary: {
          DEFAULT: '#6c757d',
          50: '#f8f9fa',
          100: '#e9ecef',
          200: '#dee2e6',
          300: '#ced4da',
          400: '#adb5bd',
          500: '#6c757d',
          600: '#5a6268',
          700: '#495057',
          800: '#343a40',
          900: '#212529'
        }
      },
      spacing: {
        '18': '4.5rem',
        '22': '5.5rem',
        '26': '6.5rem'
      },
      animation: {
        'pulse-subtle': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
        'slide-up': 'slideUp 0.3s ease-out',
        'fade-in': 'fadeIn 0.3s ease-out'
      },
      keyframes: {
        slideUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' }
        },
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' }
        }
      }
    }
  },
  plugins: [],
  safelist: [
    'bg-primary',
    'text-primary',
    'border-primary',
    {
      pattern: /bg-(red|green|yellow|blue)-(100|200|300|400|500|600|700|800|900)/
    }
  ]
}
