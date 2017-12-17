<?php

use Ellipse\FastRoute\Exceptions\FastRouteExceptionInterface;
use Ellipse\FastRoute\Exceptions\RouteNameAlreadyMappedException;

describe('RouteNameAlreadyMappedException', function () {

    it('should implement FastRouteExceptionInterface', function () {

        $test = new RouteNameAlreadyMappedException('name');

        expect($test)->toBeAnInstanceOf(FastRouteExceptionInterface::class);

    });

});
