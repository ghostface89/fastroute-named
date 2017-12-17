<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

class Path
{
    private $parts;

    private $parameters;

    private $basepath;

    public function __construct(array $parts, arrar $parameters, string $basepath = '')
    {
        $this->parts = $parts;
        $this->parameters = $parameters;
        $this->basepath = $basepath;
    }

    public function value(): string
    {
        if (count($this->parts) > 0) {

            [$part, $parts] = $this->split($this->parts);
            [$parameter, $parameters] = $this->split($this->parameters);

            $format = $parts[1];

            if (preg_match('~^' . $format . '$~', $parameter) !== 0) {

                return (new Path($parts, $parameters, $this->basepath . $parameter))->value();

            }

            throw new WrongParameterFormatException($format, $parameter);

        }

        return $basepath;
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
}
