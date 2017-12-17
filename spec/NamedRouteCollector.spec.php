<?php

use function Eloquent\Phony\Kahlan\mock;
use function Eloquent\Phony\Kahlan\partialMock;

use FastRoute\RouteCollector;
use FastRoute\RouteParser;

use Ellipse\FastRoute\NamedRouteCollector;
use Ellipse\FastRoute\RoutePattern;
use Ellipse\FastRoute\Exceptions\RouteNameNotMappedException;
use Ellipse\FastRoute\Exceptions\RouteNameAlreadyMappedException;

describe('NamedRouteCollector', function () {

    beforeEach(function () {

        $this->delegate = mock(RouteCollector::class);

        $this->collection = new NamedRouteCollector($this->delegate->get());

    });

    describe('->pattern()', function () {

        context('when the given name is associated with a route pattern', function () {

            it('should return a new RoutePattern', function () {

                $parser = new RouteParser\Std;

                $signatures = $parser->parse('pattern');

                $this->collection->addRoute('name', 'GET', 'pattern', 'handler');

                $test = $this->collection->pattern('name');

                $pattern = new RoutePattern('name', $signatures);

                expect($test)->toEqual($pattern);

            });

        });

        context('when the given name is associated with a route pattern', function () {

            it('should throw a RouteNameNotMappedException', function () {

                $test = function () {

                    $this->collection->pattern('name');

                };

                $exception = new RouteNameNotMappedException('name');

                expect($test)->toThrow($exception);

            });

        });

    });

    describe('->addRoute()', function () {

        context('when the given name is empty', function () {

            it('should proxy the delegate', function () {

                $this->collection->addRoute('name', 'GET', 'pattern', 'handler');

                $this->delegate->addRoute->calledWith('GET', 'pattern', 'handler');

            });

        });

        context('when the given name is not empty', function () {

            context('when the given name is not already associated with a route pattern', function () {

                it('should proxy the delegate', function () {

                    $this->collection->addRoute('name', 'GET', 'pattern', 'handler');

                    $this->delegate->addRoute->calledWith('GET', 'pattern', 'handler');

                });

            });

            context('when the given name is already associated with a route pattern', function () {

                it('should throw a RouteNameAlreadyMappedException', function () {

                    $this->collection->addRoute('name', 'GET', 'pattern', 'handler');

                    $test = function () {

                        $this->collection->addRoute('name', 'GET', 'pattern', 'handler');

                    };

                    $exception = new RouteNameAlreadyMappedException('name');

                    expect($test)->toThrow($exception);

                });

            });

        });

        describe('->getData()', function () {

            it('should proxy the delegate', function () {

                $data = ['route1' => 'data1', 'route2' => 'data2'];

                $this->delegate->getData->returns($data);

                $test = $this->collection->getData();

                expect($test)->toEqual($data);

            });

        });

    });

    describe('shortcuts', function () {

        beforeEach(function () {

            $this->collection = partialMock(NamedRouteCollector::class, [
                $this->delegate->get(),
            ]);

        });

        describe('->get()', function () {

            it('should proxy the ->addRoute() method with the GET method', function () {

                $collection = $this->collection->get();

                $collection->get('name', 'pattern', 'handler');

                $this->collection->addRoute->calledWith('name', 'GET', 'pattern', 'handler');

            });

        });

        describe('->post()', function () {

            it('should proxy the ->addRoute() method with the POST method', function () {

                $collection = $this->collection->get();

                $collection->post('name', 'pattern', 'handler');

                $this->collection->addRoute->calledWith('name', 'POST', 'pattern', 'handler');

            });

        });

        describe('->put()', function () {

            it('should proxy the ->addRoute() method with the PUT method', function () {

                $collection = $this->collection->get();

                $collection->put('name', 'pattern', 'handler');

                $this->collection->addRoute->calledWith('name', 'PUT', 'pattern', 'handler');

            });

        });

        describe('->delete()', function () {

            it('should proxy the ->addRoute() method with the DELETE method', function () {

                $collection = $this->collection->get();

                $collection->delete('name', 'pattern', 'handler');

                $this->collection->addRoute->calledWith('name', 'DELETE', 'pattern', 'handler');

            });

        });

        describe('->patch()', function () {

            it('should proxy the ->addRoute() method with the PATCH method', function () {

                $collection = $this->collection->get();

                $collection->patch('name', 'pattern', 'handler');

                $this->collection->addRoute->calledWith('name', 'PATCH', 'pattern', 'handler');

            });

        });

        describe('->head()', function () {

            it('should proxy the ->addRoute() method with the HEAD method', function () {

                $collection = $this->collection->get();

                $collection->head('name', 'pattern', 'handler');

                $this->collection->addRoute->calledWith('name', 'HEAD', 'pattern', 'handler');

            });

        });

    });

});
