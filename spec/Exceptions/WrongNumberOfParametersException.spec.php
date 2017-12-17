<?php

use Ellipse\FastRoute\Exceptions\FastRouteExceptionInterface;
use Ellipse\FastRoute\Exceptions\WrongNumberOfParametersException;

describe('WrongNumberOfParametersException', function () {

    it('should implement FastRouteExceptionInterface', function () {

        $test = new WrongNumberOfParametersException('name', [1, 2], 0);

        expect($test)->toBeAnInstanceOf(FastRouteExceptionInterface::class);

    });

    describe('->getMessage()', function () {

        context('when exactly 0 parameter is required', function () {

            it('should contain \'no parameter\'', function () {

                $exception = new WrongNumberOfParametersException('name', [0], 1);

                $test = $exception->getMessage();

                expect($test)->toContain('no parameter');

            });

        });

        context('when exactly 1 parameter is required', function () {

            it('should contain \'exactly 1 parameter\'', function () {

                $exception = new WrongNumberOfParametersException('name', [1], 1);

                $test = $exception->getMessage();

                expect($test)->toContain('exactly 1 parameter');

            });

        });

        context('when exactly n parameter is required', function () {

            it('should contain \'exactly n parameters\'', function () {

                $exception = new WrongNumberOfParametersException('name', [2], 1);

                $test = $exception->getMessage();

                expect($test)->toContain('exactly 2 parameters');

            });

        });

        context('when there is multiple possible number of parameters', function () {

            it('should contain \'between n and m parameters\'', function () {

                $exception = new WrongNumberOfParametersException('name', [1, 2], 1);

                $test = $exception->getMessage();

                expect($test)->toContain('between 1 and 2 parameters');

            });

        });

    });

});
