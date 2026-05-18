-- InfoHub Test Data
-- Comprehensive sample data for development and testing
-- Roles already exist from schema, no need to insert

-- ============================================
-- INSERT USERS
-- ============================================
INSERT INTO users
    (first_name, last_name, email, password_hash, phone, bio, location, role_id, is_active, email_verified)
VALUES
    ('Jean', 'Administrator', 'admin@infohub.rw', '$2y$10$abcdefghijklmnopqrstuvwxyz0123456789', '+250788123456', 'Platform administrator', 'Kigali, Rwanda', 1, TRUE, TRUE),
    ('Marie', 'Editor', 'editor@infohub.rw', '$2y$10$abcdefghijklmnopqrstuvwxyz0123456789', '+250788234567', 'Content editor', 'Kigali, Rwanda', 3, TRUE, TRUE),
    ('Jean Paul', 'Entrepreneurship', 'jean.paul@business.rw', '$2y$10$abcdefghijklmnopqrstuvwxyz0123456789', '+250788345678', 'Business owner', 'Kigali, Rwanda', 5, TRUE, TRUE),
    ('Kwizera', 'Recruitment', 'kwizera@jobs.rw', '$2y$10$abcdefghijklmnopqrstuvwxyz0123456789', '+250788456789', 'HR Manager at Tech Company', 'Kigali, Rwanda', 6, TRUE, TRUE),
    ('Aimable', 'University', 'aimable@edu.rw', '$2y$10$abcdefghijklmnopqrstuvwxyz0123456789', '+250788567890', 'University Administrator', 'Butare, Rwanda', 2, TRUE, TRUE),
    ('Grace', 'Writer', 'grace@infohub.rw', '$2y$10$abcdefghijklmnopqrstuvwxyz0123456789', '+250788678901', 'Technology writer', 'Kigali, Rwanda', 4, TRUE, TRUE),
    ('Mugisha', 'User', 'mugisha@infohub.rw', '$2y$10$abcdefghijklmnopqrstuvwxyz0123456789', '+250788789012', 'Regular user interested in tech', 'Kigali, Rwanda', 7, TRUE, TRUE),
    ('Claudette', 'Services', 'claudette@services.rw', '$2y$10$abcdefghijklmnopqrstuvwxyz0123456789', '+250788890123', 'Service provider', 'Gisenyi, Rwanda', 5, TRUE, TRUE),
    ('Pierre', 'Tech', 'pierre@tech.rw', '$2y$10$abcdefghijklmnopqrstuvwxyz0123456789', '+250788901234', 'Software developer', 'Kigali, Rwanda', 6, TRUE, TRUE),
    ('Yolanda', 'Journalist', 'yolanda@infohub.rw', '$2y$10$abcdefghijklmnopqrstuvwxyz0123456789', '+250789012345', 'Technology journalist', 'Kigali, Rwanda', 4, TRUE, TRUE);

-- ============================================
-- INSERT CATEGORIES
-- ============================================
INSERT INTO categories
    (name, slug, description, icon, is_active, sort_order)
VALUES
    ('Technology', 'technology', 'Latest tech news and articles', 'fa-laptop', TRUE, 1),
    ('Business', 'business', 'Business news and opportunities', 'fa-briefcase', TRUE, 2),
    ('Education', 'education', 'Educational resources and scholarships', 'fa-graduation-cap', TRUE, 3),
    ('Careers', 'careers', 'Job opportunities and career advice', 'fa-id-badge', TRUE, 4),
    ('Health', 'health', 'Health and wellness information', 'fa-heartbeat', TRUE, 5),
    ('Agriculture', 'agriculture', 'Agricultural news and resources', 'fa-leaf', TRUE, 6),
    ('Finance', 'finance', 'Financial news and advice', 'fa-money-bill', TRUE, 7),
    ('Culture', 'culture', 'Cultural events and updates', 'fa-theater-masks', TRUE, 8);

-- ============================================
-- INSERT POSTS
-- ============================================
INSERT INTO posts
    (title, slug, excerpt, content, author_id, category_id, status, type, is_featured, views_count, published_at)
VALUES
    ('The Future of Technology in Rwanda', 'future-technology-rwanda', 'Exploring how technology is transforming Rwanda', 'Rwanda is experiencing rapid technological growth and innovation across various sectors...', 6, 1, 'published', 'article', TRUE, 1250, NOW()),
    ('Top 10 Tech Companies in Kigali', 'top-tech-companies-kigali', 'Leading technology companies in Rwanda', 'Here are the top technology companies making an impact in Kigali...', 10, 1, 'published', 'article', TRUE, 856, NOW()),
    ('Starting Your Own Business in Rwanda', 'starting-business-rwanda', 'A comprehensive guide for entrepreneurs', 'Want to start a business? Here is everything you need to know...', 3, 2, 'published', 'article', FALSE, 542, NOW()),
    ('Agricultural Innovation Trends 2024', 'agricultural-innovation-2024', 'New farming technologies', 'Modern farming methods are revolutionizing agriculture in East Africa...', 6, 6, 'published', 'news', FALSE, 389, NOW()),
    ('Scholarships Available for Study Abroad', 'scholarships-study-abroad', 'International scholarship opportunities', 'Several organizations are offering scholarships for students from Rwanda...', 10, 3, 'published', 'article', TRUE, 2150, NOW()),
    ('Healthcare Sector Growth in Rwanda', 'healthcare-growth-rwanda', 'Healthcare improvements and initiatives', 'Rwanda\'s healthcare sector is experiencing significant improvements...', 6, 5, 'published', 'article', FALSE, 674, NOW()),
  ('Digital Payment Solutions for Small Businesses', 'digital-payments-small-business', 'Fintech for SMEs', 'Mobile money and digital payment solutions are transforming commerce...', 10, 7, 'published', 'article', FALSE, 523, NOW()),
  ('Cultural Events in Rwanda This Month', 'cultural-events-month
', 'Upcoming cultural celebrations', 'Don\'t miss these amazing cultural events happening around the country...', 6, 8, 'published', 'news', FALSE, 412, NOW
());

-- ============================================
-- INSERT ANNOUNCEMENTS
-- ============================================
INSERT INTO announcements
    (title, content, author_id, priority, target_audience, is_published)
VALUES
    ('Welcome to InfoHub Platform', 'Welcome to the all-new InfoHub platform. We are excited to introduce a platform that connects communities.', 1, 'high', 'all', TRUE),
    ('New Business Verification Feature', 'We have launched a new business verification process to ensure quality business listings.', 1, 'medium', 'businesses', TRUE),
    ('Job Posting Campaign - Free for First Month', 'Employers can now post jobs for free during our launch month. Visit careers section to post.', 1, 'high', 'employers', TRUE),
    ('Scholarship Database Now Available', 'Browse thousands of scholarship opportunities from our comprehensive database.', 1, 'medium', 'users', TRUE);

-- ============================================
-- INSERT EVENTS
-- ============================================
INSERT INTO events
    (title, slug, description, organizer_id, category_id, location, start_date, end_date, is_online, max_attendees, status)
VALUES
    ('Tech Startup Summit Rwanda 2024', 'tech-startup-summit', 'Annual gathering of tech innovators and entrepreneurs', 3, 1, 'Kigali Convention Center', '2024-03-15 09:00:00', '2024-03-15 17:00:00', FALSE, 500, 'published'),
    ('Agricultural Innovation Workshop', 'agri-innovation-workshop', 'Learn about modern farming techniques', 1, 6, 'Huye District', '2024-04-10 08:00:00', '2024-04-10 16:00:00', FALSE, 100, 'published'),
    ('Online Business Masterclass', 'business-masterclass', 'Expert-led business training sessions', 2, 2, 'Virtual', '2024-03-20 14:00:00', '2024-03-20 18:00:00', TRUE, 1000, 'published'),
    ('Career Fair - Tech Edition', 'career-fair-tech', 'Meet with top tech companies and get hired', 4, 4, 'Kigali Marriott', '2024-04-05 10:00:00', '2024-04-05 16:00:00', FALSE, 300, 'published'),
    ('Educational Leadership Forum', 'edu-leadership-forum', 'Discussing the future of education in Rwanda', 5, 3, 'National University of Rwanda', '2024-03-25 09:00:00', '2024-03-25 16:00:00', FALSE, 200, 'published');

-- ============================================
-- INSERT EDUCATIONAL RESOURCES
-- ============================================
INSERT INTO educational_resources
    (title, slug, description, content, resource_type, author_id, category_id, level, duration_hours, is_published, views_count)
VALUES
    ('Introduction to Web Development', 'intro-web-dev', 'Learn the basics of web development', 'This course covers HTML, CSS, and JavaScript fundamentals...', 'course', 6, 1, 'beginner', 20, TRUE, 1203),
    ('Digital Marketing Essentials', 'digital-marketing', 'Master the fundamentals of digital marketing', 'Learn about SEO, social media, and content marketing...', 'guide', 10, 2, 'intermediate', 15, TRUE, 856),
    ('Python Programming for Data Science', 'python-data-science', 'Advanced Python skills for data analysis', 'This course teaches Python for data manipulation and analysis...', 'course', 6, 1, 'advanced', 40, TRUE, 2145),
    ('Business Planning Webinar', 'business-planning', 'Create effective business plans', 'Learn how to write a successful business plan...', 'webinar', 3, 2, 'beginner', 3, TRUE, 523),
    ('Agricultural Sustainability Ebook', 'agri-sustainability', 'Sustainable farming practices guide', 'A comprehensive guide to sustainable and modern farming...', 'ebook', 1, 6, 'intermediate', 0, TRUE, 412);

-- ============================================
-- INSERT SCHOLARSHIPS
-- ============================================
INSERT INTO scholarships
    (title, slug, description, organization, amount, level, field_of_study, eligibility_criteria, application_deadline, posted_by, is_active, views_count)
VALUES
    ('African Leadership University Scholarship', 'alu-scholarship', 'Full scholarship for exceptional African students', 'African Leadership University', 50000, 'undergraduate', 'Any', 'Top 5% of high school class, English proficiency', '2024-06-30', 5, TRUE, 3421),
    ('Google Scholarships for Africa', 'google-africa-scholarship', 'Technology scholarships for African innovators', 'Google', 15000, 'undergraduate', 'Computer Science', 'Demonstrated interest in technology, Academic excellence', '2024-07-15', 5, TRUE, 2156),
    ('World Bank Youth Development', 'world-bank-youth', 'Support for developing world youth', 'World Bank', 20000, 'graduate', 'Economics, Development', 'Graduate studies in development fields', '2024-08-31', 5, TRUE, 1842),
    ('Mastercard Foundation Scholarship', 'mastercard-foundation', 'Education for talented but disadvantaged students', 'Mastercard Foundation', 30000, 'undergraduate', 'Any', 'Financial need and academic merit', '2024-09-30', 1, TRUE, 2634),
    ('Commonwealth Scholarships', 'commonwealth-scholarship', 'Study in Commonwealth countries', 'Commonwealth Secretariat', 25000, 'postgraduate', 'Any', 'Citizenship in Commonwealth country', '2024-10-31', 1, TRUE, 1523);

-- ============================================
-- INSERT JOBS
-- ============================================
INSERT INTO jobs
    (title, slug, description, requirements, benefits, employer_id, category_id, salary_min, salary_max, location, job_type, experience_level, status, is_featured, published_at, deadline)
VALUES
    ('Senior Software Engineer', 'senior-software-engineer', 'We are looking for an experienced software engineer...', 'Min 5 years experience, PHP, Laravel, MySQL', 'Health insurance, Competitive salary, Remote options', 4, 1, 1500000, 2500000, 'Kigali, Rwanda', 'full-time', 'senior', 'open', TRUE, NOW(), DATE_ADD(NOW(), INTERVAL
30 DAY)),
('Junior Web Developer', 'junior-web-developer', 'Fresh graduate web developer wanted', 'HTML, CSS, JavaScript, React basics', 'Training provided, Mentorship, Career growth', 4, 1, 400000, 600000, 'Kigali, Rwanda', 'full-time', 'entry', 'open', FALSE, NOW
(), DATE_ADD
(NOW
(), INTERVAL 25 DAY)),
('Business Development Manager', 'business-dev-manager', 'Drive business growth in East Africa region', 'Sales experience, Market knowledge, Communication skills', 'Commission, Car allowance, Travel budget', 3, 2, 1000000, 1800000, 'Kigali, Rwanda', 'full-time', 'mid', 'open', TRUE, NOW
(), DATE_ADD
(NOW
(), INTERVAL 20 DAY)),
('Marketing Manager', 'marketing-manager', 'Lead marketing initiatives for growing startup', 'Digital marketing, Social media, Campaign management', 'Competitive package, Flexible hours, Learning budget', 3, 2, 800000, 1200000, 'Kigali, Rwanda', 'full-time', 'mid', 'open', FALSE, NOW
(), DATE_ADD
(NOW
(), INTERVAL 28 DAY)),
('Data Analyst', 'data-analyst', 'Analyze and provide insights from business data', 'SQL, Python, Excel, Analytics', 'Remote work, Professional development', 4, 1, 900000, 1400000, 'Kigali, Rwanda', 'full-time', 'mid', 'open', FALSE, NOW
(), DATE_ADD
(NOW
(), INTERVAL 15 DAY)),
('Social Media Executive', 'social-media-executive', 'Manage social media presence for brands', 'Social media expertise, Content creation, Engagement', 'Training, Bonuses, Growth opportunities', 3, 2, 300000, 500000, 'Kigali, Rwanda', 'full-time', 'entry', 'open', FALSE, NOW
(), DATE_ADD
(NOW
(), INTERVAL 18 DAY)),
('Agricultural Consultant', 'agricultural-consultant', 'Advise farmers on modern farming techniques', 'Agriculture background, Agronomic knowledge', 'Travel allowance, Research budget', 1, 6, 600000, 1000000, 'Multiple Locations', 'part-time', 'mid', 'open', FALSE, NOW
(), DATE_ADD
(NOW
(), INTERVAL 22 DAY)),
('Content Writer', 'content-writer', 'Write engaging articles and blog posts', 'Writing skills, SEO knowledge, Creativity', 'Flexible schedule, Competitive rates', 2, 1, 200000, 400000, 'Remote', 'contract', 'entry', 'open', FALSE, NOW
(), DATE_ADD
(NOW
(), INTERVAL 12 DAY));

-- ============================================
-- INSERT BUSINESSES
-- ============================================
INSERT INTO businesses
    (name, slug, description, owner_id, email, phone, website, category_id, location, subscription_level, verification_status, verified_by, is_featured, is_active, views, rating)
VALUES
    ('TechHub Solutions Rwanda', 'techhub-solutions', 'Leading IT solutions provider in Rwanda', 3, 'info@techhubrw.com', '+250788345678', 'https://techhubrw.com', 1, 'Kigali, Rwanda', 'verified', 'verified', 1, TRUE, TRUE, 3421, 4.8),
    ('Green Farming Initiative', 'green-farming', 'Organic farming supplies and consulting', 1, 'info@greenfarming.rw', '+250788567890', 'https://greenfarming.rw', 6, 'Muhanga, Rwanda', 'premium', 'verified', 1, TRUE, TRUE, 2156, 4.6),
    ('Business Growth Academy', 'business-academy', 'Professional business training center', 2, 'info@businessacademy.rw', '+250788678901', 'https://businessacademy.rw', 2, 'Kigali, Rwanda', 'verified', 'verified', 1, TRUE, TRUE, 1842, 4.9),
    ('Digital Marketing Plus', 'digital-marketing-plus', 'Digital marketing and SEO services', 8, 'contact@dmpls.rw', '+250788890123', 'https://dmpls.rw', 2, 'Gisenyi, Rwanda', 'premium', 'verified', 1, TRUE, TRUE, 956, 4.5),
    ('Cloud Computing Services Ltd', 'cloud-computing', 'Cloud infrastructure and management', 9, 'support@cloudserv.rw', '+250789901234', 'https://cloudserv.rw', 1, 'Kigali, Rwanda', 'verified', 'verified', 1, FALSE, TRUE, 1523, 4.7),
    ('Health and Wellness Center', 'wellness-center', 'Holistic health and fitness services', 2, 'wellness@center.rw', '+250788234567', NULL, 5, 'Kigali, Rwanda', 'free', 'pending', NULL, FALSE, TRUE, 412, 4.3);

-- ============================================
-- INSERT BUSINESS SERVICES
-- ============================================
INSERT INTO business_services
    (business_id, name, description, price, service_category, is_active)
VALUES
    (1, 'Web Development', 'Custom web application development', 5000000, 'IT Services', TRUE),
    (1, 'Mobile App Development', 'iOS and Android development', 8000000, 'IT Services', TRUE),
    (1, 'IT Consulting', 'Strategic IT consulting services', 2000000, 'Consulting', TRUE),
    (2, 'Seed Supply', 'Quality organic seeds', 50000, 'Supplies', TRUE),
    (2, 'Farmer Training', 'Modern farming techniques workshop', 500000, 'Training', TRUE),
    (3, 'Entrepreneurship Course', 'Complete business startup course', 1500000, 'Training', TRUE),
    (3, 'Mentorship Program', '1-on-1 business mentorship', 300000, 'Consulting', TRUE),
    (4, 'SEO Optimization', 'Search engine optimization services', 2000000, 'Digital Marketing', TRUE),
    (4, 'Social Media Management', 'Full social media management', 1500000, 'Digital Marketing', TRUE),
    (5, 'Cloud Server Setup', 'Cloud infrastructure setup', 3000000, 'IT Services', TRUE);

-- ============================================
-- INSERT COMMENTS
-- ============================================
INSERT INTO comments
    (post_id, user_id, content, status)
VALUES
    (1, 7, 'Great article! Very informative about tech growth in Rwanda.', 'approved'),
    (1, 8, 'I agree, the technology sector is growing rapidly.', 'approved'),
    (1, 9, 'Would love to see more data about this.', 'approved'),
    (2, 3, 'Excellent compilation of tech companies!', 'approved'),
    (2, 7, 'Missing some companies in Gitarama region.', 'approved'),
    (3, 6, 'This guide is very comprehensive and helpful.', 'approved'),
    (3, 4, 'Great tips for new entrepreneurs.', 'approved'),
    (4, 8, 'The agricultural innovations section was very useful.', 'approved'),
    (5, 9, 'Thank you for sharing scholarship opportunities!', 'approved'),
    (5, 7, 'Applied to three scholarships already.', 'approved');

-- ============================================
-- INSERT REACTIONS
-- ============================================
INSERT INTO reactions
    (post_id, user_id, reaction_type)
VALUES
    (1, 3, 'love'),
    (1, 4, 'like'),
    (1, 7, 'like'),
    (1, 8, 'wow'),
    (2, 3, 'like'),
    (2, 6, 'like'),
    (2, 9, 'like'),
    (3, 4, 'love'),
    (3, 7, 'like'),
    (4, 8, 'like'),
    (5, 3, 'love'),
    (5, 7, 'like'),
    (5, 9, 'love'),
    (6, 4, 'like'),
    (7, 6, 'like');

-- ============================================
-- INSERT BOOKMARKS
-- ============================================
INSERT INTO bookmarks
    (user_id, post_id, bookmark_type, notes)
VALUES
    (7, 1, 'post', 'Read later'),
    (7, 3, 'post', 'Reference for my business'),
    (7, 5, 'post', 'Scholarship opportunities'),
    (8, 2, 'post', 'Tech companies research'),
    (8, 4, 'post', 'Agricultural techniques'),
    (3, 1, 'post', 'Industry insight'),
    (9, 3, 'job', 'Interesting companies'),
    (4, 6, 'job', 'Potential employee'),
    (4, 7, 'job', 'Can forward to candidates');

-- ============================================
-- INSERT JOB APPLICATIONS
-- ============================================
INSERT INTO job_applications
    (job_id, applicant_id, cover_letter, status)
VALUES
    (1, 9, 'I am a senior developer with 7 years of experience...', 'shortlisted'),
    (1, 7, 'I have worked with PHP and Laravel for 5 years...', 'pending'),
    (2, 7, 'Fresh graduate ready to learn and grow...', 'accepted'),
    (3, 8, 'I have 4 years of business development experience...', 'reviewed'),
    (4, 6, 'Marketing professional with strong digital background...', 'pending'),
    (5, 9, 'Data analyst with SQL and Python expertise...', 'shortlisted'),
    (6, 8, 'Social media expert with 3 years experience...', 'pending'),
    (7, 3, 'Agronomist with 10 years field experience...', 'accepted');

-- ============================================
-- INSERT PAYMENTS
-- ============================================
INSERT INTO payments
    (user_id, payment_type, amount, status, payment_method, transaction_id, description, subscription_period_months)
VALUES
    (3, 'subscription', 50000, 'completed', 'mobile_money', 'TXN123456789', 'Business Premium Subscription', 12),
    (8, 'featured_business', 30000, 'completed', 'mobile_money', 'TXN123456790', 'Feature Business Listing', 1),
    (4, 'job_posting', 15000, 'completed', 'mobile_money', 'TXN123456791', 'Job Post Featured', 1),
    (2, 'subscription', 100000, 'completed', 'mobile_money', 'TXN123456792', 'Platform Premium Subscription', 12),
    (1, 'featured_posting', 20000, 'completed', 'mobile_money', 'TXN123456793', 'Featured Article', 1),
    (6, 'subscription', 30000, 'completed', 'mobile_money', 'TXN123456794', 'Content Creator Subscription', 6),
    (9, 'job_posting', 10000, 'completed', 'mobile_money', 'TXN123456795', 'Standard Job Posting', 1);

-- ============================================
-- INSERT NOTIFICATIONS (sample)
-- ============================================
INSERT INTO notifications
    (user_id, title, message, notification_type, is_read)
VALUES
    (7, 'New Comment on Your Bookmark', 'Marie commented on the article you bookmarked', 'comment', FALSE),
    (3, 'Job Application Received', 'You have received 3 new job applications', 'application', FALSE),
    (4, 'New Job Match', 'A job matching your profile is now available', 'job_alert', FALSE),
    (8, 'Business Verified', 'Your business listing has been verified', 'announcement', TRUE),
    (9, 'Application Status Update', 'Your application status has been updated to shortlisted', 'application', FALSE);

-- ============================================
-- INSERT ACTIVITY LOGS (sample)
-- ============================================
INSERT INTO activity_logs
    (user_id, action, module, description, ip_address)
VALUES
    (3, 'login', 'auth', 'User logged in', '192.168.1.1'),
    (3, 'create_job', 'jobs', 'Posted new job listing', '192.168.1.1'),
    (7, 'create_post', 'comments', 'Added new comment', '192.168.1.2'),
    (6, 'publish_post', 'posts', 'Published article', '192.168.1.3'),
    (4, 'bookmark_post', 'bookmarks', 'Bookmarked article', '192.168.1.4'),
    (8, 'update_business', 'businesses', 'Updated business profile', '192.168.1.5');
