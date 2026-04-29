<?php
declare(strict_types=1);

namespace Core;

/**
 * Visitor Counter Utility
 */
class Visitor
{
    /**
     * Log the current visit
     */
    public static function logVisit(): void
    {
        // Don't log if not installed
        if (!file_exists(ROOT_PATH . '/config.php')) {
            return;
        }

        try {
            $pageUrl = $_SERVER['REQUEST_URI'] ?? '/';
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $sessionId = session_id();
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'none';

            // Check if this session already visited this page in the last hour to prevent spamming
            $exists = Database::fetch(
                "SELECT id FROM visitor_counter WHERE session_id = ? AND page_url = ? AND visited_at > (NOW() - INTERVAL 1 HOUR)",
                [$sessionId, $pageUrl]
            );

            if (!$exists) {
                Database::query(
                    "INSERT INTO visitor_counter (page_url, ip_address, session_id, user_agent) VALUES (?, ?, ?, ?)",
                    [$pageUrl, $ipAddress, $sessionId, $userAgent]
                );
            }
        } catch (\Throwable $e) {
            // Log error to PHP error log but don't crash the site
            error_log("Visitor Counter Error: " . $e->getMessage());
        }
    }
}
