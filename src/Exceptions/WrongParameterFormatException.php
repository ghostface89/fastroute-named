<?php declare(strict_types=1);

namespace Ellipse\FastRoute\Exceptions;

use RuntimeException;

class WrongParameterFormatException extends RuntimeException implements FastRouteExceptionInterface
{
    public function __construct(string $format, string $value)
    {
        $msg = "The given value '%s' does not match the route parameter format '%s'";

        parent::__construct(sprintf($msg, $value, $format));
    }
}
