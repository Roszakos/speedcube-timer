/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      screens: {
        'cubesm': '520px'
      },
      keyframes: {
        'fade-in-down': {
          'from': {
            transform: 'translateY(-1.35rem)',
            opacity: 0
          },
          'to': {
            transform: 'translateY(0rem)',
            opacity: 1
          }
        }
      },
      animation: {
        'fade-in-down': "fade-in-down 0.4s ease-in-out both"
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}

