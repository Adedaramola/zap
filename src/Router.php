<?php

declare(strict_types=1);

namespace Adedaramola\Zap;

use Adedaramola\Zap\Enums\Method;

class Router
{
    /**
     * routes
     *
     * @var array
     */
    private $routesTree = [];

    private $beforGlobalMiddlewares = [];

    private $afterGlobalMiddlewares = [];

    /**
     * Default controller's namespace
     *
     * @var string
     */
    private $namespace = '';

    private $requestedMethod = '';

    private $serverBasePath = '';

    /**
     * get
     *
     * @param  string $pattern
     * @param  array|string|callable $action
     * @return Route
     */
    public function get(string $pattern, $action): Route
    {
        $route = new Route($pattern, Method::GET, $action);
        $this->register($route);
        return $route;
    }

    /**
     * Register a path with equivalent handler to be called
     *
     * @param  Route $route
     * @return void
     */
    private function register(Route $route): void
    {
        $this->routesTree[$route->method][] = $route;
    }

    public function use(string ...$middlewares): void
    {
    }

    /**
     * getRequestMethod
     *
     * @return string
     */
    protected function getRequestMethod(): string
    {
        // Obtain the method from the $_SERVER global variable
        $method = $_SERVER['REQUEST_METHOD'];

        // Override HEAD requests to GET
        if ($method == 'HEAD') {
            ob_start();
            $method = 'GET';
        }

        // If it's a POST request, check for a method override header
        elseif ($method == 'POST') {
            $headers = $this->getRequestHeaders();
            if (isset($headers['X-HTTP-Method-Override']) && in_array($headers['X-HTTP-Method-Override'], array('PUT', 'DELETE', 'PATCH'))) {
                $method = $headers['X-HTTP-Method-Override'];
            }
        }

        return $method;
    }

    private function getRequestHeaders(): array
    {
        $headers = array();

        // If getallheaders() is available, use that
        if (function_exists('getallheaders')) {
            $headers = getallheaders();

            // getallheaders() can return false if something went wrong
            if ($headers !== false) {
                return $headers;
            }
        }

        // Method getallheaders() not available or went wrong: manually extract 'm
        foreach ($_SERVER as $name => $value) {
            if ((substr($name, 0, 5) == 'HTTP_') || ($name == 'CONTENT_TYPE') || ($name == 'CONTENT_LENGTH')) {
                $headers[str_replace(array(' ', 'Http'), array('-', 'HTTP'), ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }

        return $headers;
    }

    public function getBasePath(): string
    {
        $scriptNameParts = explode('/', $_SERVER['SCRIPT_NAME']);

        if (is_null($this->serverBasePath)) {
            $this->serverBasePath = implode('/', array_slice($scriptNameParts, 0, -1)) . '/';
        }

        return $this->serverBasePath;
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * setNamespace
     *
     * @param  string $namespace
     * @return void
     */
    public function setNamespace($namespace): void
    {
        if (is_string($namespace)) {
            $this->namespace = $namespace;
        }
    }

    /**
     * run
     *
     * @return bool
     */
    public function run(): bool
    {
        $this->requestedMethod = $this->getRequestMethod();

        $handledRoutes = 0;

        if (isset($this->routesTree[$this->requestedMethod])) {
            // 
        }

        if ($this->requestedMethod === 'HEAD') {
            ob_end_clean();
        }

        return $handledRoutes !== 0;
    }

    /**
     * handle
     *
     * @param  array $routes
     * @return int
     */
    private function handle($routes): int
    {
        $handledRoutes = 0;

        $uri = $this->getCurrentUri();

        foreach ($routes as $route) {
            $matches = $this->patternMatches($route->pattern, $uri);
        }

        return $handledRoutes;
    }

    public function getCurrentUri(): string
    {
        $uri = substr(rawurldecode($_SERVER['REQUEST_URI']), strlen($this->getBasePath()));

        // Remove all queries
        if (strstr($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        // Remove trailing slash + enforce a slash at the start
        return '/' . trim($uri, '/');
    }

    /**
     * patternMatches
     *
     * @param  string $pattern
     * @param  string $uri
     * @return bool
     */
    private function patternMatches(string $pattern, string $uri): bool
    {
        $pattern = preg_replace('/\/{(.*?)}/', '/(.*?)', $pattern);

        return boolval(preg_match_all('#^' . $pattern . '$#', $uri, $matches, PREG_OFFSET_CAPTURE));
    }
}
