<?php

use function Eloquent\Phony\Kahlan\mock;

use Ellipse\FastRoute\Url;
use Ellipse\FastRoute\Path;
use Ellipse\FastRoute\Urls\UrlInterface;

describe('Url', function () {

    beforeEach(function () {

        $this->path = mock(Path::class);

        $this->path->value->returns('url');

    });

    it('it should implement UrlInterface', function () {

        $test = new Url($this->path->get());

        expect($test)->toBeAnInstanceOf(UrlInterface::class);

    });

    describe('->__toString()', function () {

        context('when no query string is given', function () {

            context('when no fragment is given', function () {

                it('should proxy the delegate', function () {

                    $url = new Url($this->path->get());

                    $test = (string) $url;

                    expect($test)->toEqual('url');

                });

            });

            context('when a fragment is given', function () {

                it('should append the fragment to the delegate url', function () {

                    $url = new Url($this->path->get(), [], 'fragment');

                    $test = (string) $url;

                    expect($test)->toEqual('url#fragment');

                });

            });

        });

        context('when a query string is given', function () {

            context('when no fragment is given', function () {

                it('should append the query string to the delegate url', function () {

                    $url = new Url($this->path->get(), ['q1' => 'v1', 'q2' => 'v2']);

                    $test = (string) $url;

                    expect($test)->toEqual('url?q1=v1&q2=v2');

                });

            });

            context('when a fragment is given', function () {

                it('should append the fragment after the query string to the delegate url', function () {

                    $url = new Url($this->path->get(), ['q1' => 'v1', 'q2' => 'v2'], 'fragment');

                    $test = (string) $url;

                    expect($test)->toEqual('url?q1=v1&q2=v2#fragment');

                });

            });

        });

    });

});
