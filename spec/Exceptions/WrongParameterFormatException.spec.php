<?php

use Ellipse\FastRoute\Exceptions\FastRouteExceptionInterface;
use Ellipse\FastRoute\Exceptions\WrongParameterFormatException;

describe('WrongParameterFormatException', function () {

    it('should implement FastRouteExceptionInterface', function () {

        $test = new WrongParameterFormatException('value', 'name', 'parameter', 'format');

        expect($test)->toBeAnInstanceOf(FastRouteExceptionInterface::class);

    });

});
