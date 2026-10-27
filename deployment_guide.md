# Lawyerson - Production Deployment Guide

This comprehensive guide covers deploying your PHP/MySQL Law Firm website to a live production environment. Updated for the latest security enhancements including .env configuration, enhanced file upload security, and tightened CSP headers.

**Compatible with**: cPanel hosting (HostGator, Bluehost, Namecheap), Linux VPS (DigitalOcean, Linode), and any LAMP/LEMP stack.

**Last Updated**: October 27, 2026 - Includes new .env configuration system

---

## Step 1: Exporting the Local Database

Before you upload anything, you must package your database structure and current data.

1. Open your local database tool (e.g., **phpMyAdmin** via XAMPP/WAMP).
2. Select your `law_firm_db` database.
3. Click on the **Export** tab.
4. Ensure the format is set to **SQL** and the export method is **Quick**.
5. Click **Go** to download the `law_firm_db.sql` file to your computer. *(Alternatively, use the `database.sql` file we've been working with if it is completely up-to-date with your settings and test data).* 

---

## Step 2: Preparing the Files

### ⚠️ IMPORTANT: Security Checklist Before Upload

Before creating your deployment package, complete these security steps:

1. **Verify .gitignore is working** (if using Git):
   - The `.env` file should NOT be in your upload
   - `config/api_key.php` should NOT be in your upload
   - Uploaded files in `uploads/*` should NOT be in your upload (except `.htaccess`)

2. **Delete test/development files**:
   - Delete `api/generate_api_key.php` (if it exists)
   - Delete any `test_*.php` files
   - Delete any `.sql` backup files in the root directory

3. **Prepare production .env file**:
   - You'll create a NEW `.env` on the server (don't upload your local one)

### Creating the Deployment Package

1. Navigate to your project folder (`c:\laragon\www\lawyerson`).
2. Select **all files and folders** inside this directory.
3. **EXCLUDE these files/folders** (if present):
   - `.env` (you'll create this on the server)
   - `.git/` folder
   - `.idea/` or `.vscode/` folders
   - `node_modules/` folder
   - `test_*.php` files
   - `api/generate_api_key.php`
   - Any local database backups

4. Right-click and choose **Compress to ZIP file** (name it `lawyerson_v1.zip`).

**Recommended**: Create a clean deployment package with only production files.

---

## Step 3: Domain Connection & SSL Setup

If you haven't already, ensure your domain points to your hosting provider.

### Pointing the Domain (DNS)
1. Log into the registrar where you bought your domain (e.g., GoDaddy, Namecheap).
2. Find the **DNS Management** or **Nameservers** section for your domain.
3. Replace the default nameservers with the ones provided by your web host (e.g., `ns1.yourhost.com`, `ns2.yourhost.com`).
4. *Note: DNS propagation can take anywhere from a few minutes to 24 hours.*

### Activating SSL (HTTPS)
*Modern browsers will flag a site without SSL as "Not Secure".*
1. Log into your hosting **cPanel**.
2. Navigate to the **Security** section and click on **Let's Encrypt SSL** or **SSL/TLS Status**.
3. Select your domain and click **Issue** or **Run AutoSSL**.
4. Once installed, your site will securely load over `https://`.

---

## Step 4: Database Setup on the Live Server

1. In cPanel, go to the **Databases** section and click **MySQL Databases**.
2. **Create New Database:** Name it something unique (e.g., `yourusername_lawfirm`).
3. **Create New User:** Scroll down to "MySQL Users", create a user (e.g., `yourusername_admin`), and generate a very strong password. **Save this password!**
4. **Add User to Database:** Scroll down further, select the user and the database you just created, and click **Add**. Grant **All Privileges**.

### Importing Data
1. Go back to the main cPanel dashboard and open **phpMyAdmin**.
2. Select your newly created database (`yourusername_lawfirm`) on the left.
3. Click the **Import** tab at the top.
4. Click **Choose File** and select your `law_firm_db.sql` file from Step 1.
5. Click **Go** at the bottom to import all tables, settings, and blog posts.

---

## Step 5: Uploading Files

1. In cPanel, go to the **Files** section and click **File Manager**.
2. Navigate into the `public_html` directory (this is the root directory for your primary domain).
3. Click the **Upload** button at the top and select your `website_files.zip`.
4. Once uploaded (100%), return to the File Manager, right-click the `.zip` file, and click **Extract**.
5. Verify that `index.php` and your folders (`assets`, `views`, `models`, etc.) are now sitting directly inside `public_html`.
6. Delete the `website_files.zip` file to save space and for security.

---

## Step 6: Environment Configuration (.env Setup)

**NEW**: Your website now uses a `.env` file for secure configuration management. You need to create this file on your live server.

### Creating the .env File on Server

1. In **File Manager** (cPanel), navigate to your `public_html` directory.
2. Click **+ File** button at the top to create a new file.
3. Name it exactly: `.env` (with the dot at the beginning).
4. Right-click the new `.env` file and select **Edit**.
5. Copy and paste this template, replacing values with your actual credentials:

```ini
# Lawyerson Production Configuration
# IMPORTANT: Never share this file publicly!

# Database Configuration
DB_HOST=localhost
DB_USER=yourusername_admin
DB_PASS=YOUR_STRONG_DATABASE_PASSWORD
DB_NAME=yourusername_lawfirm

# API Configuration (generate a new key below)
API_KEY=GENERATE_A_64_CHARACTER_KEY_HERE

# Application Settings
APP_ENV=production
APP_DEBUG=false

# Security Settings
SESSION_LIFETIME=1800
MAX_LOGIN_ATTEMPTS=5
RATE_LIMIT_WINDOW=300
RATE_LIMIT_MAX_REQUESTS=20
```

### Generating a Production API Key

**Option 1: Generate via PHP (Recommended)**
1. Create a temporary file `generate_key.php` in `public_html`:
```php
<?php
echo bin2hex(random_bytes(32));
?>
```
2. Visit `https://yourdomain.com/generate_key.php` in your browser
3. Copy the generated 64-character key
4. Paste it as the `API_KEY` value in your `.env` file
5. **DELETE `generate_key.php` immediately after!**

**Option 2: Generate Online**
1. Visit: https://www.random.org/strings/ or https://passwordsgenerator.net/
2. Generate a 64-character alphanumeric string
3. Use this as your `API_KEY` in `.env`

### Setting Secure File Permissions

**CRITICAL**: Protect your `.env` file from unauthorized access.

In cPanel File Manager:
1. Right-click `.env` file
2. Select **Change Permissions**
3. Set permissions to `600` (Owner: Read+Write, Group: None, World: None)
4. Click **Change Permissions**

Or via SSH:
```bash
chmod 600 .env
chmod 644 config/*.php
```

### Verify Configuration is Working

1. Visit your website: `https://yourdomain.com`
2. If you see a database connection error, double-check your `.env` credentials
3. Try logging into admin: `https://yourdomain.com/index.php?page=admin_login`

**Troubleshooting**:
- If database connection fails, verify DB credentials in `.env` match Step 4
- If you see "Call to undefined function Env::get", ensure `config/env.php` was uploaded
- Check file permissions: `.env` should be `600`, `config/*.php` should be `644`

---

## Step 7: Security Hardening & Configuration

### 🔒 Mandatory Security Steps

#### 1. Change Admin Password Immediately
1. Login at: `https://yourdomain.com/index.php?page=admin_setup`
2. If first-time setup: Create a strong password (16+ characters)
3. If already set up: Go to Dashboard → Click "Change Password"
4. Use a password manager to generate and store strong passwords

#### 2. Verify File Permissions
Ensure these permissions are set correctly:

```bash
# Via SSH
chmod 600 .env                    # Environment config (critical!)
chmod 644 config/*.php            # Config files
chmod 644 index.php               # Entry point
chmod 755 uploads/                # Upload directory
chmod 644 uploads/.htaccess       # Upload security (critical!)
chmod 644 uploads/*               # Uploaded files
```

Or use cPanel File Manager → Right-click → Change Permissions

#### 3. Verify .htaccess Files Are Present

Check these files exist and are not corrupted:

**Root `.htaccess`** (`public_html/.htaccess`):
- Blocks direct access to config, models, includes, migrations folders
- Denies access to .sql, .md, .env, .log files
- Blocks api_key.php access

**Uploads `.htaccess`** (`public_html/uploads/.htaccess`):
- Disables PHP execution in uploads directory
- Blocks script file execution
- **CRITICAL** for security - verify this file exists!

#### 4. Force HTTPS (Mandatory for Production)

Edit or create `.htaccess` in `public_html` root. Add this **at the top**:

```apache
# Force HTTPS
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>

# Existing rules below...
```

#### 5. Test Security Headers

Visit: https://securityheaders.com/?q=https://yourdomain.com

**Expected Results**:
- ✅ X-Content-Type-Options: nosniff
- ✅ X-Frame-Options: SAMEORIGIN
- ✅ Referrer-Policy
- ✅ Strict-Transport-Security (HSTS)
- ✅ Content-Security-Policy

#### 6. Remove Development Files

Verify these files are NOT on your production server:
- ❌ `api/generate_api_key.php`
- ❌ `test_*.php` files
- ❌ `.env.example` (optional to keep for reference)
- ❌ `SECURITY.md` (optional to keep for reference)
- ❌ `README.md` (optional to keep for reference)

---

## Step 8: Functionality Testing

### Test Core Features:

#### 1. Admin Dashboard
- [ ] Login works at `/index.php?page=admin_login`
- [ ] Dashboard displays statistics
- [ ] "Remember me" option works (optional)

#### 2. Content Management
- [ ] Blog: Create, edit, publish posts
- [ ] Attorneys: Add, edit, delete team members
- [ ] Media: Upload images (JPG, PNG, WEBP, GIF only)
- [ ] Settings: Update site name, logo, social links

#### 3. Public Forms
- [ ] Contact form submits successfully
- [ ] Consultation booking form works
- [ ] Submissions appear in admin dashboard

#### 4. File Uploads
- [ ] Media manager accepts valid images
- [ ] SVG uploads are blocked (security feature)
- [ ] Uploaded files appear in media library
- [ ] Images display correctly on blog posts

#### 5. API Endpoints (if using mobile app)
Test these endpoints with your API key:
- `GET /api/posts.php` - List blog posts
- `GET /api/attorneys.php` - List attorneys
- `POST /api/consultation.php` - Submit booking
- `POST /api/contact.php` - Submit message

---

## Step 9: Performance & Optimization

### Recommended Optimizations:

1. **Enable PHP OPcache** (via cPanel or php.ini):
```ini
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
```

2. **Enable Gzip Compression** (add to .htaccess):
```apache
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript application/javascript
</IfModule>
```

3. **Browser Caching** (add to .htaccess):
```apache
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/jpg "access plus 1 year"
  ExpiresByType image/jpeg "access plus 1 year"
  ExpiresByType image/png "access plus 1 year"
  ExpiresByType image/webp "access plus 1 year"
  ExpiresByType text/css "access plus 1 month"
  ExpiresByType application/javascript "access plus 1 month"
</IfModule>
```

4. **Database Optimization**:
- In phpMyAdmin, select your database
- Go to "Operations" → "Optimize table"
- Run monthly for best performance

---

## Step 10: Backup Strategy

### Set Up Automated Backups:

#### cPanel Backups:
1. Go to cPanel → **Backup Wizard**
2. Enable **Full Backup** (weekly recommended)
3. Enable **Database Backup** (daily recommended)
4. Download backups to local storage monthly

#### Manual Backup Checklist:
- [ ] Database: Export SQL via phpMyAdmin
- [ ] Files: Download `uploads/` directory
- [ ] Configuration: Securely backup `.env` file (encrypted)
- [ ] Store backups off-site (Google Drive, Dropbox)

---

## 🚨 Security Incident Response

If you suspect a security breach:

1. **Immediate Actions**:
   - Change admin password
   - Regenerate API key (Admin → Settings)
   - Check `uploads/` for suspicious files
   - Review recent admin activity

2. **Investigation**:
   - Check cPanel → Raw Access Logs
   - Review `error_log` files
   - Look for failed login attempts in database

3. **Recovery**:
   - Restore from clean backup if compromised
   - Update all credentials
   - Contact your hosting provider if needed

---

## 📋 Post-Deployment Checklist

Use this checklist to verify everything is properly configured:

### Configuration:
- [ ] `.env` file created with production credentials
- [ ] `.env` file permissions set to 600
- [ ] Database connection works
- [ ] API key generated and saved in `.env`
- [ ] `APP_ENV=production` and `APP_DEBUG=false` set

### Security:
- [ ] Admin password changed from default
- [ ] HTTPS enabled and forced
- [ ] SSL certificate installed and valid
- [ ] `.htaccess` files present in root and uploads
- [ ] Security headers verified at securityheaders.com
- [ ] Development files removed (test files, generators)

### Functionality:
- [ ] Admin login works
- [ ] Public pages load correctly
- [ ] Contact form submits successfully
- [ ] Booking form works
- [ ] Media uploads function properly
- [ ] Blog posts display correctly

### Performance:
- [ ] Gzip compression enabled
- [ ] Browser caching configured
- [ ] Images optimized for web
- [ ] Page load time under 3 seconds

### Monitoring:
- [ ] Error logging configured
- [ ] Backup system set up
- [ ] Analytics/tracking installed (Google Analytics, etc.)
- [ ] Uptime monitoring configured (optional)

---

## 🎓 Additional Resources

- **Security Guide**: See `SECURITY.md` for detailed security documentation
- **PHP Requirements**: PHP 7.4+ (PHP 8.0+ recommended)
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Web Server**: Apache 2.4+ with mod_rewrite enabled

---

## 🆘 Troubleshooting Common Issues

### "Database connection failed"
- Verify `.env` file exists and has correct credentials
- Check DB_HOST is `localhost` (may vary on some hosts)
- Ensure database user has all privileges on the database

### "Call to undefined function Env::get"
- Ensure `config/env.php` file was uploaded
- Check file permissions on config directory (755)

### "Uploaded file security error"
- Verify `uploads/` directory has 755 permissions
- Check `uploads/.htaccess` file is present
- Ensure only JPG, PNG, WEBP, GIF files are being uploaded

### Forms not submitting
- Check CSRF token is being generated (clear browser cache)
- Verify form action points to correct URL
- Check browser console for JavaScript errors

### Admin login fails repeatedly
- Check if account is locked (wait 15 minutes)
- Clear browser cookies
- Verify admin password in database matches what you're typing

### API returns 401 Unauthorized
- Verify API_KEY in `.env` matches the one in your API requests
- Check X-API-Key header is being sent correctly
- Ensure API endpoint URLs are correct

---

## ✅ Deployment Complete!

Congratulations! Your Lawyerson law firm website is now securely deployed and ready for production use.

**Your website is now live at**: `https://yourdomain.com`  
**Admin dashboard**: `https://yourdomain.com/index.php?page=admin_login`

**Next Steps**:
1. Customize your branding (Admin → Settings)
2. Add your team members (Admin → Attorneys)
3. Create blog posts (Admin → Blog)
4. Monitor your analytics and visitor engagement

**Security Reminder**: Review `SECURITY.md` and keep all credentials secure!

---

**Deployment Guide Version**: 2.0 (Updated October 27, 2026)  
**Compatible With**: Lawyerson v2.0+ (with .env configuration)  
**Last Updated**: October 27, 2026
