<?php
/**
 * User Model
 */

require_once __DIR__ . '/../../core/Model.php';

class User extends Model {
    protected $table = 'users';

    /**
     * Find user by email
     */
    public function findByEmail($email) {
        return $this->findBy('email', $email);
    }

    /**
     * Find user with role
     */
    public function findByEmailWithRole($email) {
        $this->db->prepare("
            SELECT u.*, r.id as role_id, r.name as role_name 
            FROM {$this->table} u 
            LEFT JOIN roles r ON u.role_id = r.id 
            WHERE u.email = ?
        ");
        $this->db->bind('s', $email);
        $this->db->execute();
        return $this->db->single();
    }

    /**
     * Get all users with role
     */
    public function getAllWithRole($limit = null, $offset = 0) {
        $query = "
            SELECT u.*, r.name as role_name 
            FROM {$this->table} u 
            LEFT JOIN roles r ON u.role_id = r.id 
            ORDER BY u.created_at DESC
        ";
        
        if ($limit) {
            $query .= " LIMIT ? OFFSET ?";
            $this->db->prepare($query);
            $this->db->bind('i', $limit);
            $this->db->bind('i', $offset);
        } else {
            $this->db->prepare($query);
        }
        
        $this->db->execute();
        return $this->db->resultSet();
    }

    /**
     * Create new user
     */
    public function createUser($data) {
        $roleId = $data['role_id'] ?? $this->getRoleIdByName('Registered User');

        return $this->insert([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password_hash' => $data['password_hash'],
            'role_id' => $roleId,
            'is_active' => true
        ]);
    }

    /**
     * Get role id by role name.
     */
    protected function getRoleIdByName($roleName) {
        $this->db->prepare("SELECT id FROM roles WHERE name = ? LIMIT 1");
        $this->db->bind('s', $roleName);
        $this->db->execute();
        $result = $this->db->single();
        return $result['id'] ?? null;
    }

    /**
     * Update last login
     */
    public function updateLastLogin($userId) {
        $this->db->prepare("UPDATE {$this->table} SET last_login_at = NOW() WHERE id = ?");
        $this->db->bind('i', $userId);
        $this->db->execute();
    }

    /**
     * Verify email
     */
    public function verifyEmail($userId) {
        $this->db->prepare("UPDATE {$this->table} SET email_verified = 1, email_verified_at = NOW() WHERE id = ?");
        $this->db->bind('i', $userId);
        $this->db->execute();
    }

    /**
     * Get users by role
     */
    public function getByRole($roleId) {
        return $this->findAllBy('role_id', $roleId);
    }

    /**
     * Create password reset token
     * Returns the plain token (not hashed) to send to user
     */
    public function createPasswordResetToken($userId) {
        // Generate secure random token
        $plainToken = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $plainToken);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        try {
            // Delete any existing unused tokens for this user
            $this->db->prepare("DELETE FROM password_reset_tokens 
                               WHERE user_id = ? AND used_at IS NULL");
            $this->db->bind('i', $userId);
            $this->db->execute();

            // Create new token
            $this->db->prepare("INSERT INTO password_reset_tokens 
                               (user_id, token_hash, expires_at, ip_address, user_agent) 
                               VALUES (?, ?, ?, ?, ?)");
            $this->db->bind('i', $userId);
            $this->db->bind('s', $tokenHash);
            $this->db->bind('s', $expiresAt);
            $this->db->bind('s', $ipAddress);
            $this->db->bind('s', $userAgent);
            $this->db->execute();

            return $plainToken; // Return plain token to send to user
        } catch (Exception $e) {
            Logger::logError('Password Reset Token Creation', $e->getMessage());
            return false;
        }
    }

    /**
     * Verify password reset token
     */
    public function verifyPasswordResetToken($email, $plainToken) {
        try {
            $tokenHash = hash('sha256', $plainToken);
            
            $this->db->prepare("
                SELECT prt.*, u.id as user_id, u.email 
                FROM password_reset_tokens prt
                JOIN users u ON prt.user_id = u.id
                WHERE u.email = ? 
                AND prt.token_hash = ? 
                AND prt.expires_at > NOW() 
                AND prt.used_at IS NULL
                LIMIT 1
            ");
            $this->db->bind('s', $email);
            $this->db->bind('s', $tokenHash);
            $this->db->execute();

            $result = $this->db->single();
            return $result ? $result['user_id'] : false;
        } catch (Exception $e) {
            Logger::logError('Password Reset Token Verification', $e->getMessage());
            return false;
        }
    }

    /**
     * Complete password reset - hash and invalidate token
     */
    public function completePasswordReset($email, $plainToken, $newPassword) {
        try {
            $tokenHash = hash('sha256', $plainToken);
            $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);

            // Verify token and get user
            $userId = $this->verifyPasswordResetToken($email, $plainToken);
            if (!$userId) {
                return false;
            }

            // Update password and mark token as used in a transaction-like manner
            $this->db->prepare("UPDATE password_reset_tokens 
                               SET used_at = NOW() 
                               WHERE token_hash = ? AND used_at IS NULL");
            $this->db->bind('s', $tokenHash);
            $this->db->execute();

            // Update user password
            $this->db->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $this->db->bind('s', $passwordHash);
            $this->db->bind('i', $userId);
            $this->db->execute();

            return true;
        } catch (Exception $e) {
            Logger::logError('Password Reset Completion', $e->getMessage());
            return false;
        }
    }

    /**
     * Check if email exists
     */
    public function emailExists($email) {
        return $this->findByEmail($email) !== null;
    }

    /**
     * Count failed password reset attempts (for rate limiting)
     */
    public function countRecentPasswordResetAttempts($email, $minutes = 15) {
        try {
            $this->db->prepare("
                SELECT COUNT(*) as count 
                FROM password_reset_tokens prt
                JOIN users u ON prt.user_id = u.id
                WHERE u.email = ? 
                AND prt.created_at > DATE_SUB(NOW(), INTERVAL ? MINUTE)
            ");
            $this->db->bind('s', $email);
            $this->db->bind('i', $minutes);
            $this->db->execute();

            $result = $this->db->single();
            return $result['count'] ?? 0;
        } catch (Exception $e) {
            Logger::logError('Password Reset Attempts Count', $e->getMessage());
            return 0;
        }
    }
}
