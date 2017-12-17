<?php declare(strict_types=1);

namespace Ellipse\FastRoute\Exceptions;

use RuntimeException;

class WrongNumberOfParametersException extends RuntimeException implements FastRouteExceptionInterface
{
    public function __construct(string $name, array $allowed, int $given)
    {
        $template = "The route '%s' require %s, %s given";

        $min = min($allowed);
        $max = max($allowed);

        $expected_str = $this->format($min, $max);

        $msg = sprintf($template, $name, $expected_str, $given);

        parent::__construct($msg);
    }

    /**
     * Return a formatted expectation string for the given min and max
     * parameters.
     *
     * @param int $min
     * @param int $max
     * @return string
     */
    private function format(int $min, int $max): string
    {
        if ($min == $max) {

            if ($min == 0) {

                return 'no parameter';

            }

            elseif ($min == 1) {

                return 'exactly 1 parameter';

            } else {

                return implode(' ', ['exactly', $min, 'parameters']);

            }

        } else {

            return implode(' ', ['between', $min, 'and', $max, 'parameters']);

        }
    }
}
