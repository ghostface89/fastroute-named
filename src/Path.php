<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

use Ellipse\FastRoute\Exceptions\WrongParameterFormatException;

class Path
{
    /**
     * The route name.
     *
     * @var string
     */
    private $name;

    /**
     * The parts composing the pattern signature.
     *
     * @var array
     */
    private $parts;

    /**
     * The parameters replacing the variable parts.
     *
     * @var array
     */
    private $parameters;

    /**
     * Set up a path with the given route name, parts and parameters.
     *
     * @param string    $name
     * @param array     $parts
     * @param array     $parameters
     */
    public function __construct(string $name, array $parts, array $parameters)
    {
        $this->name = $name;
        $this->parts = $parts;
        $this->parameters = $parameters;
    }

    /**
     * Return the head and tail of the given list.
     *
     * @param array
     * @return array
     */
    private function split(array $list): array
    {
        return [array_shift($list), $list];
    }

    /**
     * Return the path value by recursively merging the parts, replacing the
     * variable ones with parameters. Unfortunately can't use __toString because
     * it may throw exceptions.
     *
     * @return string
     * @throws \Ellipse\FastRoute\Exceptions\WrongParameterFormatException
     */
    public function value(): string
    {
        if (count($this->parts) > 0) {

            list($part, $parts) = $this->split($this->parts);

            if (is_array($part)) {

                list($parameter, $parameters) = $this->split($this->parameters);

                if (preg_match('~^' . $part[1] . '$~', (string) $parameter) !== 0) {

                    return $parameter . (new Path($this->name, $parts, $parameters))->value();

                }

                throw new WrongParameterFormatException($parameter, $this->name, $part[0], $part[1]);

            }

            return $part . (new Path($this->name, $parts, $this->parameters))->value();

        }

        return '';
    }
}
