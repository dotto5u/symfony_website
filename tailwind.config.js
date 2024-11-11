/** @type { import('tailwindcss').Config } */
export const content = [
  "./assets/**/*.js",
  "./templates/**/*.html.twig",
];
export const theme = {
  extend: {
    screens: {
      '2sm': '396px',
    },
    fontFamily: {
      'fcp': ['Roboto', 'sans']
    },
    colors: {
      'success-light': '#D1FAE5',
      'success-dark': '#065F46',
      'information-light': '#FEF3C7',
      'information-dark': '#92400E',
      'error-light': '#FEE2E2',
      'error-dark': '#B91C1C',
      'fcp-blue-light': '#1E2A47',
      'fcp-blue-dark': '#0D1B2A',
      'fcp-white': '#EAEAEA',
      'fcp-orange': '#E67E22',
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
      '.fcp-label': {
        '@apply mb-1 block text-sm text-fcp-white font-medium': {},
      },
      '.fcp-input': {
        '@apply w-full p-2 border border-gray-700 rounded-md bg-fcp-blue-dark text-fcp-white focus:outline-none focus:border-cyan-600 focus:ring-2 transition duration-200': {},
      },
      '.fcp-button': {
        '@apply w-full inline-block py-2 bg-cyan-600 rounded-md text-center text-fcp-white font-semibold hover:bg-cyan-700 transition duration-300': {}
      },
    });
  },
];