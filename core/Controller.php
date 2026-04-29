<?php
declare(strict_types=1);

namespace Core;

/**
 * Base Controller Class
 */
abstract class Controller
{
    public function __construct()
    {
        // Global CSRF Protection for all POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::validate_csrf()) {
                header("HTTP/1.0 403 Forbidden");
                die("Invalid CSRF Token. Please refresh the page and try again.");
            }
        }
    }
    /**
     * Render a view file
     */
    protected function render(string $view, array $data = []): void
    {
        View::render($view, $data);
    }

    /**
     * Render a view within a layout
     */
    protected function renderWithLayout(string $view, string $layout, array $data = []): void
    {
        ob_start();
        View::render($view, $data);
        $content = ob_get_clean();
        
        $data['content'] = $content;
        View::render($layout, $data);
    }

    /**
     * Redirect to another URL
     */
    protected function redirect(string $path): void
    {
        header("Location: " . url($path));
        exit;
    }

    /**
     * Require authentication
     */
    protected function requireAuth(): void
    {
        if (!Security::isLoggedIn()) {
            $this->redirect('/auth');
        }
    }

    /**
     * Require a specific role
     */
    protected function requireRole(string|array $roles): void
    {
        $this->requireAuth();
        if (!Security::checkRole($roles)) {
            header("HTTP/1.1 403 Forbidden");
            $this->renderWithLayout('themes.admin.error_403', 'themes.admin.layout', [
                'title' => 'Access Denied'
            ]);
            exit;
        }
    }

    /**
     * Set JSON response
     */
    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
