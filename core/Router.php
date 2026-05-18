<?php
/**
 * Router Class
 * Handles URL routing
 */

class Router {
    private $routes = [];
    private $currentRoute = null;

    /**
     * Register a route
     */
    public function route($path, $controller, $method = 'GET') {
        $this->routes[] = [
            'path' => $path,
            'controller' => $controller,
            'method' => $method,
            'pattern' => $this->pathToRegex($path)
        ];
    }

    /**
     * Dispatch the request
     */
    public function dispatch($url, $method = 'GET') {
        $url = parse_url($url, PHP_URL_PATH);
        $url = str_replace('/infohub', '', $url);
        $url = trim($url, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $url, $matches)) {
                return $this->executeRoute($route, $matches);
            }
        }

        // 404
        http_response_code(404);
        echo "404 - Page not found";
        exit;
    }

    /**
     * Convert path to regex pattern
     */
    private function pathToRegex($path) {
        $pattern = preg_replace('/\{([^\}]+)\}/', '([^/]+)', $path);
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }

    /**
     * Execute the route
     */
    private function executeRoute($route, $matches) {
        list($controller, $method) = explode('@', $route['controller']);
        
        $controllerPath = $controller;
        $controllerFile = __DIR__ . '/../app/controllers/' . $controllerPath . '.php';

        if (!file_exists($controllerFile)) {
            http_response_code(500);
            die("Controller not found: $controllerPath");
        }

        require_once $controllerFile;

        // Extract the actual class name from the path (last part after /)
        $controllerClass = basename($controllerPath);

        if (!class_exists($controllerClass)) {
            http_response_code(500);
            die("Class not found: $controllerClass");
        }

        $obj = new $controllerClass();

        if (!method_exists($obj, $method)) {
            http_response_code(500);
            die("Method not found: $method in $controllerClass");
        }

        // Pass parameters
        array_shift($matches);
        call_user_func_array([$obj, $method], $matches);
    }
}
