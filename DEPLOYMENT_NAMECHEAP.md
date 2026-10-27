# Quick Deployment Guide - Namecheap Shared Hosting

**For**: Namecheap cPanel Shared Hosting  
**Time Required**: 30-45 minutes  
**Prerequisites**: Namecheap hosting account active, domain connected

---

## 🚀 Quick Steps Overview

1. ✅ Export local database
2. ✅ Prepare deployment package (exclude .env, .git, test files)
3. ✅ Upload files via cPanel File Manager
4. ✅ Create MySQL database & user
5. ✅ Import database
6. ✅ Create `.env` file with production credentials
7. ✅ Set file permissions (`.env` = 600)
8. ✅ Install SSL certificate (free via cPanel)
9. ✅ Force HTTPS redirect
10. ✅ Change admin password
11. ✅ Test everything

---

## Step 1: Access Namecheap cPanel

1. Log into your **Namecheap account**
2. Go to **Dashboard** → **Hosting List**
3. Click **Manage** next to your hosting package
4. Click **Go to cPanel** (or click the cPanel login button)

**Direct cPanel URL**: Usually `https://server123.web-hosting.com:2083`  
(Replace `server123` with your actual server name)

---

## Step 2: Create MySQL Database

1. In cPanel, scroll to **Databases** section
2. Click **MySQL Databases**

### Create Database:
- **Database Name**: `youruser_lawfirm` (Namecheap adds prefix automatically)
- Click **Create Database**
- **Note the full database name** (e.g., `youruser_lawfirm`)

### Create User:
- **Username**: `youruser_admin` (will be prefixed)
- **Password**: Click **Generate Password** → Copy and save it!
- Click **Create User**
- **Note the full username** (e.g., `youruser_admin`)

### Add User to Database:
- Scroll to **Add User To Database**
- Select your user and database
- Click **Add**
- On privileges screen, check **ALL PRIVILEGES**
- Click **Make Changes**

**💡 Save These Credentials**: You'll need them for the `.env` file!

---

## Step 3: Import Database

1. In cPanel, go to **Databases** → **phpMyAdmin**
2. Click your database name (`youruser_lawfirm`) in the left sidebar
3. Click **Import** tab at the top
4. Click **Choose File** → Select `database.sql` from your computer
5. Scroll down and click **Go**
6. Wait for "Import has been successfully finished" message

**✅ Verify**: Click **Browse** tab, you should see tables like `appointments`, `attorneys`, `posts`, etc.

---

## Step 4: Upload Files

1. In cPanel, click **File Manager**
2. Navigate to `public_html` folder (double-click to open)
3. **If this is your primary domain**: Upload files directly here
4. **If using a subdomain/addon domain**: Navigate to that folder first

### Upload Process:
1. Click **Upload** button at the top
2. Click **Select File** and choose your `lawyerson_v1.zip`
3. Wait for upload to complete (100%)
4. Click **Go Back to...** link
5. Find your `.zip` file, right-click → **Extract**
6. Click **Extract Files** button
7. **Delete the .zip file** after extraction

**✅ Verify**: You should see `index.php`, `assets/`, `config/`, `views/`, etc.

---

## Step 5: Create .env File (CRITICAL!)

1. In File Manager (`public_html`), click **+ File** button
2. Name it exactly: `.env` (with the dot!)
3. Click **Create New File**
4. Right-click `.env` → **Edit**
5. Paste this template and **replace with YOUR values**:

```ini
# Lawyerson Production Configuration

# Database Configuration (from Step 2)
DB_HOST=localhost
DB_USER=youruser_admin
DB_PASS=the_strong_password_you_copied
DB_NAME=youruser_lawfirm

# API Configuration
API_KEY=PASTE_64_CHARACTER_KEY_HERE

# Application Settings
APP_ENV=production
APP_DEBUG=false

# Security Settings
SESSION_LIFETIME=1800
MAX_LOGIN_ATTEMPTS=5
RATE_LIMIT_WINDOW=300
RATE_LIMIT_MAX_REQUESTS=20
```

### Generate API Key:
1. Create temporary file: `keygen.php` in `public_html`
2. Content: `<?php echo bin2hex(random_bytes(32)); ?>`
3. Visit: `https://yourdomain.com/keygen.php`
4. Copy the 64-character key
5. Paste as `API_KEY` in `.env`
6. **Delete `keygen.php` immediately!**

### Set Permissions:
1. Right-click `.env` → **Permissions**
2. Set to: **600** (Owner: Read+Write only)
3. Click **Change Permissions**

---

## Step 6: Install FREE SSL Certificate

1. In cPanel, scroll to **Security** section
2. Click **SSL/TLS Status**
3. Find your domain in the list
4. Check the box next to your domain
5. Click **Run AutoSSL**
6. Wait 1-2 minutes for installation
7. **✅ Verify**: Visit `https://yourdomain.com` (should show padlock)

### Force HTTPS Redirect:
1. In File Manager, edit `.htaccess` in `public_html`
2. Add this **at the very top** (before any other rules):

```apache
# Force HTTPS
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>
```

3. Save the file
4. Test: Visit `http://yourdomain.com` (without 's') - should redirect to HTTPS

---

## Step 7: First-Time Admin Setup

1. Visit: `https://yourdomain.com/index.php?page=admin_setup`
2. Create a **strong admin password** (16+ characters)
   - Use a password manager (LastPass, 1Password, Bitwarden)
   - Example: `Kx9$mP2nQ7@vL4wR8zB6`
3. Click **Set Password**
4. You'll be logged in automatically

**🔐 SAVE THIS PASSWORD SECURELY!**

---

## Step 8: Verify Everything Works

### Test Admin Panel:
- [ ] Login at: `https://yourdomain.com/index.php?page=admin_login`
- [ ] Dashboard loads and shows statistics
- [ ] Navigation works (Bookings, Messages, Blog, etc.)

### Test File Uploads:
- [ ] Go to Admin → Media
- [ ] Try uploading a JPG or PNG image
- [ ] Image should appear in media library
- [ ] SVG uploads should be **blocked** (security feature)

### Test Public Forms:
- [ ] Visit contact page
- [ ] Submit a test message
- [ ] Check Admin → Messages (should appear)
- [ ] Try consultation booking form
- [ ] Check Admin → Bookings (should appear)

### Test Content Management:
- [ ] Admin → Settings: Update site name
- [ ] Admin → Attorneys: Add a team member
- [ ] Admin → Blog: Create a test blog post
- [ ] Visit public site: Changes should be visible

---

## 🔒 Security Checklist

Before announcing your site is live:

- [ ] Admin password changed from default/weak password
- [ ] `.env` file permissions set to 600
- [ ] HTTPS enabled and forced
- [ ] Test security headers: https://securityheaders.com/?q=https://yourdomain.com
- [ ] Delete these files if they exist:
  - `api/generate_api_key.php`
  - `keygen.php`
  - Any `test_*.php` files
  - `database.sql` (from root directory)

---

## 🎨 Customize Your Site

Now that everything works, customize your content:

### 1. Branding (Admin → Settings):
- [ ] Upload your logo
- [ ] Upload favicon
- [ ] Update site name
- [ ] Add contact email
- [ ] Configure social media links
- [ ] Add Tawk.to chat ID (optional)

### 2. Content:
- [ ] Add your attorneys (Admin → Attorneys)
- [ ] Write about your firm (update views/public/about.php)
- [ ] Create blog posts (Admin → Blog)
- [ ] Upload banner images (Admin → Settings)

### 3. Practice Areas:
- Edit `views/public/practices.php` to match your specialties

---

## 📊 Monitoring & Maintenance

### Set Up Backups:
1. cPanel → **Backup Wizard**
2. Enable **Full Backup** (weekly)
3. Download backups monthly to your computer

### Monitor Activity:
- Check **Admin Dashboard** regularly for new:
  - Consultation bookings
  - Contact messages
  - Blog comments (if enabled)

### Keep Updated:
- Check for PHP security updates (cPanel → Select PHP Version)
- Optimize database monthly (phpMyAdmin → Operations → Optimize)

---

## 🆘 Common Namecheap-Specific Issues

### "Too Many Redirects" Error:
- Clear browser cache and cookies
- Check `.htaccess` doesn't have duplicate redirect rules
- Disable Cloudflare SSL (if using) temporarily

### "508 Resource Limit Reached":
- Namecheap shared hosting has entry process limits
- Optimize database queries
- Enable caching
- Consider upgrading hosting plan

### File Upload "Security Error":
- Verify `uploads/` directory permissions: **755**
- Check `uploads/.htaccess` is present
- Ensure only JPG, PNG, WEBP, GIF being uploaded

### Email Forms Not Working:
- Namecheap requires email sender to be from your domain
- Update contact forms to use: `noreply@yourdomain.com`
- Enable "Track Delivery" in cPanel → Email Deliverability

### Database Connection Timeout:
- Check if you're using `localhost` as DB_HOST in `.env`
- Some Namecheap servers use `127.0.0.1` instead
- Try changing DB_HOST to `127.0.0.1`

---

## 📞 Support Resources

### Namecheap Support:
- **Live Chat**: Available 24/7 from your dashboard
- **Knowledge Base**: https://www.namecheap.com/support/knowledgebase/
- **Ticket System**: Via your Namecheap account

### Lawyerson Documentation:
- **Full Deployment Guide**: `deployment_guide.md`
- **Security Guide**: `SECURITY.md`
- **README**: `README.md`

---

## ✅ Deployment Complete!

Your Lawyerson website is now live on Namecheap!

**Live URL**: `https://yourdomain.com`  
**Admin Panel**: `https://yourdomain.com/index.php?page=admin_login`

**Next Steps**:
1. ✅ Customize branding and content
2. ✅ Add your team and practice areas
3. ✅ Start blogging for SEO
4. ✅ Set up Google Analytics (optional)
5. ✅ Test on mobile devices

**🎉 Congratulations! Your law firm website is now live and secure!**

---

**Guide Version**: 1.0  
**Last Updated**: October 27, 2026  
**Compatible With**: Namecheap Shared Hosting (Stellar, Stellar Plus, Stellar Business)
