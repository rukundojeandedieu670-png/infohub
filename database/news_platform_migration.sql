-- ===================================================================
-- InfoHub News Platform - Database Migration Script
-- Phase 2B: Database & Backend Enhancement
-- ===================================================================
-- Purpose: Create new tables and alter existing schema for news module
-- URL: http://localhost/infohub/news
-- Date: 2024
-- Version: 1.0
-- ===================================================================

-- ===================================================================
-- TABLE 1: post_bookmarks
-- Purpose: Store user bookmarks (saved articles)
-- ===================================================================
CREATE TABLE IF NOT EXISTS post_bookmarks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Ensure each user can only bookmark a post once
    UNIQUE KEY unique_bookmark (user_id, post_id),
    
    -- Foreign key constraints
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    
    -- Indexes for performance
    INDEX idx_user_id (user_id),
    INDEX idx_post_id (post_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- TABLE 2: newsletter_subscribers
-- Purpose: Store email subscribers for newsletter
-- ===================================================================
CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL,
    
    -- Indexes for performance
    INDEX idx_email (email),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- TABLE 3: content_reports
-- Purpose: Store abuse reports for content moderation
-- ===================================================================
CREATE TABLE IF NOT EXISTS content_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT,
    reason VARCHAR(100) NOT NULL COMMENT 'inappropriate, spam, misinformation, harassment, copyright, other',
    message TEXT COMMENT 'Additional details from reporter',
    status ENUM('pending', 'reviewed', 'resolved') DEFAULT 'pending',
    admin_notes TEXT COMMENT 'Notes from admin review',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reviewed_at TIMESTAMP NULL,
    
    -- Foreign key constraints
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    
    -- Indexes for performance
    INDEX idx_status (status),
    INDEX idx_post_id (post_id),
    INDEX idx_user_id (user_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- TABLE 4: post_views
-- Purpose: Track article views for analytics and trending
-- ===================================================================
CREATE TABLE IF NOT EXISTS post_views (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    referer VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign key constraints
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    
    -- Indexes for performance
    INDEX idx_post_id (post_id),
    INDEX idx_user_id (user_id),
    INDEX idx_created (created_at),
    INDEX idx_post_created (post_id, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- ALTER EXISTING POSTS TABLE
-- Purpose: Add new columns for featured, SEO, views tracking
-- ===================================================================

-- Add column for featured articles (featured slider on homepage)
ALTER TABLE posts ADD COLUMN IF NOT EXISTS is_featured BOOLEAN DEFAULT 0 
AFTER status COMMENT 'Whether article is featured on homepage slider';

-- Add column for SEO meta description
ALTER TABLE posts ADD COLUMN IF NOT EXISTS seo_meta_description VARCHAR(160) 
AFTER slug COMMENT 'SEO meta description for search engines (max 160 chars)';

-- Add column for view counting
ALTER TABLE posts ADD COLUMN IF NOT EXISTS views_count INT DEFAULT 0 
AFTER created_at COMMENT 'Total number of views (denormalized from post_views)';

-- Add column for comment counting
ALTER TABLE posts ADD COLUMN IF NOT EXISTS comments_count INT DEFAULT 0 
AFTER views_count COMMENT 'Total number of comments (denormalized)';

-- ===================================================================
-- CREATE INDEXES ON POSTS TABLE
-- Purpose: Optimize query performance for common operations
-- ===================================================================

-- Index for filtering by status and publication date (homepage, category)
ALTER TABLE posts ADD INDEX IF NOT EXISTS idx_status_published (status, published_at DESC);

-- Index for trending algorithm (views-based ranking)
ALTER TABLE posts ADD INDEX IF NOT EXISTS idx_views (views_count DESC);

-- Index for category-filtered articles with pagination
ALTER TABLE posts ADD INDEX IF NOT EXISTS idx_category_published (category_id, status, published_at DESC);

-- Index for featured articles slider
ALTER TABLE posts ADD INDEX IF NOT EXISTS idx_featured_published (is_featured, status, published_at DESC);

-- ===================================================================
-- VERIFICATION QUERIES
-- Run these to verify migration success
-- ===================================================================

-- Verify new tables created
-- SELECT TABLE_NAME FROM information_schema.TABLES 
-- WHERE TABLE_SCHEMA = DATABASE() 
-- AND TABLE_NAME IN ('post_bookmarks', 'newsletter_subscribers', 'content_reports', 'post_views');

-- Verify new columns on posts table
-- SHOW COLUMNS FROM posts 
-- WHERE Field IN ('is_featured', 'seo_meta_description', 'views_count', 'comments_count');

-- Verify indexes created
-- SHOW INDEX FROM posts 
-- WHERE Key_name IN ('idx_status_published', 'idx_views', 'idx_category_published', 'idx_featured_published');

-- ===================================================================
-- CLEANUP (Optional - comment out if you want to keep for rollback)
-- ===================================================================
-- DROP TABLE IF EXISTS post_bookmarks;
-- DROP TABLE IF EXISTS newsletter_subscribers;
-- DROP TABLE IF EXISTS content_reports;
-- DROP TABLE IF EXISTS post_views;
-- ALTER TABLE posts DROP COLUMN IF EXISTS is_featured;
-- ALTER TABLE posts DROP COLUMN IF EXISTS seo_meta_description;
-- ALTER TABLE posts DROP COLUMN IF EXISTS views_count;
-- ALTER TABLE posts DROP COLUMN IF EXISTS comments_count;

-- ===================================================================
-- MIGRATION COMPLETE
-- Status: Ready for deployment to http://localhost/infohub/news
-- Next: Run verification queries above to confirm success
-- ===================================================================
