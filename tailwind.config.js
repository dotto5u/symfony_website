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
      'fcp-dark-blue': "#0D1B2A",
      'fcp-blue': '#1E2A47',
      'fcp-gray': '#95A6A9',
      'fcp-orange': '#E67E22',
    },
  },
};
export const plugins = [];
