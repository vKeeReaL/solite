import { defineConfig } from 'astro/config';
import react from '@astrojs/react';
import tailwind from '@astrojs/tailwind';
import sitemap from '@astrojs/sitemap';
import robotsTxt from 'astro-robots-txt';

// https://astro.build/config
export default defineConfig({
  // base: '.', // Set a path prefix.
  site: 'https://solite.au/', // Use to generate your sitemap and canonical URLs in your final build.
  trailingSlash: 'never', // Use to always append '/' at end of url
  markdown: {
    shikiConfig: {
      // Choose from Shiki's built-in themes (or add your own)
      // https://github.com/shikijs/shiki/blob/main/docs/themes.md
      theme: 'monokai',
    },
  },
  build: {
    format: 'file',
  },
  output: 'static',
  integrations: [react(), sitemap(), robotsTxt(), tailwind()],
  server: {
    port: 5173,
    host: true // optional: enables network access (e.g., mobile devices, Docker)
  }
});
