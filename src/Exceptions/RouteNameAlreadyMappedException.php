<?php declare(strict_types=1);

namespace Ellipse\FastRoute\Exceptions;

use RuntimeException;

class RouteNameAlreadyMappedException extends RuntimeException implements FastRouteExceptionInterface
{
    public function __construct(string $name)
    {
        $msg = "The route name '%s' is already mapped";

        parent::__construct(sprintf($msg, $name));
    }
}
