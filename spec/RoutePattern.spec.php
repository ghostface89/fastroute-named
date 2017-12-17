<?php

use FastRoute\RouteParser;

use Ellipse\FastRoute\Url;
use Ellipse\FastRoute\Path;
use Ellipse\FastRoute\RoutePattern;
use Ellipse\FastRoute\Exceptions\WrongNumberOfParametersException;

describe('RoutePattern', function () {

    beforeEach(function () {

        $this->parser = new RouteParser\Std;

        $signatures = $this->parser->parse('/pattern/{p1}[/{p2}]');

        $this->pattern = new RoutePattern('name', $signatures);

    });

    describe('->url()', function () {

        context('when less parameters than the number of fixed parts are given', function () {

            it('should throw a WrongNumberOfParametersException', function () {

                $test = function () {

                    $this->pattern->url([], ['q1' => 'v1'], 'fragment');

                };

                $exception = new WrongNumberOfParametersException('name', [1, 2], 0);

                expect($test)->toThrow($exception);

            });

        });

        context('when more parameters than the number of fixed parts are given', function () {

            it('should throw a WrongNumberOfParametersException', function () {

                $test = function () {

                    $this->pattern->url([1, 2, 3], ['q1' => 'v1'], 'fragment');

                };

                $exception = new WrongNumberOfParametersException('name', [1, 2], 3);

                expect($test)->toThrow($exception);

            });

        });

        context('when the same number of parameters than the fixed number of parts are given', function () {

            it('should return an url with a path containing the signature of the fixed parts', function () {

                $url = $this->pattern->url([1], ['q1' => 'v1'], 'fragment');

                $test = (string) $url;

                expect($test)->toEqual('/pattern/1?q1=v1#fragment');

            });

        });

        context('when the same number of parameters than the fixed + optional number of parts are given', function () {

            it('should return an url with a path containing the signature of the fixed + optional parts', function () {

                $url = $this->pattern->url([1, 2], ['q1' => 'v1'], 'fragment');

                $test = (string) $url;

                expect($test)->toEqual('/pattern/1/2?q1=v1#fragment');

            });

        });

        context('when no parameters, query string or fragment is given', function () {

            it('should return an url with empty parameters, query string and fragment', function () {

                $signatures = $this->parser->parse('/pattern');

                $pattern = new RoutePattern('name', $signatures);

                $url = $pattern->url();

                $test = (string) $url;

                expect($test)->toEqual('/pattern');

            });

        });

    });

});
