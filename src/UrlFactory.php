<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

class UrlFactory
{
    /**
     * The named route collector.
     *
     * @var \Ellipse\FastRoute\NamedRouteCollector
     */
    private $collector;

    /**
     * Set up an url factory with the given named route collector.
     *
     * @param \Ellipse\FastRoute\NamedRouteCollector $collector
     */
    public function __construct(NamedRouteCollector $collector)
    {
        $this->collector = $collector;
    }

    /**
     * Return an url from the route pattern matching the given name and
     * parameters, enventually appending the given query string and fragment.
     *
     * @param string    $name
     * @param array     $parameters
     * @param array     $query
     * @param string    $fragment
     * @return \Ellipse\FastRoute\Url
     */
    public function __invoke(string $name, array $parameters = [], array $query = [], string $fragment = ''): Url
    {
        return $this->collector->pattern($name)->url($parameters, $query, $fragment);
    }
}
