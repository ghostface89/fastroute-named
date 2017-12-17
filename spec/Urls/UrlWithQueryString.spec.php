<?php

use function Eloquent\Phony\Kahlan\mock;

use Ellipse\FastRoute\Urls\UrlInterface;
use Ellipse\FastRoute\Urls\UrlWithQueryString;

describe('UrlWithQueryString', function () {

    beforeEach(function () {

        $this->delegate = mock(UrlInterface::class);

        $this->delegate->__toString->returns('url');

    });

    it('it should implement UrlInterface', function () {

        $test = new UrlWithQueryString([], $this->delegate->get());

        expect($test)->toBeAnInstanceOf(UrlInterface::class);

    });

    describe('->__toString()', function () {

        context('when there is not query parameters', function () {

            it('should proxy the delegate', function () {

                $url = new UrlWithQueryString([], $this->delegate->get());

                $test = (string) $url;

                expect($test)->toEqual('url');

            });

        });

        context('when there is query parameters', function () {

            it('should append the query parameters to the url returned by the delegate', function () {

                $url = new UrlWithQueryString(['q1' => 'v1', 'q2' => 'v2'], $this->delegate->get());

                $test = (string) $url;

                expect($test)->toEqual('url?q1=v1&q2=v2');

            });

        });

    });

});
