<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

use FastRoute\RouteParser;

use Ellipse\FastRoute\Exceptions\RouteNameNotMappedException;
use Ellipse\FastRoute\Exceptions\RouteNameAlreadyMappedException;

class Map
{
    /**
     * The list of prefixes to prepend to the route names.
     *
     * @var string
     */
    private $prefixes = [];

    /**
     * The associative array of name => route pattern pairs.
     *
     * @var array
     */
    private $name2pattern = [];

    /**
     * The fastroute route parser
     *
     * @var \FastRoute\RouteParser\Std
     */
    private $parser;

    /**
     * Set up a map.
     */
    public function __construct()
    {
        $this->parser = new RouteParser\Std;
    }

    /**
     * Return a new RoutePAtter from the route pattern associated to the given
     * name.
     *
     * @param string $name
     * @return \Ellipse\FastRoute\RoutePattern
     */
    public function pattern(string $name): RoutePattern
    {
        if (array_key_exists($name, $this->name2pattern)) {

            $pattern = $this->name2pattern[$name];

            $signatures = $this->parser->parse($pattern);

            return new RoutePattern($name, $signatures);

        }

        throw new RouteNameNotMappedException($name);
    }

    /**
     * Associate the given name with the given route pattern when not empty. The
     * given name gets prefixed with the current prefix.
     *
     * @param string $name
     * @param string $route
     * @return void
     */
    public function associate(string $name, string $route): void
    {
        if ($name != '') {

            $prefixed = $this->prefixed($name);

            if (array_key_exists($prefixed, $this->name2pattern)) {

                throw new RouteNameAlreadyMappedException($prefixed);

            }

            $this->name2pattern[$prefixed] = $route;

        }
    }

    /**
     * Return the givne name prefixed with the current prefix (spaced by a dot).
     *
     * @param string $name
     * @return string
     */
    private function prefixed(string $name): string
    {
        $parts = array_merge($this->prefixes, [$name]);

        return implode('.', array_filter($parts));
    }

    /**
     * Add a prefix to the current prefix (spaced by a dot).
     *
     * @param string $name
     * @return void
     */
    public function addPrefix(string $prefix): void
    {
        $this->prefixes[] = $prefix;
    }

    /**
     * Remove the last prefix to the current prefix.
     *
     * @return void
     */
    public function removePrefix(): void
    {
        array_pop($this->prefixes);
    }
}
