<?php
declare(strict_types=1);

namespace Core;

/**
 * Basic View Engine
 */
class View
{
    private static string $theme = 'default';

    /**
     * Set the current theme
     */
    public static function setTheme(string $theme): void
    {
        self::$theme = $theme;
    }

    /**
     * Render a view component or page
     * 
     * @param string $view Format: 'module/view' or 'common/view'
     * @param array $data Data to be extracted into variables
     */
    public static function render(string $view, array $data = []): void
    {
        extract($data);

        // Convert dot notation to path (e.g., Home.Views.index -> Home/Views/index)
        $path = str_replace('.', '/', $view);

        // Handle case-sensitivity for top-level directories
        if (strpos($path, 'themes/') === 0) {
            $path = 'Themes' . substr($path, 6);
        } elseif (strpos($path, 'modules/') === 0) {
            $path = 'Modules' . substr($path, 7);
        } elseif (strpos($path, 'core/') === 0) {
            $path = 'Core' . substr($path, 4);
        }

        // 1. Check in themes/current_theme/views/
        $themeViewPath = THEMES_PATH . '/' . self::$theme . '/views/' . $path . '.php';
        
        // 2. Check in modules/
        $modulePath = MODULES_PATH . '/' . $path . '.php';

        // 3. Check directly from ROOT (for themes.default.layout etc.)
        $rootPath = ROOT_PATH . '/' . $path . '.php';

        if (file_exists($themeViewPath)) {
            require $themeViewPath;
        } elseif (file_exists($modulePath)) {
            require $modulePath;
        } elseif (file_exists($rootPath)) {
            require $rootPath;
        } else {
            throw new \Exception("View file not found: $view. Searched in: $themeViewPath, $modulePath, $rootPath");
        }
    }

    /**
     * Helper to link assets
     */
    public static function asset(string $path): string
    {
        return '/assets/' . ltrim($path, '/');
    }
}
