<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

use FastRoute\RouteCollector;
use FastRoute\RouteParser;

use Ellipse\FastRoute\Exceptions\RouteNameNotAStringException;
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
     * @param mixed ...$params
     * @return void
     * @throws \Ellipse\FastRoute\Exceptions\RouteNameNotAStringException
     * @throws \Ellipse\FastRoute\Exceptions\RouteNameAlreadyMappedException
     */
    public function addRoute(...$params)
    {
        if (count($params) == 4) {

            $name = array_shift($params);

            if (! is_string($name)) {

                throw new RouteNameNotAStringException($name);

            }

            if (array_key_exists($name, $this->name2pattern)) {

                throw new RouteNameAlreadyMappedException($name);

            }

            if ($name != '') {

                $this->name2pattern[$name] = $params[1];

            }

        }

        $this->delegate->addRoute(...$params);
    }

    /**
     * Handle shortcut.
     *
     * @param mixed $methods
     * @param mixed ...$params
     * @return void
     */
    private function shortcut($methods, ...$params)
    {
        if (count($params) == 3) {

            $name = array_shift($params);

            $params = array_merge([$name], [$methods], $params);

            $this->addRoute(...$params);

        } else {

            $this->addRoute($methods, ...$params);

        }
    }

    /**
     * Adds a GET route to the collection
     *
     * This is simply an alias of $this->addRoute('GET', $route, $handler)
     *
     * @param mixed ...$params
     * @return void
     */
    public function get(...$params)
    {
        $this->shortcut('GET', ...$params);
    }

    /**
     * Adds a POST route to the collection
     *
     * This is simply an alias of $this->addRoute('POST', $route, $handler)
     *
     * @param mixed ...$params
     * @return void
     */
    public function post(...$params)
    {
        $this->shortcut('POST', ...$params);
    }

    /**
     * Adds a PUT route to the collection
     *
     * This is simply an alias of $this->addRoute('PUT', $route, $handler)
     *
     * @param mixed ...$params
     * @return void
     */
    public function put(...$params)
    {
        $this->shortcut('PUT', ...$params);
    }

    /**
     * Adds a DELETE route to the collection
     *
     * This is simply an alias of $this->addRoute('DELETE', $route, $handler)
     *
     * @param mixed ...$params
     * @return void
     */
    public function delete(...$params)
    {
        $this->shortcut('DELETE', ...$params);
    }

    /**
     * Adds a PATCH route to the collection
     *
     * This is simply an alias of $this->addRoute('PATCH', $route, $handler)
     *
     * @param mixed ...$params
     * @return void
     */
    public function patch(...$params)
    {
        $this->shortcut('PATCH', ...$params);
    }

    /**
     * Adds a HEAD route to the collection
     *
     * This is simply an alias of $this->addRoute('HEAD', $route, $handler)
     *
     * @param mixed ...$params
     * @return void
     */
    public function head(...$params)
    {
        $this->shortcut('HEAD', ...$params);
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
