#!/usr/bin/env node

/**
 * Favicon Generator Script
 * 
 * Creates multiple favicon formats from your logo.png
 * 
 * Usage: node create-favicon.js
 */

import sharp from 'sharp';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const logoPath = './public/logo.png';
const outputDir = './public';

// Favicon sizes and formats
const faviconSizes = [
  { size: 16, name: 'favicon-16x16.png' },
  { size: 32, name: 'favicon-32x32.png' },
  { size: 48, name: 'favicon-48x48.png' },
  { size: 64, name: 'favicon-64x64.png' },
  { size: 96, name: 'favicon-96x96.png' },
  { size: 128, name: 'favicon-128x128.png' },
  { size: 180, name: 'apple-touch-icon.png' },
  { size: 192, name: 'android-chrome-192x192.png' },
  { size: 512, name: 'android-chrome-512x512.png' }
];

async function createFavicons() {
  try {
    console.log('🎨 Creating favicons from logo.png...\n');

    // Check if logo exists
    if (!fs.existsSync(logoPath)) {
      console.error('❌ logo.png not found in public folder');
      process.exit(1);
    }

    // Create ICO file (16x16, 32x32, 48x48)
    console.log('📱 Creating favicon.ico...');
    await sharp(logoPath)
      .resize(32, 32, { fit: 'contain', background: { r: 0, g: 0, b: 0, alpha: 0 } })
      .png()
      .toFile(path.join(outputDir, 'favicon.ico'));

    // Create various PNG sizes
    for (const favicon of faviconSizes) {
      console.log(`📱 Creating ${favicon.name} (${favicon.size}x${favicon.size})...`);
      
      await sharp(logoPath)
        .resize(favicon.size, favicon.size, { 
          fit: 'contain', 
          background: { r: 0, g: 0, b: 0, alpha: 0 } 
        })
        .png()
        .toFile(path.join(outputDir, favicon.name));
    }

    // Create SVG favicon (scalable) - copy existing favicon.svg or create a simple one
    console.log('📱 Creating favicon.svg...');
    try {
      // Try to copy existing favicon.svg if it exists
      if (fs.existsSync(path.join(outputDir, 'favicon.svg'))) {
        console.log('  ✓ favicon.svg already exists');
      } else {
        // Create a simple SVG favicon
        const svgContent = `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
          <rect width="32" height="32" fill="#000000"/>
          <text x="16" y="20" font-family="Arial, sans-serif" font-size="16" fill="#C0C0C0" text-anchor="middle">S</text>
        </svg>`;
        fs.writeFileSync(path.join(outputDir, 'favicon.svg'), svgContent);
      }
    } catch (error) {
      console.log('  ⚠️ Could not create SVG favicon, using existing one');
    }

    // Create web app manifest
    console.log('📱 Creating site.webmanifest...');
    const manifest = {
      "name": "Solite Candles",
      "short_name": "Solite",
      "description": "Handcrafted luxury candles for every occasion",
      "icons": [
        {
          "src": "/android-chrome-192x192.png",
          "sizes": "192x192",
          "type": "image/png"
        },
        {
          "src": "/android-chrome-512x512.png",
          "sizes": "512x512",
          "type": "image/png"
        }
      ],
      "theme_color": "#C0C0C0",
      "background_color": "#000000",
      "display": "standalone",
      "start_url": "/"
    };

    fs.writeFileSync(
      path.join(outputDir, 'site.webmanifest'), 
      JSON.stringify(manifest, null, 2)
    );

    console.log('\n✅ Favicon generation complete!');
    console.log('\n📁 Generated files:');
    console.log('  - favicon.ico (32x32)');
    console.log('  - favicon.svg (scalable)');
    console.log('  - favicon-16x16.png');
    console.log('  - favicon-32x32.png');
    console.log('  - favicon-48x48.png');
    console.log('  - favicon-64x64.png');
    console.log('  - favicon-96x96.png');
    console.log('  - favicon-128x128.png');
    console.log('  - apple-touch-icon.png (180x180)');
    console.log('  - android-chrome-192x192.png');
    console.log('  - android-chrome-512x512.png');
    console.log('  - site.webmanifest');
    
    console.log('\n🎯 Next steps:');
    console.log('1. Update your Layout.astro to include all favicon links');
    console.log('2. Test the favicons in different browsers');
    console.log('3. Verify the favicon appears in browser tabs');

  } catch (error) {
    console.error('❌ Error creating favicons:', error.message);
    process.exit(1);
  }
}

createFavicons();
