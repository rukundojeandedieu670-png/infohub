# Role-Based Access Control (RBAC) Implementation

## Overview
The InfoHub system now implements comprehensive role-based access control (RBAC) that allows different actions based on user roles. Instead of restricting actions only to admins/super admins, permissions are now granted based on the user's assigned role.

## Available Roles

### 1. **Super Admin**
- **Description**: Full system access
- **Permissions**: All actions (`*`)
- **Can**: Manage users, content, businesses, jobs, payments, reports, and everything else

### 2. **Admin**
- **Description**: Administrator access
- **Permissions**: 
  - `users.manage`
  - `content.manage`
  - `businesses.manage`
  - `jobs.manage`
  - `profile.edit`
  - `profile.view`

### 3. **Editor**
- **Description**: Content editor and publisher
- **Permissions**:
  - `posts.create`
  - `posts.edit_own`
  - `posts.delete_own`
  - `comments.manage`
  - `profile.edit`
  - `profile.view`

### 4. **Writer**
- **Description**: Creates articles and updates
- **Permissions**:
  - `posts.create`
  - `posts.edit_own`
  - `posts.delete_own`
  - `profile.edit`
  - `profile.view`

### 5. **Business Owner**
- **Description**: Manages business listings
- **Permissions**:
  - `businesses.create`
  - `businesses.edit_own`
  - `businesses.delete_own`
  - `profile.edit`
  - `profile.view`

### 6. **Employer**
- **Description**: Posts and manages jobs
- **Permissions**:
  - `jobs.create`
  - `jobs.edit_own`
  - `jobs.delete_own`
  - `applications.view`
  - `profile.edit`
  - `profile.view`

### 7. **Registered User**
- **Description**: Basic user interactions
- **Permissions**:
  - `profile.edit` (own profile only)
  - `profile.view` (own profile only)
  - `comments.create`
  - `bookmarks.create`

## Using RBAC in Controllers

### Check if User Has Permission
```php
// Check if user has a specific permission
if ($this->hasPermission('posts.create')) {
    // User can create posts
}

// Example in a controller method
public function createPost() {
    $this->requireLogin();
    $this->requirePermission('posts.create');
    
    // Create post...
}
```

### Check if User Can Edit Own Resource
```php
// Check if user can edit their own resource
if ($this->canEditOwn($resourceId)) {
    // User can edit this resource if they own it
    // Or user is Super Admin
}

// Example usage
public function updatePost($postId) {
    $this->requireLogin();
    
    if (!$this->canEditOwn($postId)) {
        $this->redirect(APP_URL . '/403');
    }
    
    // Update post...
}
```

### Other Permission Methods

```php
// Check if user can delete own resource
$this->canDeleteOwn($resourceId);

// Check if user can manage users (admin only)
$this->canManageUsers();

// Check if user can create content
$this->canCreateContent();

// Check if user can edit profile
$this->canEditProfile($userId);

// Check if user can view profile
$this->canViewProfile();

// Generic permission check with auto-redirect
$this->requirePermission('posts.create');
```

## Adding New Permissions

To add new permissions for a role:

1. Open `core/Controller.php`
2. Find the `hasPermission()` method
3. Add the new permission to the appropriate role's permission array

Example:
```php
'Registered User' => [
    'profile.edit',
    'profile.view',
    'comments.create',
    'bookmarks.create',
    'messages.send'  // New permission
]
```

## Creating New Roles

To create a new role in the database:

```sql
INSERT INTO roles (name, description, permissions) VALUES
  ('Role Name', 'Description', '["permission1", "permission2", "permission3"]');
```

Then add it to the permission matrix in `Controller.php`:

```php
'Role Name' => ['permission1', 'permission2', 'permission3']
```

## Permission Naming Convention

Use dot notation for permissions:
- `resource.action` - e.g., `posts.create`, `jobs.edit_own`, `users.manage`
- `resource.action_own` - for actions on own resources, e.g., `posts.edit_own`

## Best Practices

1. **Always Require Login First**
   ```php
   $this->requireLogin();
   $this->requirePermission('action');
   ```

2. **Use Specific Permissions**
   ```php
   // Good: Specific permission
   $this->requirePermission('posts.create');
   
   // Avoid: Too generic
   $this->requireAdmin();
   ```

3. **Check Own Resources**
   ```php
   // For edit/delete operations on user-owned resources
   if (!$this->canEditOwn($resourceId)) {
       http_response_code(403);
       exit;
   }
   ```

4. **Log Permission Violations**
   Already handled by `requirePermission()` method - logs to error log

## Migration from Old System

Old system (discouraged):
```php
$this->requireAdmin();  // Only admins/super admins
```

New system (recommended):
```php
$this->requirePermission('content.manage');  // Specific permission
```

## Examples

### Allow Registered Users to Edit Their Profile
```php
public function editProfile() {
    $this->requireLogin();
    $this->requirePermission('profile.edit');
    // Now all registered users can edit their profile
}
```

### Allow Employers to Create Jobs
```php
public function createJob() {
    $this->requireLogin();
    $this->requirePermission('jobs.create');
    // Only Employers and Super Admins can access
}
```

### Allow Users to Edit Their Own Posts
```php
public function updatePost($postId) {
    $this->requireLogin();
    
    // Get the post to check ownership
    $post = $this->getPost($postId);
    
    if (!$this->canEditOwn($post->user_id)) {
        http_response_code(403);
        exit;
    }
    
    // Update the post
}
```

## Testing Permissions

To test if a permission is working:

1. Create a test user with a specific role
2. Log in as that user
3. Try to access the action
4. Check the error log if access is denied

Example:
```bash
# Check error logs
tail -f logs/error.log | grep "Permission Denied"
```

---
**Last Updated**: May 16, 2026
