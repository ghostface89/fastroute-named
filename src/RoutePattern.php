<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

use Ellipse\FastRoute\Exceptions\WrongNumberOfParametersException;

class RoutePattern
{
    /**
     * The route name.
     *
     * @var string
     */
    private $name;

    /**
     * The route allowed signatures.
     *
     * @var array
     */
    private $signatures;

    /**
     * Set up a route pattern with the given name and allowed signatures.
     *
     * @param string    $name
     * @param array     $signatures
     */
    public function __construct(string $name, array $signatures)
    {
        $this->name = $name;
        $this->signatures = $signatures;
    }

    /**
     * Return an url from the given parameters, query string and fragment.
     *
     * @param array     $parameters
     * @param array     $query
     * @param string    $fragment
     * @return \Ellipse\FastRoute\Url
     * @throws \Ellipse\FastRoute\Exceptions\WrongNumberOfParametersException
     */
    public function url(array $parameters = [], array $query = [], string $fragment = ''): Url
    {
        $given = count($parameters);
        $allowed = [];

        foreach ($this->signatures as $signature) {

            $accepted = count(array_filter($signature, 'is_array'));

            if ($given == $accepted) {

                return new Url(new Path($this->name, $signature, $parameters), $query, $fragment);

            }

            $allowed[] = $accepted;

        }

        throw new WrongNumberOfParametersException($this->name, $allowed, $given);
    }
}
