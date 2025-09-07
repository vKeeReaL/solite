# 🚀 GitHub Actions FTP Deployment Setup

This guide will help you set up automatic deployment of your Solite Candles website to your FTP hosting using GitHub Actions.

## 📁 Deployment Structure

The deployment will create the following structure on your server:
- **`www/`** - Website files (HTML, CSS, JS, images, etc.)
- **`api/`** - PHP API files (contact form, webhooks, etc.)

## 📋 Prerequisites

- GitHub repository with your website code
- FTP hosting account with credentials
- Access to GitHub repository settings

## 🔧 Step 1: Configure GitHub Secrets

### 1.1 Access Repository Settings
1. Go to your GitHub repository
2. Click **Settings** tab
3. Click **Secrets and variables** → **Actions** in the left sidebar

### 1.2 Add FTP Credentials
Add these three secrets:

| Secret Name | Description | Example Value |
|-------------|-------------|---------------|
| `FTP_SERVER` | Your FTP server address | `ftp.yourdomain.com` |
| `FTP_USERNAME` | Your FTP username | `yourusername` |
| `FTP_PASSWORD` | Your FTP password | `yourpassword` |

### 1.3 How to Add Secrets
1. Click **New repository secret**
2. Enter the secret name (e.g., `FTP_SERVER`)
3. Enter the secret value (e.g., `ftp.yourdomain.com`)
4. Click **Add secret**
5. Repeat for all three secrets

## 🌐 Step 2: Understanding the Deployment

### 2.1 What Gets Deployed
- **Built website files** from `dist/` folder → **Root directory** of your hosting
- **API folder** with PHP files → **Root directory** of your hosting
- **Excludes** development files (`.git`, `node_modules`, etc.)

### 2.2 Deployment Structure
```
Your Hosting Root/
├── index.html (built website)
├── about.html
├── products.html
├── _astro/
├── api/
│   └── contact.php
└── other built files...
```

### 2.3 When Deployment Triggers
- **Automatic**: Every push to `main` or `master` branch
- **Manual**: Via GitHub Actions tab → **Run workflow**

## 📁 Step 3: File Structure Verification

### 3.1 Ensure API Folder Exists
Make sure your repository has this structure:
```
your-repo/
├── src/
├── api/
│   └── contact.php
├── .github/
│   └── workflows/
│       └── deploy-ftp.yml
└── package.json
```

### 3.2 API Files to Include
- `api/contact.php` - Contact form handler
- Any other PHP files you need
- Configuration files (if any)

## 🚀 Step 4: Test Deployment

### 4.1 Trigger Deployment
1. **Push changes** to main branch, OR
2. **Manual trigger**:
   - Go to **Actions** tab
   - Click **Deploy Website and API to FTP**
   - Click **Run workflow**

### 4.2 Monitor Deployment
1. Click on the running workflow
2. Watch the steps execute:
   - ✅ Checkout code
   - ✅ Setup Node.js
   - ✅ Install dependencies
   - ✅ Build website
   - ✅ Deploy Website to www folder
   - ✅ Deploy API files to api folder

### 4.3 Check for Success
- All steps should show green checkmarks
- No red X marks indicating failures
- Check your FTP hosting for uploaded files

### 4.4 Verify Folder Structure
After successful deployment, your server should have:
```
solite.au/
├── www/                    # Website files
│   ├── index.html
│   ├── products.html
│   ├── about.html
│   ├── favicon.ico
│   ├── logo.png
│   ├── products/
│   └── _astro/
└── api/                    # API files
    ├── contact.php
    ├── config.php
    ├── webhook.php
    ├── test.php
    └── README.md
```

## 🔒 Step 5: Security Considerations

### 5.1 FTP Credentials
- **Never commit** FTP credentials to your repository
- Use GitHub Secrets for all sensitive information
- Consider using SFTP if your hosting supports it

### 5.2 File Permissions
- PHP files should have permissions `644` or `640`
- Directories should have permissions `755`
- Ensure `api/` folder is accessible via web

### 5.3 Environment Variables
- Keep your Telegram bot credentials secure
- Use hosting environment variables when possible
- Don't expose sensitive data in built files

## 🐛 Troubleshooting

### 5.1 Connection Errors (ENOTFOUND, Connection Failed)

**Error: `ENOTFOUND` or "server doesn't seem to exist"**
- ✅ **Check server address format**:
  - Use: `ftp.yourdomain.com` or `yourdomain.com`
  - Avoid: `http://ftp.yourdomain.com` or `ftp://ftp.yourdomain.com`
- ✅ **Try different protocols**:
  - If FTP fails, try SFTP (use `deploy-sftp.yml` workflow)
  - Some hosts only support SFTP, not FTP
- ✅ **Check if port is needed**:
  - FTP: port 21 (default)
  - SFTP: port 22 (default)
  - Some hosts use custom ports like 21, 990, 2121

**Error: "Failed to connect, server only supports SFTP"**
- ✅ **Switch to SFTP workflow**:
  1. Rename `deploy-ftp.yml` to `deploy-ftp-backup.yml`
  2. Rename `deploy-sftp.yml` to `deploy-ftp.yml`
  3. Update your secrets if needed

### 5.2 Authentication Errors

**Error: "Login failed" or "Authentication failed"**
- ✅ **Verify credentials**:
  - Username: Usually your hosting account username
  - Password: Your hosting account password
  - Server: Your domain or hosting provider's FTP server
- ✅ **Check special characters**:
  - Escape special characters in passwords
  - Use URL encoding if needed

### 5.3 Server Configuration Issues

**Error: "Permission denied" or "Directory not found"**
- ✅ **Check server directory**:
  - Use `./` for root directory
  - Use `/public_html/` for cPanel hosting
  - Use `/www/` for some hosting providers
- ✅ **Verify file permissions**:
  - Ensure directory is writable
  - Check if hosting allows file uploads

### 5.4 Common Hosting Provider Settings

| Provider | Server Format | Directory | Port | Protocol |
|----------|---------------|-----------|------|----------|
| **cPanel** | `ftp.yourdomain.com` | `./public_html/` | 21 | FTP |
| **GoDaddy** | `ftp.yourdomain.com` | `./` | 21 | FTP |
| **Bluehost** | `ftp.yourdomain.com` | `./public_html/` | 21 | FTP |
| **Hostinger** | `ftp.yourdomain.com` | `./public_html/` | 21 | FTP |
| **SiteGround** | `ftp.yourdomain.com` | `./public_html/` | 21 | FTP |
| **DigitalOcean** | `your-ip-address` | `./` | 22 | SFTP |

### 5.5 Testing Your FTP Connection

**Before setting up GitHub Actions, test manually:**

1. **Using FileZilla (Free FTP Client)**:
   - Download FileZilla
   - Enter your server, username, password
   - Test connection
   - Note the exact server address and directory

2. **Using Command Line**:
   ```bash
   # Test FTP connection
   ftp your-server.com
   
   # Test SFTP connection
   sftp username@your-server.com
   ```

3. **Check with your hosting provider**:
   - Look for FTP settings in your hosting control panel
   - Check if FTP is enabled
   - Verify the correct server address and port

### 5.6 Alternative Deployment Methods

If FTP/SFTP continues to fail, consider:

1. **Git-based deployment** (if your host supports it)
2. **Webhook deployment** (if available)
3. **Manual deployment** using hosting control panel
4. **Different hosting provider** with better FTP support

## 📱 Advanced Configuration

### 5.1 Custom Branches
To deploy from different branches:
```yaml
on:
  push:
    branches: [ main, develop, staging ]
```

### 5.2 Environment-Specific Deployments
For different hosting environments:
```yaml
- name: Deploy to Production
  if: github.ref == 'refs/heads/main'
  # ... deployment steps

- name: Deploy to Staging
  if: github.ref == 'refs/heads/develop'
  # ... staging deployment
```

### 5.3 Conditional Deployments
Only deploy when specific files change:
```yaml
on:
  push:
    paths:
      - 'src/**'
      - 'api/**'
      - 'package.json'
```

## 🎯 Next Steps

Once deployment is working:
1. **Test the contact form** on your live site
2. **Verify Telegram notifications** are working
3. **Monitor deployment logs** for any issues
4. **Set up staging environment** if needed
5. **Configure custom domain** and SSL

## 📞 Support

If you encounter issues:
1. Check GitHub Actions logs for error details
2. Verify FTP credentials and server access
3. Test FTP connection manually
4. Check hosting provider's FTP documentation

---

**Note:** Keep your FTP credentials secure and never share them publicly. Consider using SFTP for enhanced security if your hosting supports it.
