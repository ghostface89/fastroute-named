<?php

use function Eloquent\Phony\Kahlan\mock;

use FastRoute\RouteParser;

use Ellipse\FastRoute\Map;
use Ellipse\FastRoute\RoutePattern;
use Ellipse\FastRoute\Exceptions\RouteNameNotMappedException;
use Ellipse\FastRoute\Exceptions\RouteNameAlreadyMappedException;

describe('Map', function () {

    beforeEach(function () {

        $this->parser = mock(RouteParser\Std::class);

        $this->parser->parse->with('/pattern')->returns(['/pattern']);
        $this->parser->parse->with('/prefix/pattern')->returns(['/prefix/pattern']);
        $this->parser->parse->with('/prefix1/pattern')->returns(['/prefix1/pattern']);
        $this->parser->parse->with('/prefix1/prefix2/pattern')->returns(['/prefix1/prefix2/pattern']);

        allow(RouteParser\Std::class)->toBe($this->parser->get());

        $this->map = new Map;

    });

    describe('->pattern()', function () {

        context('when the given name is associated to a route pattern', function () {

            it('should return a new RoutePattern from the pattern associated with the given name', function () {

                $this->map->associate('name', '/pattern');

                $test = $this->map->pattern('name');

                $pattern = new RoutePattern('name', ['/pattern']);

                expect($test)->toEqual($pattern);

            });

        });

        context('when the given name is not associated to a route pattern', function () {

            it('should throw a RouteNameNotMappedException', function () {

                $test = function () { $this->map->pattern('name'); };

                $exception = new RouteNameNotMappedException('name');

                expect($test)->toThrow($exception);

            });

        });

    });

    describe('->associate()', function () {

        context('when the name is empty', function () {

            beforeEach(function () {

                $this->map->associate('', '/pattern');

            });

            it('should not be added to the map', function () {

                $test = function () { $this->map->pattern(''); };

                $exception = new RouteNameNotMappedException('');

                expect($test)->toThrow($exception);

            });

            it('should allow to add multiple anonymous route', function () {

                $test = function () { $this->map->associate('', '/pattern'); };

                expect($test)->not->toThrow();

            });

        });

        context('when the name is not empty', function () {

            beforeEach(function () {

                $this->map->associate('name', '/pattern');

            });

            context('when the given name is not already associated to a route pattern', function () {

                it('should associate the given name to the given route pattern', function () {

                    $test = $this->map->pattern('name');

                    $pattern = new RoutePattern('name', ['/pattern']);

                    expect($test)->toEqual($pattern);

                });

            });

            context('when the given name is already associated to a route pattern', function () {

                it('should throw a RouteNameAlreadyMappedException', function () {

                    $test = function () { $this->map->associate('name', '/pattern'); };

                    $exception = new RouteNameAlreadyMappedException('name');

                    expect($test)->toThrow($exception);

                });

            });

        });

    });

    describe('->addNamePrefix()', function () {

        it('should merge multiple name prefixes spaced by a dot', function () {

            $this->map->addNamePrefix('prefix1');
            $this->map->addNamePrefix('prefix2');

            $this->map->associate('name', '/pattern');

            $test = $this->map->pattern('prefix1.prefix2.name');

            $pattern = new RoutePattern('prefix1.prefix2.name', ['/pattern']);

            expect($test)->toEqual($pattern);

        });

        it('should not use empty name prefixes', function () {

            $this->map->addNamePrefix('prefix1');
            $this->map->addNamePrefix('');
            $this->map->addNamePrefix('prefix2');

            $this->map->associate('name', '/pattern');

            $test = $this->map->pattern('prefix1.prefix2.name');

            $pattern = new RoutePattern('prefix1.prefix2.name', ['/pattern']);

            expect($test)->toEqual($pattern);

        });

    });

    describe('->removeNamePrefix()', function () {

        context('when there is only one name prefix', function () {

            it('should use an empty name prefix', function () {

                $this->map->addNamePrefix('prefix');
                $this->map->removeNamePrefix();

                $this->map->associate('name', '/pattern');

                $test = $this->map->pattern('name');

                $pattern = new RoutePattern('name', ['/pattern']);

                expect($test)->toEqual($pattern);

            });

        });

        context('when there is multiple name prefixes', function () {

            it('should remove the last one', function () {

                $this->map->addNamePrefix('prefix1');
                $this->map->addNamePrefix('prefix2');
                $this->map->removeNamePrefix();

                $this->map->associate('name', '/pattern');

                $test = $this->map->pattern('prefix1.name');

                $pattern = new RoutePattern('prefix1.name', ['/pattern']);

                expect($test)->toEqual($pattern);

            });

        });

        context('when the last name prefix is empty', function () {

            it('should use the same name prefix', function () {

                $this->map->addNamePrefix('prefix');
                $this->map->addNamePrefix('');
                $this->map->removeNamePrefix();

                $this->map->associate('name', '/pattern');

                $test = $this->map->pattern('prefix.name');

                $pattern = new RoutePattern('prefix.name', ['/pattern']);

                expect($test)->toEqual($pattern);

            });

        });

    });

    describe('->addPatternPrefix()', function () {

        it('should merge multiple route pattern prefixes', function () {

            $this->map->addPatternPrefix('/prefix1');
            $this->map->addPatternPrefix('/prefix2');

            $this->map->associate('name', '/pattern');

            $test = $this->map->pattern('name');

            $pattern = new RoutePattern('name', ['/prefix1/prefix2/pattern']);

            expect($test)->toEqual($pattern);

        });

        it('should not use empty route pattern prefixes', function () {

            $this->map->addPatternPrefix('/prefix1');
            $this->map->addPatternPrefix('');
            $this->map->addPatternPrefix('/prefix2');

            $this->map->associate('name', '/pattern');

            $test = $this->map->pattern('name');

            $pattern = new RoutePattern('name', ['/prefix1/prefix2/pattern']);

            expect($test)->toEqual($pattern);

        });

    });

    describe('->removePatternPrefix()', function () {

        context('when there is only one route pattern prefix', function () {

            it('should use an empty route pattern prefix', function () {

                $this->map->addPatternPrefix('/prefix');
                $this->map->removePatternPrefix();

                $this->map->associate('name', '/pattern');

                $test = $this->map->pattern('name');

                $pattern = new RoutePattern('name', ['/pattern']);

                expect($test)->toEqual($pattern);

            });

        });

        context('when there is multiple route pattern prefixes', function () {

            it('should remove the last one', function () {

                $this->map->addPatternPrefix('/prefix1');
                $this->map->addPatternPrefix('/prefix2');
                $this->map->removePatternPrefix();

                $this->map->associate('name', '/pattern');

                $test = $this->map->pattern('name');

                $pattern = new RoutePattern('name', ['/prefix1/pattern']);

                expect($test)->toEqual($pattern);

            });

        });

        context('when the last route pattern prefix is empty', function () {

            it('should use the same route pattern prefix', function () {

                $this->map->addPatternPrefix('/prefix');
                $this->map->addPatternPrefix('');
                $this->map->removePatternPrefix();

                $this->map->associate('name', '/pattern');

                $test = $this->map->pattern('name');

                $pattern = new RoutePattern('name', ['/prefix/pattern']);

                expect($test)->toEqual($pattern);

            });

        });

    });

});
