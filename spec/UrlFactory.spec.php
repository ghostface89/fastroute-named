<?php

use function Eloquent\Phony\Kahlan\mock;

use Ellipse\FastRoute\Url;
use Ellipse\FastRoute\UrlFactory;
use Ellipse\FastRoute\RoutePattern;
use Ellipse\FastRoute\NamedRouteCollector;

describe('UrlFactory', function () {

    beforeEach(function () {

        $this->collector = mock(NamedRouteCollector::class);

        $this->factory = new UrlFactory($this->collector->get());

    });

    describe('->__invoke()', function () {

        beforeEach(function () {

            $this->pattern = mock(RoutePattern::class);

            $this->collector->pattern->with('name')->returns($this->pattern);

            $this->url = mock(Url::class);

            $this->url->__toString->returns('url');

        });

        context('when parameters, query parameters and a fragment are given', function () {

            it('should proxy the ->url() method of the RoutePattern returned by the named route collector', function () {

                $this->pattern->url->with(['p1', 'p2'], ['q1' => 'v1'], 'fragment')->returns($this->url);

                $test = ($this->factory)('name', ['p1', 'p2'], ['q1' => 'v1'], 'fragment');

                expect($test)->toEqual('url');

            });

        });

        context('when no parameters, query parameters or fragment are given', function () {

            it('should proxy the ->url() method of the RoutePattern with an empty parameters, query string and fragment', function () {

                $this->pattern->url->with([], [], '')->returns($this->url);

                $test = ($this->factory)('name');

                expect($test)->toEqual('url');

            });

        });

    });

});
