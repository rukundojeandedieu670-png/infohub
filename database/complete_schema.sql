-- InfoHub Complete Database Schema
-- Based on Technical Blueprint - All Tables

-- Disable foreign key checks to allow dropping
SET FOREIGN_KEY_CHECKS = 0;

-- Drop all existing tables
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS abuse_reports;
DROP TABLE IF EXISTS reactions;
DROP TABLE IF EXISTS bookmarks;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS job_applications;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS scholarships;
DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS announcements;
DROP TABLE IF EXISTS educational_resources;
DROP TABLE IF EXISTS business_services;
DROP TABLE IF EXISTS business_verification_documents;
DROP TABLE IF EXISTS businesses;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS roles;
DROP TABLE IF EXISTS activity_logs;
DROP TABLE IF EXISTS auth_logs;
DROP TABLE IF EXISTS admin_logs;
DROP TABLE IF EXISTS error_logs;

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- 1. ROLES TABLE - User role definitions
-- ============================================
CREATE TABLE IF NOT EXISTS roles (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE,
  description TEXT,
  permissions JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO roles (name, description, permissions) VALUES
  ('Super Admin', 'Full system access', '["*"]'),
  ('Admin', 'Administrator access', '["users", "content", "businesses", "jobs", "payments", "reports"]'),
  ('Editor', 'Content editor and publisher', '["posts", "categories", "comments"]'),
  ('Writer', 'Creates articles and updates', '["posts.create", "posts.edit_own"]'),
  ('Business Owner', 'Manages business listings', '["businesses.edit_own", "services"]'),
  ('Employer', 'Posts and manages jobs', '["jobs.create", "jobs.edit_own", "applications"]'),
  ('Registered User', 'Basic user interactions', '["comments", "bookmarks", "profiles"]');

-- ============================================
-- 2. USERS TABLE - User accounts
-- ============================================
CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  avatar VARCHAR(255),
  bio TEXT,
  location VARCHAR(255),
  role_id INT UNSIGNED NOT NULL DEFAULT 7,
  is_active BOOLEAN NOT NULL DEFAULT TRUE,
  email_verified BOOLEAN NOT NULL DEFAULT FALSE,
  email_verified_at TIMESTAMP NULL,
  last_login_at TIMESTAMP NULL,
  social_links JSON,
  preferences JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id),
  INDEX idx_email (email),
  INDEX idx_role_id (role_id),
  INDEX idx_created_at (created_at),
  FULLTEXT INDEX idx_name_search (first_name, last_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 3. CATEGORIES TABLE - Content categories
-- ============================================
CREATE TABLE IF NOT EXISTS categories (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) NOT NULL UNIQUE,
  description TEXT,
  icon VARCHAR(50),
  image VARCHAR(255),
  parent_id INT UNSIGNED,
  is_active BOOLEAN DEFAULT TRUE,
  sort_order INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (parent_id) REFERENCES categories(id),
  INDEX idx_slug (slug),
  INDEX idx_is_active (is_active),
  INDEX idx_parent_id (parent_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 4. POSTS TABLE - News and articles
-- ============================================
CREATE TABLE IF NOT EXISTS posts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  excerpt VARCHAR(500),
  content LONGTEXT NOT NULL,
  author_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED,
  featured_image VARCHAR(255),
  status ENUM('draft', 'published', 'archived', 'scheduled') DEFAULT 'draft',
  type ENUM('article', 'news', 'announcement') DEFAULT 'article',
  is_featured BOOLEAN DEFAULT FALSE,
  is_trending BOOLEAN DEFAULT FALSE,
  views_count INT DEFAULT 0,
  seo_title VARCHAR(255),
  seo_description VARCHAR(500),
  seo_keywords VARCHAR(255),
  published_by INT UNSIGNED,
  scheduled_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  published_at TIMESTAMP NULL,
  FOREIGN KEY (author_id) REFERENCES users(id),
  FOREIGN KEY (published_by) REFERENCES users(id),
  FOREIGN KEY (category_id) REFERENCES categories(id),
  INDEX idx_slug (slug),
  INDEX idx_status (status),
  INDEX idx_featured (is_featured),
  INDEX idx_author_id (author_id),
  INDEX idx_created_at (created_at),
  FULLTEXT INDEX idx_content_search (title, excerpt, content)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 5. ANNOUNCEMENTS TABLE - Platform announcements
-- ============================================
CREATE TABLE IF NOT EXISTS announcements (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  author_id INT UNSIGNED NOT NULL,
  priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
  target_audience ENUM('all', 'users', 'businesses', 'employers') DEFAULT 'all',
  is_published BOOLEAN DEFAULT TRUE,
  published_at TIMESTAMP NULL,
  expires_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES users(id),
  INDEX idx_priority (priority),
  INDEX idx_published_at (published_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 6. EVENTS TABLE - Events and meetings
-- ============================================
CREATE TABLE IF NOT EXISTS events (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description LONGTEXT NOT NULL,
  organizer_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED,
  location VARCHAR(255),
  image VARCHAR(255),
  start_date DATETIME NOT NULL,
  end_date DATETIME NOT NULL,
  is_online BOOLEAN DEFAULT FALSE,
  online_link VARCHAR(255),
  max_attendees INT,
  attendee_count INT DEFAULT 0,
  status ENUM('draft', 'published', 'ongoing', 'completed', 'cancelled') DEFAULT 'draft',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (organizer_id) REFERENCES users(id),
  FOREIGN KEY (category_id) REFERENCES categories(id),
  INDEX idx_slug (slug),
  INDEX idx_start_date (start_date),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 7. EDUCATIONAL RESOURCES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS educational_resources (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description TEXT,
  content LONGTEXT,
  resource_type ENUM('course', 'tutorial', 'guide', 'webinar', 'ebook', 'video') DEFAULT 'guide',
  author_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED,
  level ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'intermediate',
  duration_hours INT,
  thumbnail VARCHAR(255),
  file_url VARCHAR(255),
  is_published BOOLEAN DEFAULT FALSE,
  views_count INT DEFAULT 0,
  downloads_count INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (author_id) REFERENCES users(id),
  FOREIGN KEY (category_id) REFERENCES categories(id),
  INDEX idx_slug (slug),
  INDEX idx_resource_type (resource_type),
  INDEX idx_level (level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 8. SCHOLARSHIPS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS scholarships (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description LONGTEXT NOT NULL,
  organization VARCHAR(255),
  amount DECIMAL(12, 2),
  currency VARCHAR(3) DEFAULT 'RWF',
  level ENUM('secondary', 'undergraduate', 'graduate', 'vocational') DEFAULT 'undergraduate',
  field_of_study VARCHAR(255),
  eligibility_criteria TEXT,
  application_deadline DATE,
  posted_by INT UNSIGNED NOT NULL,
  application_url VARCHAR(255),
  is_active BOOLEAN DEFAULT TRUE,
  views_count INT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (posted_by) REFERENCES users(id),
  INDEX idx_slug (slug),
  INDEX idx_application_deadline (application_deadline),
  INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 9. JOBS TABLE - Job listings
-- ============================================
CREATE TABLE IF NOT EXISTS jobs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description LONGTEXT NOT NULL,
  requirements LONGTEXT,
  benefits TEXT,
  employer_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED,
  salary_min DECIMAL(10, 2),
  salary_max DECIMAL(10, 2),
  currency VARCHAR(3) DEFAULT 'RWF',
  location VARCHAR(255),
  is_remote BOOLEAN DEFAULT FALSE,
  job_type ENUM('full-time', 'part-time', 'contract', 'temporary', 'internship') DEFAULT 'full-time',
  experience_level ENUM('entry', 'mid', 'senior', 'executive') DEFAULT 'mid',
  status ENUM('open', 'closed', 'on-hold', 'filled') DEFAULT 'open',
  is_featured BOOLEAN DEFAULT FALSE,
  application_count INT DEFAULT 0,
  views_count INT DEFAULT 0,
  published_at TIMESTAMP NULL,
  deadline TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (employer_id) REFERENCES users(id),
  FOREIGN KEY (category_id) REFERENCES categories(id),
  INDEX idx_slug (slug),
  INDEX idx_status (status),
  INDEX idx_featured (is_featured),
  INDEX idx_employer_id (employer_id),
  INDEX idx_deadline (deadline),
  FULLTEXT INDEX idx_job_search (title, description, requirements)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 10. JOB APPLICATIONS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS job_applications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  job_id INT UNSIGNED NOT NULL,
  applicant_id INT UNSIGNED NOT NULL,
  cv_path VARCHAR(255),
  cover_letter LONGTEXT,
  status ENUM('pending', 'reviewed', 'shortlisted', 'rejected', 'accepted', 'withdrawn') DEFAULT 'pending',
  rating INT,
  notes TEXT,
  rejection_reason TEXT,
  applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
  FOREIGN KEY (applicant_id) REFERENCES users(id),
  INDEX idx_job_id (job_id),
  INDEX idx_applicant_id (applicant_id),
  INDEX idx_status (status),
  UNIQUE KEY unique_application (job_id, applicant_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 11. BUSINESSES TABLE - Business directory
-- ============================================
CREATE TABLE IF NOT EXISTS businesses (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL UNIQUE,
  description LONGTEXT,
  owner_id INT UNSIGNED NOT NULL,
  email VARCHAR(255),
  phone VARCHAR(20),
  website VARCHAR(255),
  logo VARCHAR(255),
  cover_image VARCHAR(255),
  category_id INT UNSIGNED,
  location VARCHAR(255),
  coordinates JSON,
  subscription_level ENUM('free', 'premium', 'verified') DEFAULT 'free',
  verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
  verified_at TIMESTAMP NULL,
  verified_by INT UNSIGNED,
  is_featured BOOLEAN DEFAULT FALSE,
  is_active BOOLEAN DEFAULT TRUE,
  views INT DEFAULT 0,
  rating DECIMAL(3,2) DEFAULT 0,
  rating_count INT DEFAULT 0,
  business_hours JSON,
  social_links JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (owner_id) REFERENCES users(id),
  FOREIGN KEY (verified_by) REFERENCES users(id),
  FOREIGN KEY (category_id) REFERENCES categories(id),
  INDEX idx_slug (slug),
  INDEX idx_verification_status (verification_status),
  INDEX idx_owner_id (owner_id),
  INDEX idx_featured (is_featured),
  FULLTEXT INDEX idx_business_search (name, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 12. BUSINESS SERVICES TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS business_services (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id INT UNSIGNED NOT NULL,
  name VARCHAR(255) NOT NULL,
  description TEXT,
  price DECIMAL(10, 2),
  currency VARCHAR(3) DEFAULT 'RWF',
  service_category VARCHAR(100),
  is_active BOOLEAN DEFAULT TRUE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (business_id) REFERENCES businesses(id) ON DELETE CASCADE,
  INDEX idx_business_id (business_id),
  INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 13. BUSINESS VERIFICATION DOCUMENTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS business_verification_documents (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  business_id INT UNSIGNED NOT NULL,
  document_type VARCHAR(50) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
  verified_by INT UNSIGNED,
  verified_at TIMESTAMP NULL,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (business_id) REFERENCES businesses(id) ON DELETE CASCADE,
  FOREIGN KEY (verified_by) REFERENCES users(id),
  INDEX idx_business_id (business_id),
  INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 14. COMMENTS TABLE - Post and content comments
-- ============================================
CREATE TABLE IF NOT EXISTS comments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  parent_id INT UNSIGNED,
  content TEXT NOT NULL,
  status ENUM('pending', 'approved', 'spam', 'trash') DEFAULT 'pending',
  is_edited BOOLEAN DEFAULT FALSE,
  edited_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (parent_id) REFERENCES comments(id),
  INDEX idx_post_id (post_id),
  INDEX idx_user_id (user_id),
  INDEX idx_status (status),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 15. REACTIONS TABLE - Likes and reactions
-- ============================================
CREATE TABLE IF NOT EXISTS reactions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_id INT UNSIGNED NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  reaction_type ENUM('like', 'love', 'haha', 'wow', 'sad', 'angry') DEFAULT 'like',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
  FOREIGN KEY (user_id) REFERENCES users(id),
  INDEX idx_post_id (post_id),
  INDEX idx_user_id (user_id),
  UNIQUE KEY unique_reaction (post_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 16. BOOKMARKS TABLE - Saved content
-- ============================================
CREATE TABLE IF NOT EXISTS bookmarks (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  post_id INT UNSIGNED,
  job_id INT UNSIGNED,
  business_id INT UNSIGNED,
  bookmark_type ENUM('post', 'job', 'business', 'event', 'scholarship') DEFAULT 'post',
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
  FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
  FOREIGN KEY (business_id) REFERENCES businesses(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_bookmark_type (bookmark_type),
  UNIQUE KEY unique_bookmark (user_id, post_id, job_id, business_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 17. NOTIFICATIONS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS notifications (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  title VARCHAR(255) NOT NULL,
  message TEXT,
  notification_type ENUM('comment', 'application', 'message', 'job_alert', 'announcement', 'system') DEFAULT 'system',
  related_id INT UNSIGNED,
  related_type VARCHAR(50),
  is_read BOOLEAN DEFAULT FALSE,
  read_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_user_id (user_id),
  INDEX idx_is_read (is_read),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 18. ABUSE REPORTS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS abuse_reports (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reporter_id INT UNSIGNED NOT NULL,
  reported_item_type ENUM('post', 'comment', 'business', 'job', 'user') DEFAULT 'post',
  reported_item_id INT UNSIGNED NOT NULL,
  reason ENUM('spam', 'abuse', 'harassment', 'inappropriate', 'illegal', 'other') DEFAULT 'other',
  description TEXT,
  status ENUM('pending', 'investigating', 'resolved', 'dismissed') DEFAULT 'pending',
  resolved_by INT UNSIGNED,
  resolution_notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  resolved_at TIMESTAMP NULL,
  FOREIGN KEY (reporter_id) REFERENCES users(id),
  FOREIGN KEY (resolved_by) REFERENCES users(id),
  INDEX idx_status (status),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 19. PAYMENTS TABLE - Transactions and subscriptions
-- ============================================
CREATE TABLE IF NOT EXISTS payments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  payment_type ENUM('subscription', 'featured_posting', 'featured_business', 'job_posting', 'verification', 'donation', 'other') DEFAULT 'subscription',
  amount DECIMAL(10, 2) NOT NULL,
  currency VARCHAR(3) DEFAULT 'RWF',
  status ENUM('pending', 'completed', 'failed', 'refunded', 'cancelled') DEFAULT 'pending',
  payment_method ENUM('bank_transfer', 'mobile_money', 'credit_card', 'paypal', 'stripe') DEFAULT 'mobile_money',
  transaction_id VARCHAR(255) UNIQUE,
  description TEXT,
  related_entity_type VARCHAR(50),
  related_entity_id INT UNSIGNED,
  subscription_period_months INT,
  expires_at TIMESTAMP NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  completed_at TIMESTAMP NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  INDEX idx_user_id (user_id),
  INDEX idx_status (status),
  INDEX idx_payment_type (payment_type),
  INDEX idx_transaction_id (transaction_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 20. ACTIVITY LOGS TABLE - User actions
-- ============================================
CREATE TABLE IF NOT EXISTS activity_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED,
  action VARCHAR(100) NOT NULL,
  module VARCHAR(50),
  description TEXT,
  ip_address VARCHAR(45),
  user_agent VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
  INDEX idx_user_id (user_id),
  INDEX idx_action (action),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 21. AUTHENTICATION LOGS TABLE
-- ============================================
CREATE TABLE IF NOT EXISTS auth_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255),
  action VARCHAR(50) NOT NULL,
  success BOOLEAN DEFAULT FALSE,
  failure_reason VARCHAR(255),
  ip_address VARCHAR(45),
  user_agent VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_email (email),
  INDEX idx_action (action),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 22. ADMIN LOGS TABLE - Administrative actions
-- ============================================
CREATE TABLE IF NOT EXISTS admin_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  admin_id INT UNSIGNED NOT NULL,
  action VARCHAR(100),
  target_type VARCHAR(50),
  target_id INT UNSIGNED,
  changes JSON,
  ip_address VARCHAR(45),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (admin_id) REFERENCES users(id),
  INDEX idx_admin_id (admin_id),
  INDEX idx_action (action),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 23. ERROR LOGS TABLE - System errors
-- ============================================
CREATE TABLE IF NOT EXISTS error_logs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  error_type VARCHAR(100),
  error_message TEXT,
  error_file VARCHAR(255),
  error_line INT,
  stack_trace LONGTEXT,
  context JSON,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_error_type (error_type),
  INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
