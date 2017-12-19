<?php

use FastRoute\RouteParser;

use Ellipse\FastRoute\Map;
use Ellipse\FastRoute\RoutePattern;
use Ellipse\FastRoute\Exceptions\RouteNameNotMappedException;
use Ellipse\FastRoute\Exceptions\RouteNameAlreadyMappedException;

describe('Map', function () {

    beforeEach(function () {

        $this->map = new Map;

    });

    describe('->pattern()', function () {

        context('when the given name is associated to a route pattern', function () {

            it('should return a new RoutePattern from the pattern associated with the given name', function () {

                $parser = new RouteParser\Std;

                $this->map->associate('name', '/pattern');

                $test = $this->map->pattern('name');

                $pattern = new RoutePattern('name', $parser->parse('/pattern'));

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

        context('when the map prefix is empty', function () {

            beforeEach(function () {

                $this->map->associate('name', '/pattern');

            });

            context('when the given name is not already associated to a route pattern', function () {

                it('should associate the given name to the given route pattern', function () {

                    $test = function () { $this->map->pattern('name'); };

                    expect($test)->not->toThrow();

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

        context('when the map prefix is not empty', function () {

            beforeEach(function () {

                $this->map->addPrefix('prefix');

                $this->map->associate('name', '/pattern');

            });

            context('when the prefixed name is not already associated to a route pattern', function () {

                it('should associate the prefixed name to the given route pattern', function () {

                    $test = function () { $this->map->pattern('prefix.name'); };

                    expect($test)->not->toThrow();

                });

            });

            context('when the prefixed name is already associated to a route pattern', function () {

                it('should throw a RouteNameAlreadyMappedException', function () {

                    $test = function () { $this->map->associate('name', '/pattern'); };

                    $exception = new RouteNameAlreadyMappedException('prefix.name');

                    expect($test)->toThrow($exception);

                });

            });

        });

    });

    describe('->withPrefix()', function () {

        it('should merge multiple prefixes spaced by a dot', function () {

            $this->map->addPrefix('prefix1');
            $this->map->addPrefix('prefix2');

            $this->map->associate('name', '/pattern');

            $test = function () { $this->map->pattern('prefix1.prefix2.name'); };

            expect($test)->not->toThrow();

        });

    });

    describe('->removePrefix()', function () {

        context('when there is only one prefix', function () {

            it('should use an empty prefix', function () {

                $this->map->addPrefix('prefix');
                $this->map->removePrefix();

                $this->map->associate('name', '/pattern');

                $test = function () { $this->map->pattern('name'); };

                expect($test)->not->toThrow();

            });

        });

        context('when there is multiple prefixes', function () {

            it('should remove the last one', function () {

                $this->map->addPrefix('prefix1');
                $this->map->addPrefix('prefix2');
                $this->map->removePrefix();

                $this->map->associate('name', '/pattern');

                $test = function () { $this->map->pattern('prefix1.name'); };

                expect($test)->not->toThrow();

            });

        });

        context('when the last prefix is empty', function () {

            it('should use the same prefix', function () {

                $this->map->addPrefix('prefix');
                $this->map->addPrefix('');
                $this->map->removePrefix();

                $this->map->associate('name', '/pattern');

                $test = function () { $this->map->pattern('prefix.name'); };

                expect($test)->not->toThrow();

            });

        });

    });

});
