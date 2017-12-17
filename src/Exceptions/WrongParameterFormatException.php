<?php declare(strict_types=1);

namespace Ellipse\FastRoute\Exceptions;

use RuntimeException;

class WrongParameterFormatException extends RuntimeException implements FastRouteExceptionInterface
{
    public function __construct(string $value, string $name, string $part, string $format)
    {
        $template = "The value '%s' does not match the format of the route '%s' '%s' parameter ('%s')";

        $msg = sprintf($template, $value, $name, $part, $format);

        parent::__construct($msg);
    }
}
