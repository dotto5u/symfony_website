/** @type { import('tailwindcss').Config } */
export const content = [
  "./assets/**/*.js",
  "./templates/**/*.html.twig",
];
export const theme = {
  extend: {
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
      'fcp-dark-blue': "#0D1B2A",
      'fcp-blue': '#1E2A47',
      'fcp-cyan': '#0189A4',
      'fcp-orange': '#E67E22',
      'fcp-white': '#EAEAEA',
    },
    animation: {
      'slide-in': 'slideIn 0.5s forwards',
      'slide-out': 'slideOut 0.3s forwards',
    },
    keyframes: {
      slideIn: {
        '0%': { transform: 'translateX(100%)', opacity: '0' },
        '100%': { transform: 'translateX(0)', opacity: '1' },
      },
      slideOut: {
        '0%': { transform: 'translateX(0)', opacity: '1' },
        '100%': { transform: 'translateX(100%)', opacity: '0' },
      },
    },
  },
};
export const safelist = [
  { pattern: /bg-(success|information|error)-light/ },
  { pattern: /text-(success|information|error)-dark/ },
];
export const plugins = [];
