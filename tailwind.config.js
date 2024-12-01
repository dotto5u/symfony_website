/** @type { import('tailwindcss').Config } */
export const content = [
  "./assets/**/*.js",
  "./templates/**/*.html.twig",
];
export const theme = {
  extend: {
    screens: {
      'xs': '396px',
    },
    fontFamily: {
      'fcp': ['Roboto', 'sans']
    },
    colors: {
      'success-light': '#A8D5BA',
      'success-dark': '#2E7D32',
      'information-light': '#B3E5FC',
      'information-dark': '#0288D1',
      'error-light': '#FFCDD2',
      'error-dark': '#C62828',
      'fcp-blue-light': '#1E2A47',
      'fcp-blue-dark': '#0D1B2A',
      'fcp-white': '#EAEAEA',
    },
    keyframes: {
      fadeIn: {
        '0%': { opacity: '0' },
        '100%': { opacity: '1' },
      },
      slideIn: {
        '0%': { transform: 'translateX(100%)', opacity: '0' },
        '100%': { transform: 'translateX(0)', opacity: '1' },
      },
      slideOut: {
        '0%': { transform: 'translateX(0)', opacity: '1' },
        '100%': { transform: 'translateX(100%)', opacity: '0' },
      },
    },
    animation: {
      'fade-in' : 'fadeIn 0.5s ease-in-out',
      'slide-in': 'slideIn 0.5s forwards',
      'slide-out': 'slideOut 0.3s forwards',
    },
  },
};

export const safelist = [
  { pattern: /bg-(success|information|error)-light/ },
  { pattern: /text-(success|information|error)-dark/ },
];

export const plugins = [
  function({ addBase }) {
    addBase({
      '@font-face': {
        fontFamily: 'Roboto',
        fontStyle: 'normal',
        fontWeight: 'normal',
        src: "url('../fonts/Roboto-Regular.woff') format('woff')",
      },
    });
  },
  function({ addComponents }) {
    addComponents({
      '.fcp-card': {
        '@apply flex flex-col gap-5 p-8 rounded-md shadow-lg bg-fcp-blue-dark': {},
      },
      '.fcp-label': {
        '@apply mb-1 block text-sm text-fcp-white font-medium': {},
      },
      '.fcp-error': {
        '@apply mt-2 text-sm text-error-dark': {},
      },
      '.fcp-input': {
        '@apply w-full p-2 border border-gray-700 rounded-md bg-fcp-blue-dark text-fcp-white focus:outline-none focus:border-cyan-600 focus:ring-2 transition duration-200': {},
      },
      '.fcp-button': {
        '@apply w-full inline-block py-2 bg-cyan-600 rounded-md text-center text-fcp-white font-semibold hover:bg-cyan-700 cursor-pointer transition duration-300': {}
      },
    });
  },
];
