<?php

use Ellipse\FastRoute\Exceptions\FastRouteExceptionInterface;
use Ellipse\FastRoute\Exceptions\RouteNameNotAStringException;

describe('RouteNameNotAStringException', function () {

    it('should implement FastRouteExceptionInterface', function () {

        $test = new RouteNameNotAStringException('name');

        expect($test)->toBeAnInstanceOf(FastRouteExceptionInterface::class);

    });

});
