<?php
/**
 * Google OAuth Helper
 */

class GoogleAuth {
    private $config;
    
    public function __construct() {
        $this->config = require ROOT_PATH . '/config/google.php';
    }
    
    /**
     * Get the authorization URL for Google login
     */
    public function getAuthorizationUrl($state = null) {
        if (!$state) {
            $state = bin2hex(random_bytes(16));
            $_SESSION['oauth_state'] = $state;
        }
        
        $params = [
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
        ];
        
        return $this->config['auth_url'] . '?' . http_build_query($params);
    }
    
    /**
     * Exchange authorization code for access token
     */
    public function getAccessToken($code) {
        $params = [
            'client_id' => $this->config['client_id'],
            'client_secret' => $this->config['client_secret'],
            'code' => $code,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->config['redirect_uri'],
        ];
        
        $ch = curl_init($this->config['token_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }
    
    /**
     * Get user info from Google using access token
     */
    public function getUserInfo($accessToken) {
        $ch = curl_init($this->config['userinfo_url']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response, true);
    }
    
    /**
     * Verify OAuth state to prevent CSRF
     */
    public function verifyState($state) {
        return isset($_SESSION['oauth_state']) && $_SESSION['oauth_state'] === $state;
    }
    
    /**
     * Clear OAuth state
     */
    public function clearState() {
        unset($_SESSION['oauth_state']);
    }
}
