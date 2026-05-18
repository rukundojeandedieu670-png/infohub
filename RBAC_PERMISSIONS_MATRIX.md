# RBAC Permission Matrix

## Quick Reference - Which Roles Can Do What?

| Action | Super Admin | Admin | Editor | Writer | Business Owner | Employer | Registered User |
|--------|:-----------:|:-----:|:------:|:------:|:--------------:|:--------:|:---------------:|
| **User Management** | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Content Management** | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Business Management** | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Job Management** | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| **Create Posts** | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Edit Own Posts** | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Delete Own Posts** | ✅ | ❌ | ✅ | ✅ | ❌ | ❌ | ❌ |
| **Manage Comments** | ✅ | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ |
| **Create Comments** | ✅ | ❌ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Create Businesses** | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ |
| **Edit Own Businesses** | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ |
| **Delete Own Businesses** | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ |
| **Create Jobs** | ✅ | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ |
| **Edit Own Jobs** | ✅ | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ |
| **Delete Own Jobs** | ✅ | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ |
| **View Applications** | ✅ | ✅ | ❌ | ❌ | ❌ | ✅ | ❌ |
| **Edit Profile** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **View Profile** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Create Bookmarks** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **Send Messages** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |

## Permission Details

### Profile Permissions
- **profile.view**: Can view own profile
- **profile.edit**: Can edit own profile

### Post Permissions
- **posts.create**: Can create new posts
- **posts.edit_own**: Can edit own posts
- **posts.delete_own**: Can delete own posts

### Business Permissions
- **businesses.create**: Can create business listings
- **businesses.edit_own**: Can edit own businesses
- **businesses.delete_own**: Can delete own businesses

### Job Permissions
- **jobs.create**: Can create job postings
- **jobs.edit_own**: Can edit own job postings
- **jobs.delete_own**: Can delete own job postings

### Admin Permissions
- **users.manage**: Can manage user accounts
- **content.manage**: Can manage all content
- **businesses.manage**: Can manage all businesses
- **jobs.manage**: Can manage all jobs
- **comments.manage**: Can manage all comments

## Code Examples

### Checking Permissions
```php
// In your controller
public function createJob() {
    $this->requireLogin();
    $this->requirePermission('jobs.create');
    // Only Employers and Super Admins can reach this point
}

public function editOwnBusiness($businessId) {
    $this->requireLogin();
    
    if (!$this->canEditOwn($businessId)) {
        http_response_code(403);
        exit;
    }
    // Process update
}
```

### Frontend Display
```php
<?php if ($this->hasPermission('posts.create')): ?>
    <a href="<?php echo APP_URL; ?>/posts/create">Create Post</a>
<?php endif; ?>
```

## Default Role Assignment

When a new user registers, they are automatically assigned the **Registered User** role (role_id = 7).

To assign a different role, update the `role_id` in the users table:
- 1: Super Admin
- 2: Admin
- 3: Editor
- 4: Writer
- 5: Business Owner
- 6: Employer
- 7: Registered User

---
**Last Updated**: May 16, 2026
