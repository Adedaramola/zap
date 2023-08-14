<?php

declare(strict_types=1);

namespace Adedaramola\Zap;

class Route
{
    /**
     * pattern
     *
     * @var string
     */
    public $pattern;

    /**
     * method
     *
     * @var string
     */
    public $method;

    /**
     * action
     *
     * @var \Closure|array
     */
    public $action;

    /**
     * parameterBag
     *
     * @var array
     */
    public $parameterBag;

    /**
     * middlewares
     *
     * @var array
     */
    private $middlewares;

    /**
     * Instantiate a new Route instance
     *
     * @param  string $pattern
     * @param  string $method
     * @param  \Closure|array $action
     * @return void
     */
    public function __construct($pattern, $method, $action)
    {
        $this->pattern = $pattern;
        $this->method = $method;
        $this->action = $action;
    }

    /**
     * Append middleware to the route stack
     *
     * @param  array $middlewares
     * @return Route
     */
    public function middleware($middlewares): Route
    {
        foreach ($middlewares as $middleware) {
            $this->middlewares[] = $middleware;
        }

        return $this;
    }
}
