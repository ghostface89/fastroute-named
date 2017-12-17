<?php

use FastRoute\RouteParser;

use Ellipse\FastRoute\Path;
use Ellipse\FastRoute\Exceptions\WrongParameterFormatException;

describe('Path', function () {

    beforeEach(function () {

        $this->parser = new RouteParser\Std;

    });

    describe('->value()', function () {

        context('when all the parameters are matching the pattern parts format', function () {

            it('should return an path built with the given parameters', function () {

                $pattern = '/pattern/{p1}/{p2}';

                $parts = current($this->parser->parse($pattern));

                $path = new Path('name', $parts, ['v1', 'v2']);

                $test = $path->value();

                expect($test)->toEqual('/pattern/v1/v2');

            });

            it('should cast the parameters as string', function () {

                $pattern = '/pattern/{p1}/{p2}';

                $parts = current($this->parser->parse($pattern));

                $path = new Path('name', $parts, ['v1', 2]);

                $test = $path->value();

                expect($test)->toEqual('/pattern/v1/2');

            });

        });

        context('when a parameter does not match a pattern part format', function () {

            it('should throw a WrongParameterFormatException', function () {

                $pattern = '/pattern/{p1:[0-9]+}/{p2:[0-9]+}';

                $parts = current($this->parser->parse($pattern));

                $path = new Path('name', $parts, [1, 'v2']);

                $test = function () use ($path) { $path->value(); };

                $exception = new WrongParameterFormatException('v2', 'name', 'p2', '[0-9]+');

                expect($test)->toThrow($exception);

            });

        });

    });

});
