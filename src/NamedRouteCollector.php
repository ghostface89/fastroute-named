<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

use FastRoute\RouteCollector;

use Ellipse\FastRoute\Exceptions\RouteNameNotAStringException;

class NamedRouteCollector
{
    /**
     * The delegate.
     *
     * @var \FastRoute\RouteCollector
     */
    private $delegate;

    /**
     * The name to route pattern map.
     *
     * @var \Ellipse\FastRoute\Map
     */
    private $map;

    /**
     * Set up a named route collector with the given delegate.
     *
     * @param \FastRoute\RouteCollector $delegate
     */
    public function __construct(RouteCollector $delegate)
    {
        $this->delegate = $delegate;
        $this->map = new Map;
    }

    /**
     * Proxy the map ->pattern() method.
     *
     * @param string $name
     * @return \Ellipse\FastRoute\RoutePattern
     */
    public function pattern(string $name): RoutePattern
    {
        return $this->map->pattern($name);
    }

    /**
     * Proxy the delegate ->addRoute() method. Allow to pass an optional name as
     * first argument which gets mapped to the route pattern.
     *
     * @param mixed ...$args
     * @return void
     */
    public function addRoute(...$args)
    {
        $name = count($args) == 4 ? array_shift($args) : '';
        $httpMethod = array_shift($args);
        $route = array_shift($args);
        $handler = array_shift($args);

        if (! is_string($name)) {

            throw new RouteNameNotAStringException($name);

        }

        $this->map->associate($name, $route);

        $this->delegate->addRoute($httpMethod, $route, $handler);
    }

    /**
     * Proxy the delegate ->addGroup() by passing this named route collector to
     * the given callback. Allow to pass an optional name prefix which gets
     * preprended to all names defined in the callback.
     *
     * @param mixed ...$args
     * @return void
     */
    public function addGroup(...$args)
    {
        $name_prefix = count($args) == 3 ? array_shift($args) : '';
        $route_prefix = array_shift($args);
        $callback = array_shift($args);

        if (! is_string($name_prefix)) {

            throw new RouteNameNotAStringException($name_prefix);

        }

        $this->delegate->addGroup($route_prefix, function ($r) use ($name_prefix, $callback) {

            $this->map->addPrefix($name_prefix);

            $callback($this);

            $this->map->removePrefix();

        });
    }

    /**
     * Proxy the delegate ->getData() method.
     *
     * @return array
     */
    public function getData()
    {
        return $this->delegate->getData();
    }

    /**
     * Proxy the delegate ->get() method. Allow to pass an optional name as
     * first argument which gets mapped to the route pattern.
     *
     * @param mixed ...$args
     * @return void
     */
    public function get(...$args)
    {
        $this->shortcut('GET', ...$args);
    }

    /**
     * Proxy the delegate ->post() method. Allow to pass an optional name as
     * first argument which gets mapped to the route pattern.
     *
     * @param mixed ...$args
     * @return void
     */
    public function post(...$args)
    {
        $this->shortcut('POST', ...$args);
    }

    /**
     * Proxy the delegate ->put() method. Allow to pass an optional name as
     * first argument which gets mapped to the route pattern.
     *
     * @param mixed ...$args
     * @return void
     */
    public function put(...$args)
    {
        $this->shortcut('PUT', ...$args);
    }

    /**
     * Proxy the delegate ->delete() method. Allow to pass an optional name as
     * first argument which gets mapped to the route pattern.
     *
     * @param mixed ...$args
     * @return void
     */
    public function delete(...$args)
    {
        $this->shortcut('DELETE', ...$args);
    }

    /**
     * Proxy the delegate ->patch() method. Allow to pass an optional name as
     * first argument which gets mapped to the route pattern.
     *
     * @param mixed ...$args
     * @return void
     */
    public function patch(...$args)
    {
        $this->shortcut('PATCH', ...$args);
    }

    /**
     * Proxy the delegate ->head() method. Allow to pass an optional name as
     * first argument which gets mapped to the route pattern.
     *
     * @param mixed ...$args
     * @return void
     */
    public function head(...$args)
    {
        $this->shortcut('HEAD', ...$args);
    }

    /**
     * Utility method containing the logic used to add an optional name when
     * using shortcuts.
     *
     * @param mixed ...$args
     * @return void
     */
    private function shortcut($httpMethod, ...$args)
    {
        if (count($args) == 3) {

            $name = array_shift($args);
            array_unshift($args, $httpMethod);
            array_unshift($args, $name);

        } else {

            array_unshift($args, $httpMethod);

        }

        $this->addRoute(...$args);
    }
}
