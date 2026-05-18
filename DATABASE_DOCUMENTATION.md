# InfoHub Database Schema Documentation

## Overview
Complete MySQL database schema for the InfoHub platform - a comprehensive information and opportunity hub for Rwanda connecting communities through news, jobs, businesses, education, and services.

## Database Summary
- **Database Name:** infohub
- **Total Tables:** 23
- **Character Set:** utf8mb4 (supports all Unicode characters)
- **Engine:** InnoDB (supports foreign keys and transactions)

---

## Table Structure

### 1. **roles** - User Roles and Permissions
Controls access levels and permissions for different user types.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Role identifier |
| name | VARCHAR(50) | UNIQUE | Role name (Super Admin, Admin, Editor, etc.) |
| description | TEXT | - | Role description |
| permissions | JSON | - | Array of permissions |
| created_at | TIMESTAMP | - | Creation timestamp |

**Default Roles:**
- Super Admin - Full system access
- Admin - Administrator functions
- Editor - Content publishing
- Writer - Article creation
- Business Owner - Business management
- Employer - Job posting
- Registered User - Basic interactions

---

### 2. **users** - User Accounts
Central user management table with authentication and profile data.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | User ID |
| first_name | VARCHAR(100) | - | User's first name |
| last_name | VARCHAR(100) | - | User's last name |
| email | VARCHAR(255) | UNIQUE | User email |
| password_hash | VARCHAR(255) | - | Bcrypt password hash |
| phone | VARCHAR(20) | - | Contact number |
| avatar | VARCHAR(255) | - | Profile picture URL |
| bio | TEXT | - | User biography |
| location | VARCHAR(255) | - | User location |
| role_id | INT UNSIGNED | FK → roles | User's role |
| is_active | BOOLEAN | - | Account status |
| email_verified | BOOLEAN | - | Email verification status |
| email_verified_at | TIMESTAMP | - | Email verification date |
| last_login_at | TIMESTAMP | - | Last login timestamp |
| social_links | JSON | - | Social media profiles |
| preferences | JSON | - | User preferences |
| created_at | TIMESTAMP | - | Account creation date |
| updated_at | TIMESTAMP | - | Last update date |

**Indexes:**
- FULLTEXT on first_name, last_name for name search
- INDEX on email, role_id, created_at

---

### 3. **categories** - Content Categories
Hierarchical category system for organizing content, jobs, businesses, etc.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Category ID |
| name | VARCHAR(100) | - | Category name |
| slug | VARCHAR(100) | UNIQUE | URL-friendly name |
| description | TEXT | - | Category description |
| icon | VARCHAR(50) | - | Icon class (Font Awesome) |
| image | VARCHAR(255) | - | Category image URL |
| parent_id | INT UNSIGNED | FK → categories | Parent category for hierarchies |
| is_active | BOOLEAN | - | Availability status |
| sort_order | INT | - | Display order |
| created_at | TIMESTAMP | - | Creation date |
| updated_at | TIMESTAMP | - | Last update |

**Example Categories:**
- Technology
- Business
- Education
- Careers
- Health
- Agriculture
- Finance
- Culture

---

### 4. **posts** - News Articles and Content
Main content publication table.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Post ID |
| title | VARCHAR(255) | - | Article title |
| slug | VARCHAR(255) | UNIQUE | URL slug |
| excerpt | VARCHAR(500) | - | Short summary |
| content | LONGTEXT | - | Full article content |
| author_id | INT UNSIGNED | FK → users | Content author |
| category_id | INT UNSIGNED | FK → categories | Content category |
| featured_image | VARCHAR(255) | - | Hero image URL |
| status | ENUM | - | draft/published/archived/scheduled |
| type | ENUM | - | article/news/announcement |
| is_featured | BOOLEAN | - | Homepage feature flag |
| is_trending | BOOLEAN | - | Trending content flag |
| views_count | INT | - | Article views count |
| seo_title | VARCHAR(255) | - | SEO page title |
| seo_description | VARCHAR(500) | - | Meta description |
| seo_keywords | VARCHAR(255) | - | Keywords for SEO |
| published_by | INT UNSIGNED | FK → users | Approving editor |
| scheduled_at | TIMESTAMP | - | Publication schedule |
| created_at | TIMESTAMP | - | Creation date |
| updated_at | TIMESTAMP | - | Last edit date |
| published_at | TIMESTAMP | - | Publication date |

**Indexes:**
- FULLTEXT on title, excerpt, content for search
- INDEX on slug, status, featured, author_id, created_at

---

### 5. **announcements** - Platform Announcements
System-wide announcements and notifications.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Announcement ID |
| title | VARCHAR(255) | - | Announcement title |
| content | TEXT | - | Announcement content |
| author_id | INT UNSIGNED | FK → users | Creator |
| priority | ENUM | - | low/medium/high/critical |
| target_audience | ENUM | - | all/users/businesses/employers |
| is_published | BOOLEAN | - | Publication status |
| published_at | TIMESTAMP | - | When published |
| expires_at | TIMESTAMP | - | Expiration date |
| created_at | TIMESTAMP | - | Creation date |
| updated_at | TIMESTAMP | - | Last update |

---

### 6. **events** - Events and Meetings
Event management and promotion.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Event ID |
| title | VARCHAR(255) | - | Event name |
| slug | VARCHAR(255) | UNIQUE | URL slug |
| description | LONGTEXT | - | Full event details |
| organizer_id | INT UNSIGNED | FK → users | Event organizer |
| category_id | INT UNSIGNED | FK → categories | Event category |
| location | VARCHAR(255) | - | Physical location |
| image | VARCHAR(255) | - | Event image |
| start_date | DATETIME | - | Event start |
| end_date | DATETIME | - | Event end |
| is_online | BOOLEAN | - | Virtual event flag |
| online_link | VARCHAR(255) | - | Meeting link (Zoom, etc.) |
| max_attendees | INT | - | Capacity |
| attendee_count | INT | - | Current registrations |
| status | ENUM | - | draft/published/ongoing/completed/cancelled |
| created_at | TIMESTAMP | - | Creation date |
| updated_at | TIMESTAMP | - | Last update |

---

### 7. **educational_resources** - Learning Materials
Courses, tutorials, guides, webinars.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Resource ID |
| title | VARCHAR(255) | - | Resource title |
| slug | VARCHAR(255) | UNIQUE | URL slug |
| description | TEXT | - | Short description |
| content | LONGTEXT | - | Full content |
| resource_type | ENUM | - | course/tutorial/guide/webinar/ebook/video |
| author_id | INT UNSIGNED | FK → users | Creator |
| category_id | INT UNSIGNED | FK → categories | Subject category |
| level | ENUM | - | beginner/intermediate/advanced |
| duration_hours | INT | - | Duration in hours |
| thumbnail | VARCHAR(255) | - | Course thumbnail |
| file_url | VARCHAR(255) | - | Download/access link |
| is_published | BOOLEAN | - | Publication status |
| views_count | INT | - | View count |
| downloads_count | INT | - | Download count |
| created_at | TIMESTAMP | - | Creation date |
| updated_at | TIMESTAMP | - | Last update |

---

### 8. **scholarships** - Scholarship Opportunities
Scholarship database and opportunity listings.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Scholarship ID |
| title | VARCHAR(255) | - | Scholarship name |
| slug | VARCHAR(255) | UNIQUE | URL slug |
| description | LONGTEXT | - | Full details |
| organization | VARCHAR(255) | - | Offering organization |
| amount | DECIMAL(12,2) | - | Award amount |
| currency | VARCHAR(3) | - | Currency code |
| level | ENUM | - | secondary/undergraduate/graduate/vocational |
| field_of_study | VARCHAR(255) | - | Study field |
| eligibility_criteria | TEXT | - | Requirements |
| application_deadline | DATE | - | Deadline |
| posted_by | INT UNSIGNED | FK → users | Posted by user |
| application_url | VARCHAR(255) | - | External link |
| is_active | BOOLEAN | - | Active status |
| views_count | INT | - | View count |
| created_at | TIMESTAMP | - | Creation date |
| updated_at | TIMESTAMP | - | Last update |

---

### 9. **jobs** - Job Listings
Employment opportunities.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Job ID |
| title | VARCHAR(255) | - | Job title |
| slug | VARCHAR(255) | UNIQUE | URL slug |
| description | LONGTEXT | - | Full job description |
| requirements | LONGTEXT | - | Required qualifications |
| benefits | TEXT | - | Job benefits |
| employer_id | INT UNSIGNED | FK → users | Hiring employer |
| category_id | INT UNSIGNED | FK → categories | Industry category |
| salary_min | DECIMAL(10,2) | - | Minimum salary |
| salary_max | DECIMAL(10,2) | - | Maximum salary |
| currency | VARCHAR(3) | - | Salary currency |
| location | VARCHAR(255) | - | Job location |
| is_remote | BOOLEAN | - | Remote work flag |
| job_type | ENUM | - | full-time/part-time/contract/temporary/internship |
| experience_level | ENUM | - | entry/mid/senior/executive |
| status | ENUM | - | open/closed/on-hold/filled |
| is_featured | BOOLEAN | - | Featured listing flag |
| application_count | INT | - | Applications received |
| views_count | INT | - | Job views |
| published_at | TIMESTAMP | - | Publication date |
| deadline | TIMESTAMP | - | Application deadline |
| created_at | TIMESTAMP | - | Creation date |
| updated_at | TIMESTAMP | - | Last update |

**Indexes:**
- FULLTEXT on title, description, requirements

---

### 10. **job_applications** - Job Applications
Tracks applications to job postings.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Application ID |
| job_id | INT UNSIGNED | FK → jobs | Applied job |
| applicant_id | INT UNSIGNED | FK → users | Applicant |
| cv_path | VARCHAR(255) | - | CV file path |
| cover_letter | LONGTEXT | - | Motivation letter |
| status | ENUM | - | pending/reviewed/shortlisted/rejected/accepted/withdrawn |
| rating | INT | - | Employer rating |
| notes | TEXT | - | Employer notes |
| rejection_reason | TEXT | - | Rejection explanation |
| applied_at | TIMESTAMP | - | Application date |
| updated_at | TIMESTAMP | - | Last status update |

**Unique Constraint:** One application per user per job

---

### 11. **businesses** - Business Directory
Business and service provider listings.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Business ID |
| name | VARCHAR(255) | - | Business name |
| slug | VARCHAR(255) | UNIQUE | URL slug |
| description | LONGTEXT | - | Business description |
| owner_id | INT UNSIGNED | FK → users | Business owner |
| email | VARCHAR(255) | - | Contact email |
| phone | VARCHAR(20) | - | Contact phone |
| website | VARCHAR(255) | - | Website URL |
| logo | VARCHAR(255) | - | Logo URL |
| cover_image | VARCHAR(255) | - | Cover image URL |
| category_id | INT UNSIGNED | FK → categories | Business category |
| location | VARCHAR(255) | - | Physical location |
| coordinates | JSON | - | GPS coordinates |
| subscription_level | ENUM | - | free/premium/verified |
| verification_status | ENUM | - | pending/verified/rejected |
| verified_at | TIMESTAMP | - | Verification date |
| verified_by | INT UNSIGNED | FK → users | Verifying admin |
| is_featured | BOOLEAN | - | Featured flag |
| is_active | BOOLEAN | - | Active status |
| views | INT | - | Profile views |
| rating | DECIMAL(3,2) | - | Average rating |
| rating_count | INT | - | Number of ratings |
| business_hours | JSON | - | Operating hours |
| social_links | JSON | - | Social media |
| created_at | TIMESTAMP | - | Creation date |
| updated_at | TIMESTAMP | - | Last update |

**Indexes:**
- FULLTEXT on name, description

---

### 12. **business_services** - Business Services
Services offered by businesses.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Service ID |
| business_id | INT UNSIGNED | FK → businesses | Business |
| name | VARCHAR(255) | - | Service name |
| description | TEXT | - | Service description |
| price | DECIMAL(10,2) | - | Service price |
| currency | VARCHAR(3) | - | Currency |
| service_category | VARCHAR(100) | - | Service type |
| is_active | BOOLEAN | - | Active status |
| created_at | TIMESTAMP | - | Creation date |
| updated_at | TIMESTAMP | - | Last update |

---

### 13. **business_verification_documents** - Business Verification
Documents for business verification process.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Document ID |
| business_id | INT UNSIGNED | FK → businesses | Business |
| document_type | VARCHAR(50) | - | Type of document |
| file_path | VARCHAR(255) | - | File location |
| status | ENUM | - | pending/verified/rejected |
| verified_by | INT UNSIGNED | FK → users | Verifying admin |
| verified_at | TIMESTAMP | - | Verification date |
| notes | TEXT | - | Verification notes |
| created_at | TIMESTAMP | - | Upload date |
| updated_at | TIMESTAMP | - | Last update |

---

### 14. **comments** - Post Comments
Comments on posts and articles with threading support.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Comment ID |
| post_id | INT UNSIGNED | FK → posts | Post being commented |
| user_id | INT UNSIGNED | FK → users | Comment author |
| parent_id | INT UNSIGNED | FK → comments | Parent comment (threading) |
| content | TEXT | - | Comment text |
| status | ENUM | - | pending/approved/spam/trash |
| is_edited | BOOLEAN | - | Edit flag |
| edited_at | TIMESTAMP | - | Edit timestamp |
| created_at | TIMESTAMP | - | Creation date |
| updated_at | TIMESTAMP | - | Last update |

---

### 15. **reactions** - Post Reactions
Like/reaction system for posts.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Reaction ID |
| post_id | INT UNSIGNED | FK → posts | Post reacted to |
| user_id | INT UNSIGNED | FK → users | Reacting user |
| reaction_type | ENUM | - | like/love/haha/wow/sad/angry |
| created_at | TIMESTAMP | - | Reaction date |

**Unique Constraint:** One reaction per user per post

---

### 16. **bookmarks** - Saved Content
Users can bookmark posts, jobs, businesses, etc.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Bookmark ID |
| user_id | INT UNSIGNED | FK → users | User |
| post_id | INT UNSIGNED | FK → posts | Bookmarked post |
| job_id | INT UNSIGNED | FK → jobs | Bookmarked job |
| business_id | INT UNSIGNED | FK → businesses | Bookmarked business |
| bookmark_type | ENUM | - | post/job/business/event/scholarship |
| notes | TEXT | - | User notes |
| created_at | TIMESTAMP | - | Creation date |

---

### 17. **notifications** - Notifications
User notifications system.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Notification ID |
| user_id | INT UNSIGNED | FK → users | Recipient |
| title | VARCHAR(255) | - | Notification title |
| message | TEXT | - | Message body |
| notification_type | ENUM | - | comment/application/message/job_alert/announcement/system |
| related_id | INT UNSIGNED | - | Related entity ID |
| related_type | VARCHAR(50) | - | Type of entity |
| is_read | BOOLEAN | - | Read status |
| read_at | TIMESTAMP | - | Read timestamp |
| created_at | TIMESTAMP | - | Creation date |

---

### 18. **abuse_reports** - Abuse Reporting
User-submitted abuse reports.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Report ID |
| reporter_id | INT UNSIGNED | FK → users | Report author |
| reported_item_type | ENUM | - | post/comment/business/job/user |
| reported_item_id | INT UNSIGNED | - | Item being reported |
| reason | ENUM | - | spam/abuse/harassment/inappropriate/illegal/other |
| description | TEXT | - | Report details |
| status | ENUM | - | pending/investigating/resolved/dismissed |
| resolved_by | INT UNSIGNED | FK → users | Resolving admin |
| resolution_notes | TEXT | - | Resolution details |
| created_at | TIMESTAMP | - | Report date |
| resolved_at | TIMESTAMP | - | Resolution date |

---

### 19. **payments** - Transactions
Payment and subscription management.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Payment ID |
| user_id | INT UNSIGNED | FK → users | Paying user |
| payment_type | ENUM | - | subscription/featured_posting/featured_business/job_posting/verification/donation/other |
| amount | DECIMAL(10,2) | - | Amount paid |
| currency | VARCHAR(3) | - | Currency code |
| status | ENUM | - | pending/completed/failed/refunded/cancelled |
| payment_method | ENUM | - | bank_transfer/mobile_money/credit_card/paypal/stripe |
| transaction_id | VARCHAR(255) | UNIQUE | Payment gateway transaction ID |
| description | TEXT | - | Payment description |
| related_entity_type | VARCHAR(50) | - | Type of entity (job, business, etc.) |
| related_entity_id | INT UNSIGNED | - | Entity ID |
| subscription_period_months | INT | - | Subscription duration |
| expires_at | TIMESTAMP | - | Subscription expiration |
| created_at | TIMESTAMP | - | Creation date |
| completed_at | TIMESTAMP | - | Completion date |

---

### 20. **activity_logs** - User Activity Logs
Tracks user actions for analytics and audit.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Log ID |
| user_id | INT UNSIGNED | FK → users | Acting user |
| action | VARCHAR(100) | - | Action performed |
| module | VARCHAR(50) | - | Module/feature |
| description | TEXT | - | Activity details |
| ip_address | VARCHAR(45) | - | User IP |
| user_agent | VARCHAR(255) | - | Browser/device info |
| created_at | TIMESTAMP | - | Timestamp |

---

### 21. **auth_logs** - Authentication Logs
Tracks login attempts and authentication.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Log ID |
| email | VARCHAR(255) | - | Email attempted |
| action | VARCHAR(50) | - | login/logout/password_reset/2fa |
| success | BOOLEAN | - | Success status |
| failure_reason | VARCHAR(255) | - | Failure explanation |
| ip_address | VARCHAR(45) | - | User IP |
| user_agent | VARCHAR(255) | - | Browser/device |
| created_at | TIMESTAMP | - | Timestamp |

---

### 22. **admin_logs** - Admin Activity Logs
Tracks administrative actions.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Log ID |
| admin_id | INT UNSIGNED | FK → users | Admin user |
| action | VARCHAR(100) | - | Action taken |
| target_type | VARCHAR(50) | - | Type of target |
| target_id | INT UNSIGNED | - | Target ID |
| changes | JSON | - | What changed |
| ip_address | VARCHAR(45) | - | Admin IP |
| created_at | TIMESTAMP | - | Timestamp |

---

### 23. **error_logs** - System Error Logs
Application error tracking.

| Field | Type | Key | Description |
|-------|------|-----|-------------|
| id | INT UNSIGNED | PK | Log ID |
| error_type | VARCHAR(100) | - | Error category |
| error_message | TEXT | - | Error message |
| error_file | VARCHAR(255) | - | File path |
| error_line | INT | - | Line number |
| stack_trace | LONGTEXT | - | Full stack trace |
| context | JSON | - | Error context |
| created_at | TIMESTAMP | - | Timestamp |

---

## Sample Data

The database includes 100+ sample records:
- **10 Users** - Various roles and permissions
- **8 Posts** - News and articles across categories
- **8 Jobs** - Diverse job listings
- **6 Businesses** - Business directory entries
- **5 Events** - Scheduled events
- **5 Scholarships** - Scholarship opportunities
- **5 Educational Resources** - Courses and tutorials
- **And more** - Comments, reactions, bookmarks, applications, logs

---

## Key Features

### 1. **Foreign Key Relationships**
All tables maintain referential integrity through foreign keys.

### 2. **Full-Text Search**
FULLTEXT indexes on:
- users (first_name, last_name)
- posts (title, excerpt, content)
- jobs (title, description, requirements)
- businesses (name, description)

### 3. **Soft Deletes Ready**
Tables use `is_active`/`status` flags instead of hard deletes for audit trail.

### 4. **JSON Fields**
Flexible data storage:
- `users.social_links` - Social media profiles
- `users.preferences` - User settings
- `businesses.business_hours` - Operating hours
- `businesses.social_links` - Business social media
- `roles.permissions` - Role permissions array
- `admin_logs.changes` - Change tracking

### 5. **Timestamps**
All tables include:
- `created_at` - Record creation
- `updated_at` - Last modification

### 6. **Audit Trail**
- `activity_logs` - User actions
- `auth_logs` - Login attempts
- `admin_logs` - Administrative actions
- `error_logs` - System errors

---

## Database Backup & Recovery

### Backup the Database
```bash
mysqldump -u root infohub > infohub_backup.sql
```

### Restore the Database
```bash
mysql -u root infohub < infohub_backup.sql
```

---

## API Integration Points

### Key Entities for REST API:
- `/api/posts` - Articles
- `/api/jobs` - Job listings
- `/api/businesses` - Business directory
- `/api/users` - User profiles
- `/api/events` - Events
- `/api/scholarships` - Scholarships
- `/api/categories` - Content categories
- `/api/applications` - Job applications
- `/api/comments` - Post comments
- `/api/notifications` - Notifications

---

## Performance Considerations

### Optimized Indexes:
- Search queries (FULLTEXT)
- Filtering (status, is_active, is_featured)
- Relationships (FK queries)
- Time-based queries (created_at, deadline)

### Recommendations:
- Add pagination (LIMIT/OFFSET) for large result sets
- Use connection pooling for high traffic
- Regular index maintenance: `ANALYZE TABLE`
- Monitor slow queries: `mysql > SET GLOBAL slow_query_log = 'ON';`

---

## Security Best Practices

1. **User Passwords** - Use bcrypt hashing ($2y$10$ format)
2. **Email Verification** - Use email_verified_at timestamps
3. **Admin Actions** - All tracked in admin_logs
4. **Data Privacy** - Implement role-based access control
5. **SQL Injection** - Use prepared statements in application
6. **Rate Limiting** - Track auth_logs for suspicious activity

---

## Connection Details

- **Host:** localhost
- **User:** root
- **Database:** infohub
- **Port:** 3306
- **Charset:** utf8mb4

---

## Support & Maintenance

For schema updates or additions, follow the patterns established:
- Use consistent naming conventions
- Include proper indexes
- Add timestamps to all tables
- Document foreign key relationships
- Maintain referential integrity
