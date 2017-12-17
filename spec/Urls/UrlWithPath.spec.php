<?php

use function Eloquent\Phony\Kahlan\mock;

use Ellipse\FastRoute\Path;
use Ellipse\FastRoute\Urls\UrlInterface;
use Ellipse\FastRoute\Urls\UrlWithPath;

describe('UrlWithPath', function () {

    beforeEach(function () {

        $path = mock(Path::class);

        $path->value->returns('path');

        $this->url = new UrlWithPath($path->get());

    });

    it('it should implement UrlInterface', function () {

        expect($this->url)->toBeAnInstanceOf(UrlInterface::class);

    });

    describe('->__toString()', function () {

        it('should return the path', function () {

            $test = (string) $this->url;

            expect($test)->toEqual('path');

        });

    });

});
