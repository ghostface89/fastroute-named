<?php

use function Eloquent\Phony\Kahlan\mock;
use function Eloquent\Phony\Kahlan\partialMock;

use FastRoute\RouteCollector;
use FastRoute\RouteParser;

use Ellipse\FastRoute\NamedRouteCollector;
use Ellipse\FastRoute\RoutePattern;
use Ellipse\FastRoute\Exceptions\RouteNameNotAStringException;
use Ellipse\FastRoute\Exceptions\RouteNameNotMappedException;
use Ellipse\FastRoute\Exceptions\RouteNameAlreadyMappedException;

describe('NamedRouteCollector', function () {

    beforeEach(function () {

        $this->delegate = mock(RouteCollector::class);

        $this->collector = new NamedRouteCollector($this->delegate->get());

    });

    describe('->pattern()', function () {

        context('when the given name is associated with a route pattern', function () {

            it('should return a new RoutePattern', function () {

                $parser = new RouteParser\Std;

                $signatures = $parser->parse('pattern');

                $this->collector->addRoute('name', 'GET', 'pattern', 'handler');

                $test = $this->collector->pattern('name');

                $pattern = new RoutePattern('name', $signatures);

                expect($test)->toEqual($pattern);

            });

        });

        context('when the given name is associated with a route pattern', function () {

            it('should throw a RouteNameNotMappedException', function () {

                $test = function () {

                    $this->collector->pattern('name');

                };

                $exception = new RouteNameNotMappedException('name');

                expect($test)->toThrow($exception);

            });

        });

    });

    describe('->addRoute()', function () {

        context('when no name is given', function () {

            it('should proxy the delegate', function () {

                $this->collector->addRoute('GET', 'pattern', 'handler');

                $this->delegate->addRoute->calledWith('GET', 'pattern', 'handler');

            });

        });

        context('when a name is given', function () {

            context('when the given name is not a string', function () {

                it('should throw a NameNotAStringException', function () {

                    $test = function () {

                        $this->collector->addRoute(['name'], 'GET', 'pattern', 'handler');

                    };

                    $exception = new RouteNameNotAStringException(['name']);

                    expect($test)->toThrow($exception);

                });

            });

            context('when the given name is a string', function () {

                context('when the given name is empty', function () {

                    it('should proxy the delegate without mapping the name to the pattern', function () {

                        $this->collector->addRoute('', 'GET', 'pattern', 'handler');

                        $test = function () { $this->collector->pattern(''); };

                        $exception = new RouteNameNotMappedException('');

                        expect($test)->toThrow($exception);

                        $this->delegate->addRoute->calledWith('GET', 'pattern', 'handler');

                    });

                });

                context('when the given name is not empty', function () {

                    context('when the given name is not already associated with a route pattern', function () {

                        it('should proxy the delegate and map the name to the pattern', function () {

                            $this->collector->addRoute('name', 'GET', 'pattern', 'handler');

                            $test = function () { $this->collector->pattern('name'); };

                            expect($test)->not->toThrow();

                            $this->delegate->addRoute->calledWith('GET', 'pattern', 'handler');

                        });

                    });

                    context('when the given name is already associated with a route pattern', function () {

                        it('should throw a RouteNameAlreadyMappedException', function () {

                            $this->collector->addRoute('name', 'GET', 'pattern', 'handler');

                            $test = function () {

                                $this->collector->addRoute('name', 'GET', 'pattern', 'handler');

                            };

                            $exception = new RouteNameAlreadyMappedException('name');

                            expect($test)->toThrow($exception);

                        });

                    });

                });

            });

        });

        describe('->getData()', function () {

            it('should proxy the delegate', function () {

                $data = ['route1' => 'data1', 'route2' => 'data2'];

                $this->delegate->getData->returns($data);

                $test = $this->collector->getData();

                expect($test)->toEqual($data);

            });

        });

    });

    describe('shortcuts', function () {

        beforeEach(function () {

            $this->collector = partialMock(NamedRouteCollector::class, [
                $this->delegate->get(),
            ]);

        });

        describe('->get()', function () {

            context('when no name is given', function () {

                it('should proxy the ->addRoute() method with the GET method', function () {

                    $collection = $this->collector->get();

                    $collection->get('pattern', 'handler');

                    $this->collector->addRoute->calledWith('GET', 'pattern', 'handler');

                });

            });

            context('when a name is given', function () {

                it('should proxy the ->addRoute() method with the name and GET method', function () {

                    $collection = $this->collector->get();

                    $collection->get('name', 'pattern', 'handler');

                    $this->collector->addRoute->calledWith('name', 'GET', 'pattern', 'handler');

                });

            });

        });

        describe('->post()', function () {

            context('when no name is given', function () {

                it('should proxy the ->addRoute() method with the POST method', function () {

                    $collection = $this->collector->get();

                    $collection->post('pattern', 'handler');

                    $this->collector->addRoute->calledWith('POST', 'pattern', 'handler');

                });

            });

            context('when a name is given', function () {

                it('should proxy the ->addRoute() method with the name and POST method', function () {

                    $collection = $this->collector->get();

                    $collection->post('name', 'pattern', 'handler');

                    $this->collector->addRoute->calledWith('name', 'POST', 'pattern', 'handler');

                });

            });

        });

        describe('->put()', function () {

            context('when no name is given', function () {

                it('should proxy the ->addRoute() method with the PUT method', function () {

                    $collection = $this->collector->get();

                    $collection->put('pattern', 'handler');

                    $this->collector->addRoute->calledWith('PUT', 'pattern', 'handler');

                });

            });

            context('when a name is given', function () {

                it('should proxy the ->addRoute() method with the name and PUT method', function () {

                    $collection = $this->collector->get();

                    $collection->put('name', 'pattern', 'handler');

                    $this->collector->addRoute->calledWith('name', 'PUT', 'pattern', 'handler');

                });

            });

        });

        describe('->delete()', function () {

            context('when no name is given', function () {

                it('should proxy the ->addRoute() method with the DELETE method', function () {

                    $collection = $this->collector->get();

                    $collection->delete('pattern', 'handler');

                    $this->collector->addRoute->calledWith('DELETE', 'pattern', 'handler');

                });

            });

            context('when a name is given', function () {

                it('should proxy the ->addRoute() method with the name and DELETE method', function () {

                    $collection = $this->collector->get();

                    $collection->delete('name', 'pattern', 'handler');

                    $this->collector->addRoute->calledWith('name', 'DELETE', 'pattern', 'handler');

                });

            });

        });

        describe('->patch()', function () {

            context('when no name is given', function () {

                it('should proxy the ->addRoute() method with the PATCH method', function () {

                    $collection = $this->collector->get();

                    $collection->patch('pattern', 'handler');

                    $this->collector->addRoute->calledWith('PATCH', 'pattern', 'handler');

                });

            });

            context('when a name is given', function () {

                it('should proxy the ->addRoute() method with the name and PATCH method', function () {

                    $collection = $this->collector->get();

                    $collection->patch('name', 'pattern', 'handler');

                    $this->collector->addRoute->calledWith('name', 'PATCH', 'pattern', 'handler');

                });

            });

        });

        describe('->head()', function () {

            context('when no name is given', function () {

                it('should proxy the ->addRoute() method with the HEAD method', function () {

                    $collection = $this->collector->get();

                    $collection->head('pattern', 'handler');

                    $this->collector->addRoute->calledWith('HEAD', 'pattern', 'handler');

                });

            });

            context('when a name is given', function () {

                it('should proxy the ->addRoute() method with the name and HEAD method', function () {

                    $collection = $this->collector->get();

                    $collection->head('name', 'pattern', 'handler');

                    $this->collector->addRoute->calledWith('name', 'HEAD', 'pattern', 'handler');

                });

            });

        });

    });

});
