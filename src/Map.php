<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

use FastRoute\RouteParser;

use Ellipse\FastRoute\Exceptions\RouteNameNotMappedException;
use Ellipse\FastRoute\Exceptions\RouteNameAlreadyMappedException;

class Map
{
    /**
     * The list of prefixes to prepend to the names.
     *
     * @var string
     */
    private $names = [];

    /**
     * The list of prefixes to prepend to the route patterns.
     *
     * @var string
     */
    private $patterns = [];

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
    public function associate(string $name, string $route)
    {
        if ($name != '') {

            $prefixed_name = $this->prefixedName($name);
            $prefixed_pattern = $this->prefixedPattern($route);

            if (array_key_exists($prefixed_name, $this->name2pattern)) {

                throw new RouteNameAlreadyMappedException($prefixed_name);

            }

            $this->name2pattern[$prefixed_name] = $prefixed_pattern;

        }
    }

    /**
     * Return the given name prefixed with the current name prefixes (spaced by
     * a dot).
     *
     * @param string $name
     * @return string
     */
    private function prefixedName(string $name): string
    {
        $parts = array_merge($this->names, [$name]);

        return implode('.', array_filter($parts));
    }

    /**
     * Return the given route pattern prefixed with the current route pattern
     * prefixes.
     *
     * @param string $pattern
     * @return string
     */
    private function prefixedPattern(string $pattern): string
    {
        $parts = array_merge($this->patterns, [$pattern]);

        return implode('', array_filter($parts));
    }

    /**
     * Add a name prefix to the current name prefixes (spaced by a dot).
     *
     * @param string $name
     * @return void
     */
    public function addNamePrefix(string $prefix): void
    {
        $this->names[] = $prefix;
    }

    /**
     * Remove the last name prefix from the current name prefixes.
     *
     * @return void
     */
    public function removeNamePrefix(): void
    {
        array_pop($this->names);
    }

    /**
     * Add a route pattern prefix to the current route pattern prefixes.
     *
     * @param string $name
     * @return void
     */
    public function addPatternPrefix(string $prefix): void
    {
        $this->patterns[] = $prefix;
    }

    /**
     * Remove the last route pattern prefix from the current route pattern
     * prefixes.
     *
     * @return void
     */
    public function removePatternPrefix(): void
    {
        array_pop($this->patterns);
    }
}
