# Uploads Directory

This directory contains user-uploaded media files (images) from the admin media manager.

## Security Features

- ✅ Script execution disabled via `.htaccess`
- ✅ Directory listing disabled
- ✅ PHP handlers removed
- ✅ Only image MIME types allowed
- ✅ Strict file validation in Media.php model

## Allowed File Types

- JPG/JPEG
- PNG
- WEBP
- GIF

**SVG uploads are DISABLED by default** due to XSS security risks.

## File Naming

All uploaded files are renamed with:
- Cryptographically secure random prefix (32 characters)
- Original extension preserved
- Example: `5a8f3b9c4d2e1f6a7b8c9d0e1f2a3b4c.jpg`

## Backup Recommendations

Regular backups of this directory are recommended as it contains all media assets for the website.

## .htaccess Protection

Do NOT delete the `.htaccess` file in this directory - it provides critical security protections.
