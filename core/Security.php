<?php
declare(strict_types=1);

namespace Core;

class Security
{
    /**
     * Generate or return existing CSRF token
     */
    public static function csrf_token(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }

    /**
     * Validate CSRF token from request
     */
    public static function validate_csrf(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Output a hidden CSRF input field
     */
    public static function csrf_field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . self::csrf_token() . '">';
    }

    /**
     * Check if user is logged in
     */
    public static function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user_id']);
    }

    /**
     * Check if current user has specific role(s)
     */
    public static function checkRole(string|array $roles): bool
    {
        if (!self::isLoggedIn()) {
            return false;
        }

        $userRole = $_SESSION['user_role'] ?? '';
        
        if (is_array($roles)) {
            return in_array($userRole, $roles);
        }
        
        return $userRole === $roles;
    }

    /**
     * Set common security headers
     */
    public static function setSecurityHeaders(): void
    {
        if (headers_sent()) {
            return;
        }

        // Prevent Clickjacking
        header("X-Frame-Options: SAMEORIGIN");
        
        // Prevent MIME sniffing
        header("X-Content-Type-Options: nosniff");
        
        // XSS Protection for older browsers
        header("X-XSS-Protection: 1; mode=block");
        
        // Referrer Policy
        header("Referrer-Policy: strict-origin-when-cross-origin");
        
        // Basic Content Security Policy (Allow self, common CDNs for the CMS)
        // Note: In a production environment, this should be more restrictive
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://code.jquery.com https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://cdn.ckeditor.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com https://cdnjs.cloudflare.com https://cdn.ckeditor.com; font-src 'self' data: https://fonts.gstatic.com https://cdnjs.cloudflare.com; img-src 'self' data: https:; frame-src 'self' https://www.youtube.com https://www.google.com; connect-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://cdn.ckeditor.com;");
    }
}
