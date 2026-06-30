<?php
namespace App\Core;

class View
{
    private static array $shared = [];

    public static function share(string $key, mixed $value): void
    {
        self::$shared[$key] = $value;
    }

    public static function render(string $view, array $data = []): void
    {
        $data = array_merge(self::$shared, $data);
        $data['view'] = $view;

        extract($data);

        $viewPath = __DIR__ . '/../../views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            throw new \RuntimeException("View not found: {$viewPath}");
        }

        $layout = $data['layout'] ?? 'main';

        // Check if this view already includes layout
        if (isset($data['_no_layout']) && $data['_no_layout']) {
            require $viewPath;
            return;
        }

        $layoutPath = __DIR__ . '/../../views/layouts/' . $layout . '.php';

        if (file_exists($layoutPath)) {
            $content = self::capture($viewPath, $data);
            require $layoutPath;
        } else {
            require $viewPath;
        }
    }

    public static function renderPartial(string $view, array $data = []): string
    {
        $data = array_merge(self::$shared, $data);
        $viewPath = __DIR__ . '/../../views/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewPath)) {
            return '';
        }

        return self::capture($viewPath, $data);
    }

    private static function capture(string $__path, array $__data): string
    {
        extract($__data);
        ob_start();
        require $__path;
        return ob_get_clean();
    }

    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public static function redirect(string $url, int $status = 302): void
    {
        header("Location: {$url}", true, $status);
        exit;
    }

    public static function notFound(): void
    {
        http_response_code(404);
        self::render('errors/404', ['title' => 'Page Not Found'], false);
        exit;
    }

    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
