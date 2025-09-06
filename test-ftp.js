#!/usr/bin/env node

/**
 * FTP Connection Test Script
 * 
 * This script helps you test your FTP connection before setting up GitHub Actions.
 * 
 * Usage:
 * 1. Install dependencies: npm install ftp
 * 2. Update the connection details below
 * 3. Run: node test-ftp.js
 */

const ftp = require('ftp');

// 🔧 UPDATE THESE VALUES WITH YOUR FTP DETAILS
const ftpConfig = {
  host: 'your-ftp-server.com',        // e.g., 'ftp.yourdomain.com'
  user: 'your-username',              // Your FTP username
  password: 'your-password',          // Your FTP password
  port: 21,                           // Usually 21 for FTP, 22 for SFTP
  secure: false,                      // true for FTPS, false for FTP
  secureOptions: null,
  debug: console.log
};

console.log('🚀 Testing FTP Connection...\n');

const client = new ftp();

client.on('ready', () => {
  console.log('✅ FTP Connection Successful!');
  console.log(`📁 Connected to: ${ftpConfig.host}:${ftpConfig.port}`);
  
  // List current directory
  client.list((err, list) => {
    if (err) {
      console.log('❌ Error listing directory:', err.message);
    } else {
      console.log('📂 Directory contents:');
      list.forEach(file => {
        console.log(`  - ${file.name} (${file.type === 'd' ? 'directory' : 'file'})`);
      });
    }
    
    client.end();
  });
});

client.on('error', (err) => {
  console.log('❌ FTP Connection Failed!');
  console.log('Error:', err.message);
  
  // Provide helpful suggestions
  console.log('\n🔧 Troubleshooting suggestions:');
  console.log('1. Check if server address is correct (no http:// or ftp:// prefix)');
  console.log('2. Verify username and password');
  console.log('3. Check if port is correct (21 for FTP, 22 for SFTP)');
  console.log('4. Try with secure: true for FTPS');
  console.log('5. Check if your hosting provider supports FTP');
  console.log('6. Try SFTP instead of FTP');
  
  process.exit(1);
});

// Connect to FTP server
client.connect(ftpConfig);

// Timeout after 30 seconds
setTimeout(() => {
  console.log('⏰ Connection timeout after 30 seconds');
  client.end();
  process.exit(1);
}, 30000);
