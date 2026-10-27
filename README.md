# Lawyerson & Partners - Law Firm Website with Admin Dashboard

Lawyerson & Partners is a professional PHP and MySQL law firm website built for legal practices, attorneys, consultants, and agencies that need a polished public website with a practical admin dashboard. It includes public-facing service pages, consultation booking, contact messaging, blog/case publishing, attorney management, media management, site settings, and secure admin password control.

This guide explains the full website feature set, admin features, installation steps, and buyer-facing highlights you can adapt for a CodeCanyon product listing.

## Key Highlights

- Modern law firm website with a premium navy and gold visual style
- Responsive public website and admin dashboard
- Consultation booking system with admin status management
- Contact message inbox with search, filters, reply history, and delete actions
- Blog/case management with draft and published statuses
- Attorney/team management
- Media upload manager for JPG, PNG, WEBP, GIF, and SVG files
- Website settings panel for branding, logo, favicon, banners, social links, and live chat ID
- Dashboard analytics for bookings, messages, posts, attorneys, and activity
- Admin password change feature with secure password hashing
- CSRF protection on form submissions
- Clean PHP MVC-style structure using reusable models and views

## Technology Stack

- PHP
- MySQL / MariaDB
- PDO database connection
- Bootstrap 5
- Font Awesome icons
- Chart.js dashboard charts
- HTML5, CSS3, JavaScript

## Public Website Features

### Home Page

The homepage presents the law firm with a professional landing experience, call-to-action sections, practice highlights, attorney/business credibility sections, and consultation prompts. The page is designed to convert visitors into enquiries or consultation bookings.

### Practice Areas Page

The practice areas page lists legal service categories such as:

- Corporate Law
- Criminal Law
- Property Law
- Family Law
- Immigration Law
- Civil Litigation

Each practice area includes a short explanation, key service points, and a consultation call to action.

### Attorneys Page

The attorneys page displays the legal team from the database. Admins can add attorneys with:

- Full name
- Position or title
- Short biography
- Image URL
- Optional social links

### Blog / Case Insights

The blog system can be used for legal articles, firm news, case studies, practice insights, or SEO content. Posts support:

- Title
- Slug
- Category
- Content
- Featured image
- Draft or published status

Only published posts are displayed publicly.

### Article Details Page

Each blog post has a full article page with:

- Featured image
- Category badge
- Published date
- Full content
- Social share buttons
- Consultation call to action

### Consultation Booking

Visitors can submit consultation requests with:

- Name
- Email
- Phone
- Appointment date
- Practice area
- Case details

Requests are stored in the database and managed from the admin dashboard.

### Contact Page

Visitors can send direct messages to the firm using the contact form. Submitted messages appear in the admin message inbox with unread/read/replied status tracking.

### Dynamic Branding

The admin settings panel controls important website branding options, including:

- Website name
- Contact email
- Footer text
- Social links
- Website logo
- Favicon
- Homepage and inner-page banner images
- Tawk.to live chat property ID

## Admin Dashboard Features

The admin area is designed as a professional law firm command center with a responsive sidebar and clean card-based layouts.

### Admin Login

The admin area is protected by login authentication. Default demo credentials are:

```text
Email: admin@lawyerson.com
Password: password123
```

After installation, change the password from the admin dashboard using the **Change Password** button under the System Admin profile.

### Secure Password Management

The admin sidebar includes a professional **Change Password** feature under the System Admin profile. It supports:

- Current password verification
- New password entry
- Confirm password field
- Minimum password length validation
- Password hashing with PHP `password_hash()`
- Secure login verification with `password_verify()`

### Firm Command Center

The main dashboard gives the admin a quick overview of the website and firm activity:

- Confirmed bookings
- Pending bookings
- Total bookings
- Unread messages
- Published posts
- Attorney count
- Booking status chart
- Consultation request chart
- Next consultation panel
- Recent clients
- Upcoming appointments
- Recent activity feed
- Top practice areas
- Embedded consultation booking table

### Booking Management

Consultation requests submitted from the public website can be managed in the admin area. Admins can:

- View booking details
- Confirm pending bookings
- Mark confirmed bookings as completed
- Delete bookings
- See client name, email, phone, appointment date, practice area, and status

### Message Inbox

The message section has a modern inbox interface for contact enquiries. Admins can:

- Search messages by name, email, phone, subject, or message body
- Filter messages by all, unread, read, or replied
- View sender details
- Open a message modal
- Save dashboard replies to message history
- Mark messages as replied after sending a reply
- Delete messages
- View message status totals

### Blog / Case Management

Admins can manage legal articles or case insights from the blog admin page:

- Create new posts
- Edit existing posts
- Delete posts
- Set posts as draft or published
- Upload featured images
- Select featured images from the media library
- Paste external image URLs
- Preview selected images

### Attorney Management

Admins can add and remove attorney profiles. Each attorney supports:

- Full name
- Position or title
- Photo URL
- Short bio
- LinkedIn URL
- Twitter URL

### Media Manager

The media manager lets admins upload and delete website assets. Supported uploads include:

- JPG
- JPEG
- PNG
- WEBP
- GIF
- SVG

Uploaded media can be reused for blog featured images, admin profile image, logo, favicon, and website banners.

### Settings Panel

The settings panel controls website-wide content and branding:

- Website name
- Contact email
- Footer text
- Social media URLs
- Tawk.to live chat ID
- Admin profile image
- Website logo
- Site favicon
- Homepage hero image
- Homepage contact image
- Homepage about image
- About page banner and section images
- Attorneys page banner
- Contact page banner

### Responsive Admin Layout

The admin dashboard is responsive across desktop, tablet, and mobile devices. The sidebar adapts for smaller screens, and dashboard tables/cards are optimized for mobile viewing.

## Security Features

- CSRF token validation for form submissions
- HTTP-only session cookies
- SameSite session cookie policy
- Admin route protection
- Secure admin password hashing
- Password change validation
- PDO prepared statements for database operations
- Admin password no longer exposed as a login hint

## Database Tables

The project uses the following core tables:

- `appointments` - consultation booking requests
- `attorneys` - attorney/team profiles
- `posts` - blog posts and legal insights
- `media` - uploaded media files
- `settings` - dynamic website settings and admin password hash
- `messages` - contact form messages
- `message_replies` - saved dashboard replies to messages

## Folder Structure

```text
lawyerson/
├── assets/
│   ├── css/
│   └── images/
├── config/
│   └── database.php
├── includes/
│   └── admin_helpers.php
├── migrations/
│   └── add_message_replies.sql
├── models/
│   ├── Attorney.php
│   ├── Media.php
│   ├── Message.php
│   ├── Post.php
│   └── Setting.php
├── uploads/
├── views/
│   ├── admin/
│   ├── layouts/
│   ├── partials/
│   └── public/
├── database.sql
├── deployment_guide.md
├── index.php
└── xampp_local_guide.md
```

## Installation Guide

### Requirements

- PHP 7.4 or newer recommended
- MySQL or MariaDB
- Apache server
- XAMPP, WAMP, MAMP, Laragon, or cPanel hosting

### Local Installation with XAMPP

1. Copy the project folder to:

```text
C:\xampp\htdocs\lawyerson
```

2. Start Apache and MySQL from the XAMPP Control Panel.

3. Create a database in phpMyAdmin:

```text
lawfirm_db
```

4. Import the provided SQL file:

```text
database.sql
```

5. Confirm database details in:

```text
config/database.php
```

Default local configuration:

```php
$host = 'localhost';
$dbname = 'lawfirm_db';
$username = 'root';
$password = '';
```

6. Open the website:

```text
http://localhost/lawyerson/
```

7. Open the admin dashboard:

```text
http://localhost/lawyerson/index.php?page=admin_login
```

8. Login with the default credentials and immediately change the password:

```text
Email: admin@lawyerson.com
Password: password123
```

## Admin Usage Guide

### Change Admin Password

1. Login to the admin dashboard.
2. Look at the System Admin profile in the sidebar.
3. Click **Change Password**.
4. Enter the current password.
5. Enter and confirm the new password.
6. Click **Update Password**.

### Manage Bookings

1. Go to **Dashboard** or **Booking**.
2. Review consultation requests.
3. Use the action buttons to view, confirm, complete, or delete a booking.

### Reply to Messages

1. Go to **Messages**.
2. Search or filter messages.
3. Click **Reply** on a message.
4. Write a reply in the modal.
5. Submit the reply to save it in the message history.

### Publish a Blog Post

1. Go to **My Cases (Blog)**.
2. Create a new article.
3. Add title, category, content, and image.
4. Set status to published.
5. Save the post.

### Upload Media

1. Go to **Media**.
2. Upload supported image files.
3. Use the uploaded file path in blog posts, banners, logo, favicon, or attorney photos.

### Update Website Branding

1. Go to **Settings**.
2. Update website name, email, footer, social links, live chat ID, logo, favicon, and banners.
3. Save the settings.

## CodeCanyon Listing Feature Summary

You can adapt this section for a marketplace product description.

### Product Title Ideas

- Lawyerson - Law Firm Website with PHP Admin Dashboard
- LegalPro PHP - Responsive Law Firm Website and CMS
- Attorney Firm CMS - Legal Website with Booking, Blog, Messages and Admin Panel

### Short Description

Lawyerson is a complete PHP and MySQL law firm website with a professional public frontend and a powerful admin dashboard. Manage consultation bookings, client messages, blog posts, attorneys, media, branding, banners, social links, live chat ID, and admin password from one responsive dashboard.

### Main Selling Points

- Complete legal website solution
- No framework required
- Easy to install on shared hosting
- Admin dashboard included
- Booking and contact message management
- Built-in blog/case publishing system
- Media library and branding controls
- Secure admin password update
- Modern responsive design
- Ideal for lawyers, legal consultants, law firms, and agencies

### Buyer Benefits

- Launch a professional law firm website quickly
- Manage content without editing code
- Capture consultation requests directly from the website
- Organize client enquiries in the admin inbox
- Publish legal articles for SEO and authority building
- Customize logo, favicon, banners, contact details, and social links
- Works on desktop, tablet, and mobile devices

## Customization Notes

- Colors and typography can be adjusted in `assets/css/style.css` and `views/layouts/admin.php`.
- Public page content is stored in `views/public/`.
- Admin page layouts are stored in `views/admin/`.
- Database connection settings are stored in `config/database.php`.
- Uploaded images are saved in the `uploads/` folder.
- Dynamic settings are stored in the `settings` database table.

## Recommended First Steps After Installation

1. Change the admin password.
2. Update the website name and contact email.
3. Upload your logo and favicon.
4. Replace default banner images.
5. Add real attorneys.
6. Add real practice-area content or blog posts.
7. Test the consultation and contact forms.
8. Configure Tawk.to live chat ID if needed.

## Notes for Deployment

- Make sure the `uploads/` folder is writable on your server.
- Update `config/database.php` with live hosting database credentials.
- Import `database.sql` into the live database.
- Use strong admin credentials after installation.
- Keep regular database and upload backups.
- For production, run the site over HTTPS.

## License and Usage

This project can be customized for law firms, legal consultants, attorneys, agencies, and business websites. If you are preparing it for marketplace sale, add your own license terms, support policy, update policy, and included/excluded services.

