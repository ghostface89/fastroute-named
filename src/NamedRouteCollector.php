<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

use FastRoute\RouteCollector;
use FastRoute\RouteParser;

use Ellipse\FastRoute\Exceptions\RouteNameNotMappedException;
use Ellipse\FastRoute\Exceptions\RouteNameAlreadyMappedException;

class NamedRouteCollector
{
    /**
     * The delegate.
     *
     * @var \FastRoute\RouteCollector
     */
    private $delegate;

    /**
     * Associative array of name => pattern pairs.
     *
     * @var array
     */
    private $name2pattern = [];

    /**
     * The fastroute parser.
     *
     * @var \FastRoute\RouteParser
     */
    private $parser;

    /**
     * Set up a named route collector with the given delegate.
     *
     * @param \FastRoute\RouteCollector $delegate
     */
    public function __construct(RouteCollector $delegate)
    {
        $this->delegate = $delegate;
        $this->parser = new RouteParser\Std;
    }

    /**
     * Return the route pattern associated with the given name.
     *
     * @param string $name
     * @return \Ellipse\FastRoute\RoutePattern
     * @throws \Ellipse\FastRoute\Exceptions\RouteNameNotMappedException
     */
    public function pattern(string $name): RoutePattern
    {
        if (array_key_exists($name, $this->name2pattern)) {

            $signatures = $this->parser->parse($this->name2pattern[$name]);

            return new RoutePattern($name, $signatures);

        }

        throw new RouteNameNotMappedException($name);
    }

    /**
     * Associate non empty name with the given route pattern, then proxy the
     * delegate ->addRoute() method.
     *
     * @param string            $name
     * @param string|string[]   $httpMethod
     * @param string            $route
     * @param mixed             $handler
     * @return void
     * @throws \Ellipse\FastRoute\Exceptions\RouteNameAlreadyMappedException
     */
    public function addRoute(string $name, $httpMethod, $route, $handler): void
    {
        if ($name != '') {

            if (array_key_exists($name, $this->name2pattern)) {

                throw new RouteNameAlreadyMappedException($name);

            }

            $this->name2pattern[$name] = $route;

        }

        $this->delegate->addRoute($httpMethod, $route, $handler);
    }

    /**
     * Adds a GET route to the collection
     *
     * This is simply an alias of $this->addRoute('GET', $route, $handler)
     *
     * @param string    $name
     * @param string    $route
     * @param mixed     $handler
     */
    public function get(string $name, $route, $handler): void
    {
        $this->addRoute($name, 'GET', $route, $handler);
    }

    /**
     * Adds a POST route to the collection
     *
     * This is simply an alias of $this->addRoute('POST', $route, $handler)
     *
     * @param string    $name
     * @param string    $route
     * @param mixed     $handler
     */
    public function post(string $name, $route, $handler): void
    {
        $this->addRoute($name, 'POST', $route, $handler);
    }

    /**
     * Adds a PUT route to the collection
     *
     * This is simply an alias of $this->addRoute('PUT', $route, $handler)
     *
     * @param string    $name
     * @param string    $route
     * @param mixed     $handler
     */
    public function put(string $name, $route, $handler): void
    {
        $this->addRoute($name, 'PUT', $route, $handler);
    }

    /**
     * Adds a DELETE route to the collection
     *
     * This is simply an alias of $this->addRoute('DELETE', $route, $handler)
     *
     * @param string    $name
     * @param string    $route
     * @param mixed     $handler
     */
    public function delete(string $name, $route, $handler): void
    {
        $this->addRoute($name, 'DELETE', $route, $handler);
    }

    /**
     * Adds a PATCH route to the collection
     *
     * This is simply an alias of $this->addRoute('PATCH', $route, $handler)
     *
     * @param string    $name
     * @param string    $route
     * @param mixed     $handler
     */
    public function patch(string $name, $route, $handler): void
    {
        $this->addRoute($name, 'PATCH', $route, $handler);
    }

    /**
     * Adds a HEAD route to the collection
     *
     * This is simply an alias of $this->addRoute('HEAD', $route, $handler)
     *
     * @param string    $name
     * @param string    $route
     * @param mixed     $handler
     */
    public function head(string $name, $route, $handler): void
    {
        $this->addRoute($name, 'HEAD', $route, $handler);
    }

    /**
     * Proxy the delegate ->getData() method.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->delegate->getData();
    }
}
