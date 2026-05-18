-- InfoHub Database Seed - Test Data
-- Run this after schema.sql to populate test data

USE infohub;

-- Insert test categories
INSERT INTO categories
    (name, slug, description, is_active)
VALUES
    ('Technology', 'technology', 'Technology news and jobs', 1),
    ('Business', 'business', 'Business opportunities', 1),
    ('Finance', 'finance', 'Financial news and opportunities', 1),
    ('Health', 'health', 'Health and wellness', 1),
    ('Education', 'education', 'Educational opportunities', 1);

-- Insert test user (Admin)
INSERT INTO users
    (first_name, last_name, email, password_hash, role_id, is_active, email_verified)
VALUES
    ('Admin', 'User', 'admin@infohub.com', '$2y$10$abcdefghijklmnopqrstuvwxyz', 2, 1, 1);

-- Insert test editor user
INSERT INTO users
    (first_name, last_name, email, password_hash, role_id, is_active, email_verified)
VALUES
    ('John', 'Smith', 'john@infohub.com', '$2y$10$abcdefghijklmnopqrstuvwxyz', 3, 1, 1);

-- Insert test employer user
INSERT INTO users
    (first_name, last_name, email, password_hash, role_id, is_active, email_verified)
VALUES
    ('Jane', 'Doe', 'employer@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz', 4, 1, 1);

-- Insert test posts
INSERT INTO posts
    (title, slug, excerpt, content, author_id, category_id, status, is_featured, published_at)
VALUES
    (
        'Rwanda Tech Innovation Hub Launches',
        'rwanda-tech-innovation-hub-launches',
        'A new innovation hub has opened in Kigali to foster tech entrepreneurship',
        'Rwanda has officially launched a new technology innovation hub in Kigali aimed at fostering entrepreneurship and innovation in the technology sector. The hub will provide mentorship, funding, and workspace to startup companies...',
        2,
        1,
        'published',
        1,
        NOW()
  ),
    (
        'Digital Transformation in Rwanda',
        'digital-transformation-rwanda',
        'Government initiatives driving digital transformation across the nation',
        'The Rwandan government has announced new initiatives to accelerate digital transformation across all sectors. These initiatives include infrastructure development, skills training, and business digitalization programs...',
        2,
        1,
        'published',
        1,
        NOW()
  ),
    (
        'New Financial Services Platform',
        'new-financial-services-platform',
        'A new fintech platform launched to serve underbanked communities',
        'A innovative fintech startup has launched a new platform designed to provide financial services to underbanked communities across Rwanda. The platform offers mobile banking, savings, and microloans...',
        2,
        3,
        'published',
        0,
        NOW()
  );

-- Insert test jobs
INSERT INTO jobs
    (title, slug, description, requirements, employer_id, category_id, salary_min, salary_max, location, job_type, status, featured, published_at, deadline)
VALUES
    (
        'Senior Software Developer',
        'senior-software-developer',
        'We are looking for an experienced senior software developer to join our growing team. You will be responsible for designing and developing scalable web applications using modern technologies.',
        'Bachelor degree in Computer Science or related field, 5+ years of experience, Proficiency in PHP, Python, or Java, Experience with databases and APIs',
        3,
        1,
        1500000,
        2000000,
        'Kigali',
        'full-time',
        'open',
        1,
        NOW(),
        DATE_ADD(NOW(), INTERVAL
30 DAY)
  ),
(
    'Business Analyst',
    'business-analyst',
    'Join our team as a Business Analyst to help drive business decisions through data analysis and insights.',
    'Bachelor degree in Business, Economics, or related field, 3+ years of experience in business analysis, Strong analytical and communication skills',
    3,
    1,
    1000000,
    1500000,
    'Kigali',
    'full-time',
    'open',
    0,
    NOW
(),
    DATE_ADD
(NOW
(), INTERVAL 30 DAY)
  ),
(
    'Marketing Manager',
    'marketing-manager',
    'Lead our marketing team to develop and execute marketing strategies for our products and services.',
    'Bachelor degree in Marketing or related field, 4+ years of marketing experience, Experience with digital marketing and social media',
    3,
    1,
    1200000,
    1800000,
    'Kigali',
    'full-time',
    'open',
    1,
    NOW
(),
    DATE_ADD
(NOW
(), INTERVAL 30 DAY)
  );

-- Insert test businesses
INSERT INTO businesses
    (name, slug, description, owner_id, email, phone, website, category_id, location, verification_status, is_featured, is_active)
VALUES
    (
        'TechStart Rwanda',
        'techstart-rwanda',
        'A leading technology startup providing software solutions and IT services to businesses across Rwanda and the East African region.',
        3,
        'info@techstart.com',
        '+250788123456',
        'www.techstart.com',
        1,
        'Kigali',
        'verified',
        1,
        1
  ),
    (
        'Green Business Solutions',
        'green-business-solutions',
        'Sustainable business consulting firm helping companies adopt green practices and environmental responsibility.',
        3,
        'contact@greenbiz.com',
        '+250788654321',
        'www.greenbiz.com',
        2,
        'Kigali',
        'verified',
        0,
        1
  );
