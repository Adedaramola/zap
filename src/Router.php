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
    protected $routes = array();

    protected Response $response;

    public function get(string $pattern, $handler): void
    {
    }

    public function post(string $pattern, $handler): void
    {
    }

    public function put(string $pattern, $handler): void
    {
    }

    public function patch(string $pattern, $handler): void
    {
    }

    public function delete(string $pattern, $handler): void
    {
    }

    /**
     * match
     *
     * @param  \Adedaramola\Zap\Enums $method
     * @param  string $path
     * @param  Object|callable $handler
     * @return void
     */
    protected function match(Method $method, string $path, callable $handler)
    {
    }

    /**
     * getRequestMethod
     *
     * @return string
     */
    protected function getRequestMethod(): string
    {
        return '';
    }

    /**
     * run
     *
     * @return void
     */
    public function run(): void
    {
    }
}
