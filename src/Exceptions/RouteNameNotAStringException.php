<?php declare(strict_types=1);

namespace Ellipse\FastRoute\Exceptions;

use InvalidArgumentException;

class RouteNameNotAStringException extends InvalidArgumentException implements FastRouteExceptionInterface
{
    public function __construct($name)
    {
        $template = "Route names must be strings. %s given.";

        $msg = sprintf($template, is_object($name) ? get_class($name) : gettype($name));

        parent::__construct(sprintf($msg, $name));
    }
}
