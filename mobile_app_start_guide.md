# Lawyerson Mobile App Start Guide

This project is now ready for the first mobile-app step: exposing website data through API endpoints.

## What Was Added

The `api` folder contains mobile-friendly JSON endpoints:

- `GET /api/index.php` - lists available API endpoints
- `GET /api/attorneys.php` - returns attorney/team profiles
- `GET /api/posts.php` - returns published blog posts
- `GET /api/posts.php?id=1` - returns one published post by ID
- `GET /api/posts.php?slug=example-slug` - returns one published post by slug
- `GET /api/settings.php` - returns public website settings
- `POST /api/consultation.php` - saves a consultation booking
- `POST /api/contact.php` - saves a contact message

## How To Test Locally

1. Open XAMPP Control Panel.
2. Start Apache.
3. Start MySQL.
4. Visit this URL in your browser:

```text
http://localhost/lawyerson/api/index.php
```

Then test the attorneys endpoint:

```text
http://localhost/lawyerson/api/attorneys.php
```

If the database is connected and has attorney records, you should see JSON data.

## How The Mobile App Will Use This

The mobile app will call these URLs to get data and submit forms.

Example:

```text
Mobile app -> /api/attorneys.php -> MySQL database -> attorney list
```

For bookings:

```text
Mobile app -> /api/consultation.php -> appointments table -> admin dashboard
```

For contact messages:

```text
Mobile app -> /api/contact.php -> messages table -> admin dashboard
```

## Next Step

After confirming these API URLs work in the browser, create the React Native/Expo mobile app and connect its screens to these endpoints.
