# InfoHub Database - Developer's Quick Reference

## 📊 Database Structure at a Glance

### Core Tables (7)
| Table | Purpose | Records |
|-------|---------|---------|
| roles | User permissions | 7 roles |
| users | User accounts | 10 users |
| categories | Content organization | 8 categories |
| posts | News/articles | 8 posts |
| jobs | Job listings | 8 jobs |
| businesses | Business directory | 6 businesses |
| payments | Transactions | 7 payments |

### Content Tables (4)
- announcements - Platform announcements
- events - Events/meetings
- educational_resources - Courses/tutorials
- scholarships - Scholarship opportunities

### Engagement Tables (3)
- comments - Post comments
- reactions - Likes/reactions
- bookmarks - Saved content

### Business Tables (2)
- business_services - Services per business
- business_verification_documents - Verification files

### Management Tables (4)
- job_applications - Job applications
- notifications - User notifications
- abuse_reports - Content moderation
- activity_logs - User activity

### Logging Tables (3)
- auth_logs - Login attempts
- admin_logs - Admin actions
- error_logs - System errors

---

## 🔑 Key Relationships

```
User (1) ──────────► (Many) Posts
User (1) ──────────► (Many) Comments
User (1) ──────────► (Many) Reactions
User (1) ──────────► (Many) Bookmarks

Job (1) ──────────► (Many) Applications
Job (1) ──────────► (Many) Bookmarks

Business (1) ──────────► (Many) Services
Business (1) ──────────► (Many) Verification Documents

Category (1) ──────────► (Many) Posts
Category (1) ──────────► (Many) Jobs
Category (1) ──────────► (Many) Businesses
```

---

## 🔐 User Roles & Permissions

### Role Levels (by access)
1. **Super Admin** - Full system control
2. **Admin** - User & content management
3. **Editor** - Publish and manage content
4. **Writer** - Create articles
5. **Business Owner** - Manage business listing
6. **Employer** - Post and manage jobs
7. **Registered User** - Basic platform access

---

## 📝 Common Queries

### Get Active Users
```sql
SELECT * FROM users WHERE is_active = TRUE;
```

### Get Published Posts with Comments
```sql
SELECT p.*, u.first_name, COUNT(c.id) as comment_count
FROM posts p
JOIN users u ON p.author_id = u.id
LEFT JOIN comments c ON p.id = c.post_id
WHERE p.status = 'published'
GROUP BY p.id;
```

### Get Job Listings with Applications
```sql
SELECT j.*, u.first_name as employer, COUNT(a.id) as applications
FROM jobs j
JOIN users u ON j.employer_id = u.id
LEFT JOIN job_applications a ON j.id = a.job_id
WHERE j.status = 'open'
GROUP BY j.id;
```

### Get Verified Businesses
```sql
SELECT b.*, u.first_name as owner
FROM businesses b
JOIN users u ON b.owner_id = u.id
WHERE b.verification_status = 'verified'
AND b.is_active = TRUE
ORDER BY b.rating DESC;
```

### Get Recent Activities
```sql
SELECT u.first_name, a.action, a.module, a.created_at
FROM activity_logs a
JOIN users u ON a.user_id = u.id
ORDER BY a.created_at DESC
LIMIT 50;
```

### Count Posts by Category
```sql
SELECT c.name, COUNT(p.id) as post_count
FROM categories c
LEFT JOIN posts p ON c.id = p.category_id
GROUP BY c.id
ORDER BY post_count DESC;
```

### Get Top Trending Posts
```sql
SELECT * FROM posts
WHERE is_trending = TRUE
AND status = 'published'
ORDER BY views_count DESC
LIMIT 10;
```

### Get Job Applications for Employer
```sql
SELECT a.*, u.first_name, u.last_name, j.title as job_title
FROM job_applications a
JOIN users u ON a.applicant_id = u.id
JOIN jobs j ON a.job_id = j.id
WHERE j.employer_id = ?
ORDER BY a.applied_at DESC;
```

---

## 🔍 Search Queries (FULLTEXT)

### Search Posts
```sql
SELECT * FROM posts
WHERE MATCH(title, excerpt, content) AGAINST('technology' IN BOOLEAN MODE)
AND status = 'published';
```

### Search Jobs
```sql
SELECT * FROM jobs
WHERE MATCH(title, description, requirements) AGAINST('developer' IN BOOLEAN MODE)
AND status = 'open';
```

### Search Businesses
```sql
SELECT * FROM businesses
WHERE MATCH(name, description) AGAINST('restaurant' IN BOOLEAN MODE)
AND verification_status = 'verified';
```

### Search Users by Name
```sql
SELECT * FROM users
WHERE MATCH(first_name, last_name) AGAINST('jean' IN BOOLEAN MODE);
```

---

## 💰 Payment Queries

### Get All Successful Payments
```sql
SELECT * FROM payments
WHERE status = 'completed'
ORDER BY created_at DESC;
```

### Get Subscription Revenue
```sql
SELECT 
  DATE(created_at) as date,
  SUM(amount) as total_revenue,
  COUNT(*) as transactions
FROM payments
WHERE payment_type = 'subscription'
AND status = 'completed'
GROUP BY DATE(created_at)
ORDER BY date DESC;
```

### Get Active Subscriptions
```sql
SELECT u.first_name, u.email, p.expires_at, p.subscription_period_months
FROM payments p
JOIN users u ON p.user_id = u.id
WHERE p.status = 'completed'
AND p.expires_at > NOW()
ORDER BY p.expires_at;
```

---

## 📊 Analytics Queries

### User Growth
```sql
SELECT 
  DATE(created_at) as join_date,
  COUNT(*) as new_users
FROM users
GROUP BY DATE(created_at)
ORDER BY join_date DESC
LIMIT 30;
```

### Content Performance
```sql
SELECT 
  title,
  views_count,
  (SELECT COUNT(*) FROM comments WHERE post_id = posts.id) as comment_count,
  (SELECT COUNT(*) FROM reactions WHERE post_id = posts.id) as reaction_count
FROM posts
WHERE status = 'published'
ORDER BY views_count DESC
LIMIT 20;
```

### Business Performance
```sql
SELECT 
  name,
  views,
  rating,
  rating_count,
  (SELECT COUNT(*) FROM business_services WHERE business_id = businesses.id) as service_count
FROM businesses
WHERE verification_status = 'verified'
ORDER BY rating DESC;
```

### Most Applied Jobs
```sql
SELECT 
  j.title,
  j.location,
  COUNT(a.id) as applications,
  j.status
FROM jobs j
LEFT JOIN job_applications a ON j.id = a.job_id
GROUP BY j.id
ORDER BY applications DESC
LIMIT 20;
```

---

## 🛡️ Security Queries

### Check Recent Login Attempts
```sql
SELECT * FROM auth_logs
WHERE action = 'login'
AND success = FALSE
ORDER BY created_at DESC
LIMIT 50;
```

### Monitor Admin Actions
```sql
SELECT u.first_name, a.action, a.target_type, a.created_at
FROM admin_logs a
JOIN users u ON a.admin_id = u.id
WHERE DATE(a.created_at) = CURDATE()
ORDER BY a.created_at DESC;
```

### Check for Reported Content
```sql
SELECT 
  ar.reason,
  ar.reported_item_type,
  ar.status,
  COUNT(*) as reports
FROM abuse_reports ar
WHERE ar.status = 'pending'
GROUP BY ar.reported_item_type, ar.reason
ORDER BY reports DESC;
```

---

## ⚙️ Connection String

**PHP (PDO):**
```php
$dsn = 'mysql:host=localhost;dbname=infohub;charset=utf8mb4';
$pdo = new PDO($dsn, 'root', '');
```

**PHP (MySQLi):**
```php
$conn = new mysqli('localhost', 'root', '', 'infohub');
$conn->set_charset('utf8mb4');
```

**Node.js (mysql2):**
```javascript
const connection = mysql.createConnection({
  host: 'localhost',
  user: 'root',
  database: 'infohub'
});
```

**Python (mysql-connector):**
```python
import mysql.connector
conn = mysql.connector.connect(
  host='localhost',
  user='root',
  database='infohub'
)
```

---

## 📌 Tips & Best Practices

1. **Always use prepared statements** to prevent SQL injection
2. **Hash passwords** with bcrypt before storing
3. **Implement pagination** for large result sets
4. **Use transactions** for multiple related updates
5. **Index frequently searched fields** (emails, slugs)
6. **Regular backups** at least daily
7. **Monitor error_logs** for application issues
8. **Archive old logs** to maintain performance
9. **Use time zones consistently** (store UTC, convert on display)
10. **Validate foreign keys** before insert/update
