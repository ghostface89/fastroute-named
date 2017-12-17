<?php

use function Eloquent\Phony\Kahlan\mock;

use Ellipse\FastRoute\Urls\UrlInterface;
use Ellipse\FastRoute\Urls\UrlWithFragment;

describe('UrlWithFragment', function () {

    beforeEach(function () {

        $this->delegate = mock(UrlInterface::class);

        $this->delegate->__toString->returns('url');

    });

    it('it should implement UrlInterface', function () {

        $test = new UrlWithFragment('', $this->delegate->get());

        expect($test)->toBeAnInstanceOf(UrlInterface::class);

    });

    describe('->__toString()', function () {

        context('when the fragment is empty', function () {

            it('should proxy the delegate', function () {

                $url = new UrlWithFragment('', $this->delegate->get());

                $test = (string) $url;

                expect($test)->toEqual('url');

            });

        });

        context('when the fragment is not empty', function () {

            it('should append the fragment to the url returned by the delegate', function () {

                $url = new UrlWithFragment('fragment', $this->delegate->get());

                $test = (string) $url;

                expect($test)->toEqual('url#fragment');

            });

        });

    });

});
