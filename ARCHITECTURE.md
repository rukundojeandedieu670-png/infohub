# InfoHub Platform - Architecture & API Reference

## 🏗️ System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                      USER INTERFACE (HTML5/CSS3)            │
│  - Responsive Design  - Modern SaaS Style  - Mobile First   │
└──────────────────────┬──────────────────────────────────────┘
                       │
┌──────────────────────▼──────────────────────────────────────┐
│                   ROUTER (index.php)                         │
│  - URL Pattern Matching - Request Dispatching              │
└──────────────────────┬──────────────────────────────────────┘
                       │
┌──────────────────────▼──────────────────────────────────────┐
│                   CONTROLLERS                                │
│  - Business Logic  - Request Handling  - Response Building  │
│  HomeController | AuthController | NewsController | etc...  │
└──────────────────────┬──────────────────────────────────────┘
                       │
        ┌──────────────┴────────────────┐
        │                               │
┌───────▼─────────┐         ┌──────────▼────────────┐
│     MODELS      │         │   SECURITY & UTILS    │
│ (Data Access)   │         │ Logger | CSRF | Hash  │
│ User            │         │ Sanitize | Validate   │
│ Post            │         └───────────────────────┘
│ Job             │
│ Business        │
│ Category        │
└────────┬────────┘
         │
┌────────▼──────────────────────────────────────────────────┐
│                    DATABASE (MySQL)                        │
│  8 Core Tables | 4 Logging Tables | Foreign Keys          │
│  users | posts | jobs | businesses | activity_logs | ...  │
└───────────────────────────────────────────────────────────┘
```

## 🔐 Security Layers

```
Request
   ↓
[CSRF Token Check] ──→ Reject if invalid
   ↓
[Session Validation] ──→ Authenticate user
   ↓
[Role Check] ──→ Authorize action
   ↓
[Input Sanitization] ──→ Clean & escape
   ↓
[Prepared Statements] ──→ Execute safely
   ↓
[Activity Logging] ──→ Audit trail
   ↓
Response
```

## 📊 Data Flow Examples

### User Registration Flow
```
User fills form
    ↓
POST /auth/register
    ↓
[CSRF Token Verified]
    ↓
[Email validation]
    ↓
[Password hashing with bcrypt]
    ↓
User::createUser() → INSERT users
    ↓
Logger::logAuth() → INSERT auth_logs
    ↓
Session created → Redirect to home
```

### Job Application Flow
```
User views job detail
    ↓
Clicks "Apply" button
    ↓
Uploads CV file
    ↓
POST /jobs/apply/{id}
    ↓
[Authentication check]
    ↓
[File upload validation]
    ↓
INSERT job_applications
    ↓
UPDATE jobs (increment count)
    ↓
Logger::logActivity()
    ↓
Success message
```

### Admin Dashboard Flow
```
Admin logs in
    ↓
POST /auth/login
    ↓
Role check: Is 'Admin' or 'Super Admin'?
    ↓
Session stored with role
    ↓
GET /admin/dashboard
    ↓
DashboardController requires admin role
    ↓
Query statistics from all tables
    ↓
Render admin dashboard view
    ↓
Log activity to activity_logs
```

## 🛣️ URL Routing Map

### Public Routes
```
GET  /                          → HomeController@index
GET  /news                      → NewsController@index
GET  /news/{slug}               → NewsController@show
GET  /news/category/{slug}      → NewsController@category
GET  /jobs                      → JobsController@index
GET  /jobs/{slug}               → JobsController@show
GET  /business                  → BusinessController@index
GET  /business/{slug}           → BusinessController@show
GET  /sitemap.xml               → SeoController@sitemap
GET  /robots.txt                → SeoController@robots
```

### Auth Routes
```
GET  /auth/login                → AuthController@login
POST /auth/login                → AuthController@handleLogin
GET  /auth/register             → AuthController@register
POST /auth/register             → AuthController@handleRegister
GET  /auth/logout               → AuthController@logout
GET  /auth/forgot-password      → AuthController@forgotPassword
POST /auth/forgot-password      → AuthController@handleForgotPassword
```

### Protected Routes (Requires Login)
```
GET  /profile                   → ProfileController@show
GET  /profile/edit              → ProfileController@edit
POST /profile/update            → ProfileController@update
POST /jobs/apply/{id}           → JobsController@apply
```

### Employer Routes (Requires Employer Role)
```
GET  /employer/jobs             → EmployerController@jobs
GET  /employer/jobs/create      → EmployerController@createJob
POST /employer/jobs/create      → EmployerController@storeJob
GET  /employer/jobs/{id}/edit   → EmployerController@editJob
POST /employer/jobs/{id}/update → EmployerController@updateJob
GET  /employer/jobs/{id}/apps   → EmployerController@applications
```

### Admin Routes (Requires Admin Role)
```
GET  /admin                     → Admin/DashboardController@index
GET  /admin/dashboard           → Admin/DashboardController@index
GET  /admin/users               → Admin/UsersController@index
GET  /admin/users/{id}          → Admin/UsersController@show
POST /admin/users/{id}/edit     → Admin/UsersController@edit
POST /admin/users/{id}/delete   → Admin/UsersController@delete
GET  /admin/logs                → Admin/LogsController@index
GET  /admin/logs/activity       → Admin/LogsController@activity
GET  /admin/logs/auth           → Admin/LogsController@auth
GET  /admin/logs/errors         → Admin/LogsController@errors
```

## 📱 View Templates Structure

```
/views
  ├── layouts/
  │   ├── main.php          [Header, Nav, Footer - Public pages]
  │   ├── auth.php          [Minimal - Auth pages only]
  │   └── admin.php         [Sidebar, Admin header - Admin pages]
  │
  ├── home.php              [Homepage with featured content]
  ├── profile/
  │   ├── show.php          [User profile view]
  │   └── edit.php          [Edit profile form]
  │
  ├── auth/
  │   ├── login.php         [Login form]
  │   ├── register.php      [Registration form]
  │   └── forgot-password.php [Password reset]
  │
  ├── news/
  │   ├── index.php         [News list with pagination]
  │   ├── show.php          [Full article view]
  │   └── category.php      [Category filtered list]
  │
  ├── jobs/
  │   ├── index.php         [Job listings]
  │   └── show.php          [Job detail + apply form]
  │
  ├── business/
  │   ├── index.php         [Business directory]
  │   └── show.php          [Business profile]
  │
  ├── admin/
  │   ├── dashboard.php     [Stats & activity feed]
  │   ├── users/
  │   │   └── index.php     [User management table]
  │   └── logs/
  │       ├── activity.php  [Activity log table]
  │       ├── auth.php      [Auth attempts log]
  │       └── errors.php    [Error log table]
  │
  └── errors/
      ├── 404.php           [Page not found]
      └── 403.php           [Access denied]
```

## 🔄 Database Relationships

```
users (1) ──────────────── (M) posts
  ↓                              ↓
  ↓                            (M) categories
  ↓
  ├─ (M) job_applications
  │        ↓
  │      jobs
  │        ↓
  │     (M) categories
  │
  ├─ (M) businesses
  │        ↓
  │     (M) categories
  │
  ├─ (M) activity_logs
  ├─ (M) admin_logs
  └─ (1) roles

activity_logs (M) ── (1) users
auth_logs (M) ────── (N) email (not FK)
admin_logs (M) ───── (1) users
error_logs (M) ───── (1) system
```

## 📋 Key Methods Reference

### Controller Methods
```php
$this->view($view, $data)        // Render view with data
$this->requireLogin()            // Check authentication
$this->requireAdmin()            // Check admin role
$this->requireRole($role)        // Check specific role
$this->redirect($url)            // HTTP redirect
$this->jsonResponse($data)       // JSON response
$this->sanitize($data)           // XSS prevention
$this->hashPassword($pwd)        // Bcrypt hash
$this->verifyPassword($pwd,$hash) // Password verify
$this->generateCSRFToken()       // Create CSRF token
$this->verifyCSRFToken($token)   // Validate CSRF
$this->setFlash($type,$msg)      // Flash message
$this->getFlash()                // Retrieve flash
```

### Model Methods
```php
$model->findById($id)            // Get by ID
$model->findAll($limit, $offset) // Get all with pagination
$model->findBy($column, $value)  // Get by column
$model->findAllBy($col, $val)    // Get all by column
$model->count($col, $val)        // Count records
$model->insert($data)            // Create
$model->update($id, $data)       // Update
$model->delete($id)              // Delete
```

### Logger Methods
```php
Logger::logActivity($userId, $action, $module, $desc)
Logger::logAuth($email, $action, $success)
Logger::logAdminAction($adminId, $action, $targetType, $targetId, $changes)
Logger::logError($errorType, $errorMessage, $file, $line)
```

## 🎯 Common Development Tasks

### Add New Module
1. Create controller in `/app/controllers/`
2. Create model in `/app/models/`
3. Create views in `/app/views/{module}/`
4. Add routes to `index.php`
5. Add navigation link to layout

### Add New Field to User
1. Add column to `users` table
2. Update `User::createUser()` method
3. Update profile form in `profile/edit.php`
4. Update `ProfileController::update()` method

### Add New Log Type
1. Create new table in database
2. Create new method in `Logger.php`
3. Call method in appropriate controller
4. Create view to display logs in admin

### Create New Admin Report
1. Create query in admin controller
2. Create view with table
3. Add route to `index.php`
4. Add link in admin sidebar

## 🐛 Troubleshooting Guide

### Issue: "404 - Page not found"
**Cause**: Route not defined or wrong URL
**Fix**: Check route in index.php matches URL pattern

### Issue: "Database connection failed"
**Cause**: Wrong credentials in config/database.php
**Fix**: Update DB_HOST, DB_USER, DB_PASS

### Issue: "CSRF token validation failed"
**Cause**: Form missing csrf_token hidden field
**Fix**: Add `<input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">`

### Issue: Uploads not working
**Cause**: Directory not writable
**Fix**: `chmod 755 public/uploads -R`

### Issue: Sessions not persisting
**Cause**: Session start missing
**Fix**: `session_start()` is called in index.php

### Issue: Clean URLs not working (.htaccess)
**Cause**: mod_rewrite not enabled
**Fix**: Enable in Apache config or contact hosting

---

## 📈 Performance Optimization Notes

✅ Database queries use LIMIT/OFFSET for pagination
✅ Indexes created on frequently searched columns
✅ Views only receive needed data (no N+1 queries)
✅ CSS/JS minification recommended for production
✅ Gzip compression enabled in .htaccess
✅ Cache headers configured
✅ No external dependencies = fast load times

---

**Built with ❤️ for Rwanda | InfoHub Platform**
