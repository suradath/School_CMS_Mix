<?php
declare(strict_types=1);

namespace Core;

/**
 * Router Class
 * Handles URL parsing and routing to controllers
 */
class Router
{
    private array $routes = [];
    private string $currentRoute = '';

    /**
     * Add a route
     * 
     * @param string $path URL path (e.g., /news/view/1)
     * @param string|callable $callback Controller@Action or callable
     */
    public function add(string $path, $callback): void
    {
        $path = $this->trimPath($path);
        $this->routes[$path] = $callback;
    }

    /**
     * Resolve the current request
     */
    public function resolve(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = $this->trimPath($uri);
        
        // Normalize path: ignore index.php if it's the only path
        if ($path === 'index.php') {
            $path = '';
        }
        
        // Root path
        if ($path === '') {
            $this->execute("Modules\Home\Controllers\HomeController@index");
            return;
        }

        // Dynamic Registered Routes Matching (Specific first)
        uksort($this->routes, function($a, $b) {
            return strlen($b) <=> strlen($a);
        });

        foreach ($this->routes as $routePath => $callback) {
            if ($path === $routePath || strpos($path, $routePath . '/') === 0) {
                $params = [];
                if ($path !== $routePath) {
                    $params = explode('/', substr($path, strlen($routePath) + 1));
                }
                $this->execute($callback, $params);
                return;
            }
        }

        // Dynamic matching
        $this->fallbackRouting($path);
    }

    /**
     * Fallback to module-based routing: /module/action/params
     * OR Static Page Check
     */
    private function fallbackRouting(string $path): void
    {
        $parts = explode('/', $path);
        $moduleName = !empty($parts[0]) ? $parts[0] : 'home';
        $action = !empty($parts[1]) ? $parts[1] : 'index';
        $params = array_slice($parts, 2);

        // Map to Module Controller
        $controllerClass = "Modules\\" . ucfirst($moduleName) . "\\Controllers\\" . ucfirst($moduleName) . "Controller";

        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            if (method_exists($controller, $action)) {
                call_user_func_array([$controller, $action], $params);
                return;
            }
        }

        // Check for Static Page Slug in Database
        try {
            if (class_exists('Modules\Home\Controllers\HomeController')) {
                $home = new \Modules\Home\Controllers\HomeController();
                // Passing $path as the slug
                $home->viewPage($path);
                return;
            }
        } catch (\Exception $e) {
            // Silently fail to 404
        }

        // 404 Not Found
        $this->send404();
    }

    private function execute($callback, array $params = []): void
    {
        if (is_callable($callback)) {
            call_user_func_array($callback, $params);
        } elseif (is_string($callback)) {
            // Handle Controller@Action
            list($controller, $method) = explode('@', $callback);
            if (class_exists($controller)) {
                $instance = new $controller();
                call_user_func_array([$instance, $method], $params);
            }
        }
    }

    private function trimPath(string $path): string
    {
        return trim($path, '/');
    }

    private function send404(): void
    {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Not Found</h1>";
        echo "The page you are looking for does not exist.";
    }
}
