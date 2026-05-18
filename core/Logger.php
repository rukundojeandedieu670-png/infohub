<?php
/**
 * Logger Class
 * Handles all logging operations
 */

class Logger {
    private static $db = null;

    /**
     * Get Database Instance
     */
    private static function getDb() {
        if (self::$db === null) {
            self::$db = Database::getInstance();
        }
        return self::$db;
    }

    /**
     * Log User Activity
     */
    public static function logActivity($userId, $action, $module, $description = null, $ipAddress = null) {
        try {
            $db = self::getDb();
            $ipAddress = $ipAddress ?? self::getClientIP();
            
            $db->prepare('INSERT INTO activity_logs (user_id, action, module, description, ip_address, created_at) 
                         VALUES (?, ?, ?, ?, ?, NOW())')
               ->bind('i', $userId)
               ->bind('s', $action)
               ->bind('s', $module)
               ->bind('s', $description)
               ->bind('s', $ipAddress)
               ->execute();
               
        } catch (Exception $e) {
            error_log('Activity Log Error: ' . $e->getMessage());
        }
    }

    /**
     * Log Authentication Attempt
     */
    public static function logAuth($email, $action, $success = true, $ipAddress = null) {
        try {
            $db = self::getDb();
            $ipAddress = $ipAddress ?? self::getClientIP();
            $successFlag = $success ? 1 : 0;
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
            
            $db->prepare('INSERT INTO auth_logs (email, action, success, ip_address, user_agent, created_at) 
                         VALUES (?, ?, ?, ?, ?, NOW())')
               ->bind('s', $email)
               ->bind('s', $action)
               ->bind('i', $successFlag)
               ->bind('s', $ipAddress)
               ->bind('s', $userAgent)
               ->execute();
               
        } catch (Exception $e) {
            error_log('Auth Log Error: ' . $e->getMessage());
        }
    }

    /**
     * Log Admin Action
     */
    public static function logAdminAction($adminId, $action, $targetType, $targetId, $changes = null) {
        try {
            $db = self::getDb();
            $ipAddress = self::getClientIP();
            
            $db->prepare('INSERT INTO admin_logs (admin_id, action, target_type, target_id, changes, ip_address, created_at) 
                         VALUES (?, ?, ?, ?, ?, ?, NOW())')
               ->bind('i', $adminId)
               ->bind('s', $action)
               ->bind('s', $targetType)
               ->bind('i', $targetId)
               ->bind('s', $changes)
               ->bind('s', $ipAddress)
               ->execute();
               
        } catch (Exception $e) {
            error_log('Admin Log Error: ' . $e->getMessage());
        }
    }

    /**
     * Log System Error
     */
    public static function logError($errorType, $errorMessage, $errorFile = null, $errorLine = null) {
        try {
            $db = self::getDb();
            $errorLine = (int) ($errorLine ?? 0);
            
            $db->prepare('INSERT INTO error_logs (error_type, error_message, error_file, error_line, created_at) 
                         VALUES (?, ?, ?, ?, NOW())')
               ->bind('s', $errorType)
               ->bind('s', $errorMessage)
               ->bind('s', $errorFile)
               ->bind('i', $errorLine)
               ->execute();
               
        } catch (Exception $e) {
            error_log('Error Log Error: ' . $e->getMessage());
        }
    }

    /**
     * Get Client IP Address
     */
    private static function getClientIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        }
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : 'unknown';
    }
}
