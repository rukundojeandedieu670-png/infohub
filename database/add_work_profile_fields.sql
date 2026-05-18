-- Migration: Add work-related fields to users table
-- This adds professional fields for users interested in jobs and business

-- Add work-related columns to users table
ALTER TABLE users ADD COLUMN job_title VARCHAR(100) DEFAULT NULL;
ALTER TABLE users ADD COLUMN company VARCHAR(150) DEFAULT NULL;
ALTER TABLE users ADD COLUMN industry VARCHAR(100) DEFAULT NULL;
ALTER TABLE users ADD COLUMN skills JSON DEFAULT NULL;
ALTER TABLE users ADD COLUMN experience_years INT UNSIGNED DEFAULT NULL;
ALTER TABLE users ADD COLUMN bio_professional TEXT DEFAULT NULL;
ALTER TABLE users ADD COLUMN linkedin_url VARCHAR(255) DEFAULT NULL;
ALTER TABLE users ADD COLUMN portfolio_url VARCHAR(255) DEFAULT NULL;
ALTER TABLE users ADD COLUMN is_job_seeker BOOLEAN DEFAULT FALSE;
ALTER TABLE users ADD COLUMN is_business_owner BOOLEAN DEFAULT FALSE;

-- Create index for job seeker and business owner searches
ALTER TABLE users ADD INDEX idx_job_seeker (is_job_seeker);
ALTER TABLE users ADD INDEX idx_business_owner (is_business_owner);
