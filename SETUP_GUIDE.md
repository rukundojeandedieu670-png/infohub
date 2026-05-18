# InfoHub - Rwanda National Digital Information System

A modern, production-ready PHP MVC platform for managing news, jobs, and business information.

## 🚀 Quick Start

### Requirements
- PHP 7.4+
- MySQL 5.7+
- Apache with mod_rewrite enabled
- WAMP64 or similar development environment

### Installation Steps

1. **Import Database**
   - Open phpMyAdmin
   - Create new database named `infohub`
   - Import `database.sql` file
   - Default admin: `admin@infohub.rw` / `Admin@123456`

2. **Configure Database**
   - Edit `config/database.php`
   - Update DB_HOST, DB_USER, DB_PASS if needed

3. **Enable mod_rewrite** (for clean URLs)
   - Enable Apache rewrite module
   - .htaccess file is already configured

4. **Create Upload Directories**
   ```bash
   mkdir -p public/uploads/cvs
   mkdir -p public/uploads/logos
   mkdir -p public/uploads/images
   chmod 755 public/uploads -R
   ```

5. **Access Application**
   - Home: `http://localhost/infohub`
   - Login: `http://localhost/infohub/auth/login`
   - Admin: `http://localhost/infohub/admin`

## 📁 Project Structure

```
/infohub
  /app
    /controllers      # Application logic
    /models          # Database models
    /views           # UI templates
  /core
    Database.php     # Database singleton
    Router.php       # URL routing
    Controller.php   # Base controller class
    Model.php        # Base model class
    Logger.php       # Logging system
  /config
    database.php     # Database configuration
  /public
    /assets
      /css           # Stylesheets
      /js            # JavaScript
      /img           # Images
    /uploads         # User uploads
  /modules           # Feature modules
  database.sql       # Database schema
```

## 🎨 Design System

- **Primary Color**: #16a34a (Green)
- **Secondary Color**: #2563eb (Blue)
- **Modern, card-based UI** with smooth animations
- **Mobile-first responsive design**
- **Professional SaaS aesthetic**

## 🔐 Security Features

- ✅ CSRF Protection
- ✅ Prepared Statements (SQL Injection Prevention)
- ✅ Password Hashing (bcrypt)
- ✅ Session Management
- ✅ Input Sanitization
- ✅ Access Control (Role-based)
- ✅ Comprehensive Logging

## 📋 Features Implemented

### Phase 1 (Current)
- ✅ Authentication System (Register/Login/Logout)
- ✅ User Management with Role-based Access
- ✅ Logging System (Activity, Auth, Admin, Error logs)
- ✅ News/CMS System (Create, edit, delete posts)
- ✅ Job Board (Post jobs, Apply for jobs)
- ✅ Business Directory (Profiles, Verification)
- ✅ Admin Dashboard
- ✅ SEO System (Sitemaps, Meta tags)
- ✅ Modern, responsive UI

### Future Phases
- REST API
- Flutter Mobile App
- AI Recommendation System
- Elasticsearch Integration
- Payment Gateway

## 👥 User Roles

1. **Super Admin** - Full system access
2. **Admin** - Administrative functions
3. **Editor** - Create and publish posts
4. **Employer** - Post and manage jobs
5. **Business Owner** - Manage business profile
6. **User** - Regular user account

## 📊 Database Tables

- **users** - User accounts
- **roles** - User roles and permissions
- **posts** - News articles and CMS content
- **categories** - Post categories
- **jobs** - Job listings
- **job_applications** - Job applications
- **businesses** - Business directory
- **activity_logs** - User activity tracking
- **auth_logs** - Authentication attempts
- **admin_logs** - Admin actions
- **error_logs** - System errors

## 🔑 Default Credentials

**Super Admin Account**
- Email: `admin@infohub.rw`
- Password: `Admin@123456`

⚠️ **IMPORTANT**: Change these credentials in production!

## 📝 Configuration

Edit `config/database.php` to customize:
- Database connection details
- Application settings
- Security keys
- Email configuration
- Upload limits

## 🛠️ Development Notes

### Adding a New Controller
```php
class MyController extends Controller {
    public function action() {
        $this->requireLogin();  // Require authentication
        $this->view('my-view', ['data' => $data]);
    }
}
```

### Adding a New Model
```php
class MyModel extends Model {
    protected $table = 'my_table';
    
    public function customMethod() {
        // Custom database logic
    }
}
```

### Logging Activities
```php
Logger::logActivity($userId, 'action', 'module', 'description');
Logger::logAuth($email, 'login_attempt', true);
Logger::logAdminAction($adminId, 'action', 'target_type', $targetId);
Logger::logError('Error Type', 'Error message');
```

## 📞 Support

For issues or questions, refer to the documentation in the comment blocks throughout the code.

## 📄 License

This project is built for the Rwanda national digital information system.

---

Built with ❤️ for Rwanda | InfoHub Platform 2024
