<?php

use Ellipse\FastRoute\Exceptions\FastRouteExceptionInterface;
use Ellipse\FastRoute\Exceptions\RouteNameNotMappedException;

describe('RouteNameNotMappedException', function () {

    it('should implement FastRouteExceptionInterface', function () {

        $test = new RouteNameNotMappedException('name');

        expect($test)->toBeAnInstanceOf(FastRouteExceptionInterface::class);

    });

});
