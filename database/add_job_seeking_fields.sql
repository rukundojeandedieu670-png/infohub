-- Migration: Add job-seeking fields to users table for unemployed and all job seekers
-- This adds comprehensive employment status and job opportunity fields

-- Add job-seeking related columns to users table
ALTER TABLE users ADD COLUMN employment_status VARCHAR
(50) DEFAULT NULL COMMENT 'Employment status: Employed, Unemployed, Self-employed, Freelancer, Student, Career Break';
ALTER TABLE users ADD COLUMN job_seeking_status VARCHAR
(50) DEFAULT NULL COMMENT 'Job seeking intent: Actively Looking, Open to Offers, Not Looking';
ALTER TABLE users ADD COLUMN preferred_job_titles TEXT DEFAULT NULL COMMENT 'Preferred job titles the user is interested in';
ALTER TABLE users ADD COLUMN preferred_location VARCHAR
(255) DEFAULT NULL COMMENT 'Preferred work location (city, remote, or flexible)';
ALTER TABLE users ADD COLUMN availability VARCHAR
(50) DEFAULT NULL COMMENT 'Availability: Immediately Available, 2 Weeks Notice, 1 Month Notice, 2 Months Notice, 3 Months Notice';
ALTER TABLE users ADD COLUMN salary_expectation INT UNSIGNED DEFAULT NULL COMMENT 'Expected monthly salary in RWF';
ALTER TABLE users ADD COLUMN willing_to_relocate BOOLEAN DEFAULT FALSE COMMENT 'Whether user is willing to relocate for work';
ALTER TABLE users ADD COLUMN open_to_opportunities BOOLEAN DEFAULT FALSE COMMENT 'Whether user is open to new job opportunities';

-- Create indexes for job seeker searches
ALTER TABLE users ADD INDEX idx_employment_status (employment_status);
ALTER TABLE users ADD INDEX idx_job_seeking_status (job_seeking_status);
ALTER TABLE users ADD INDEX idx_availability (availability);
