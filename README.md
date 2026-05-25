

# 🟣 INFOHUB PHASE 1 — FULL DEVELOPMENT SPEC (COPILOT READY)

### 🟢 PHP + MySQL + MVC + MODERN UI SYSTEM

### 🔵 Kigali, Rwanda | 2025

---

# 🎨 1. INFOHUB DESIGN SYSTEM (IMPORTANT FOR COPILOT)

> This section defines the **visual identity rules** for all UI generation.

---

## 🟢 COLOR SYSTEM

```css
:root {
  --primary: #16a34a;
  --primary-dark: #12823b;
  --primary-light: #e9f9ef;

  --secondary: #2563eb;

  --dark: #0f172a;
  --text: #1f2937;
  --muted: #6b7280;

  --border: #e5e7eb;
  --bg: #ffffff;
  --bg-soft: #f8fafc;

  --shadow-sm: 0 2px 10px rgba(0,0,0,0.06);
  --shadow-md: 0 8px 25px rgba(0,0,0,0.10);

  --radius: 14px;
  --transition: 0.25s ease-in-out;
}
```

---

## 🧠 DESIGN RULES (VERY IMPORTANT)

Copilot MUST follow:

* Use **green (#16a34a)** as primary brand color
* Use **white + soft gray backgrounds**
* Use **card-based layout system**
* Use **rounded corners (14px+)**
* Use **soft shadows (no harsh borders)**
* Mobile-first UI always
* Clean SaaS + government digital platform style

---

## 🟢 UI COMPONENT STYLE

### Buttons

* Rounded full pills
* Green primary buttons
* Hover lift effect

### Cards

* White background
* Soft shadow
* Hover elevation

### Navigation

* Sticky top navbar
* Blur background
* Clean spacing

---

## 📱 RESPONSIVE RULE

* Mobile first (default)
* Tablet adaptation
* Desktop grid expansion

---

# 🧱 2. SYSTEM ARCHITECTURE (MVC)

```plaintext
/infohub
│
├── /app
│   ├── controllers
│   ├── models
│   ├── views
│
├── /modules
│   ├── auth
│   ├── news
│   ├── jobs
│   ├── business
│   ├── logs
│
├── /core
│   ├── Database.php
│   ├── Controller.php
│   ├── Model.php
│   ├── Logger.php
│
├── /admin
├── /public
├── /config
├── index.php
```

---

# 🎯 3. PHASE 1 CORE OBJECTIVE

Build a **fully functional InfoHub MVP system** with:

* Authentication system
* News CMS
* Job board
* Business directory
* Admin dashboard
* Logging system
* SEO-ready frontend
* Mobile-first UI (using design system above)

---

# 🔐 4. AUTHENTICATION SYSTEM

## Features

* Register
* Login
* Logout
* Password hashing (bcrypt)
* Session system
* Role-based access

## Roles

* Super Admin
* Admin
* Editor
* Employer
* Business Owner
* User

---

# 📰 5. NEWS / CMS MODULE

## Features

* Create posts
* Edit posts
* Delete posts
* Categories
* Featured posts

## SEO URLs

```plaintext
/news/rwanda-digital-growth-2025
```

---

# 💼 6. JOB BOARD MODULE

## Features

* Post jobs
* Apply jobs
* Upload CV
* Search jobs
* Save jobs

---

# 🏢 7. BUSINESS DIRECTORY

## Features

* Business profiles
* Logo upload
* Contact info
* Search system
* Verification system

---

# 🛠 8. ADMIN DASHBOARD

## Features

* Manage users
* Manage posts
* Manage jobs
* Verify businesses
* System monitoring

---

# 📊 9. LOGGING SYSTEM (CRITICAL)

## Tracks:

* User actions
* Admin actions
* System errors
* Login attempts

---

## Core Tables

```sql
activity_logs
auth_logs
admin_logs
error_logs
```

---

## Logger Usage

```php
Logger::logActivity();
Logger::logAdminAction();
Logger::logError();
Logger::logAuth();
```

---

# 🔎 10. SEO SYSTEM

* SEO friendly URLs
* Meta tags
* Sitemap.xml
* Open Graph support

---

# 🗄 11. DATABASE CORE

## USERS

* id, name, email, password, role

## POSTS

* id, title, content, category_id

## JOBS

* id, title, description, employer_id

## BUSINESSES

* id, name, owner_id

---

# 🚀 12. FUTURE EXPANSION (IMPORTANT)

This Phase 1 system is designed for:

## Phase 2

* Comments
* Likes
* Notifications
* Profiles

## Phase 3

* Ads system
* Payments
* Subscriptions

## Phase 4

* Flutter mobile app
* Laravel API
* AI recommendations
* Elasticsearch

---

# 🚀 Getting Started

## Local setup

1. Copy the example environment file:

```bash
cp .env.example .env
```

2. Update `.env` with your local values:

```ini
APP_URL=http://localhost/infohub
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
```

3. Start your local web server and database.

4. Open the app in your browser at:

```text
http://localhost/infohub
```

## Git safety

* Never commit `.env`.
* Keep real credentials out of version control.
* Use `.env.example` as the safe template.

## Push guidance after cleanup

If you rewrite history to remove secrets, push with:

```bash
git push --force-with-lease rukundo main
```

> If GitHub blocks the push, ensure `.env` is removed from all commit history and that you have completed browser authentication.

See `CONTRIBUTING.md` for development rules, secret handling, and push guidance.

---

# 🧠 FINAL RULE FOR COPILOT

👉 ALWAYS generate UI using:

* Green primary theme
* Card-based layout
* Clean SaaS design
* Mobile-first structure
* Soft shadows + rounded UI

---


