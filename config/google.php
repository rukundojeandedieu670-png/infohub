<?php
/**
 * Google OAuth Configuration
 */

return [
    'client_id' => getenv('GOOGLE_CLIENT_ID') ?: 'YOUR_GOOGLE_CLIENT_ID',
    'client_secret' => getenv('GOOGLE_CLIENT_SECRET') ?: 'YOUR_GOOGLE_CLIENT_SECRET',
    'redirect_uri' => (getenv('APP_URL') ?: (defined('APP_URL') ? APP_URL : 'http://localhost/infohub')) . '/auth/google/callback',
    'auth_url' => 'https://accounts.google.com/o/oauth2/v2/auth',
    'token_url' => 'https://oauth2.googleapis.com/token',
    'userinfo_url' => 'https://www.googleapis.com/oauth2/v2/userinfo',
];
