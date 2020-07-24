<?php

namespace Laracasts\Cypress\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Route;
use Laracasts\Cypress\CypressServiceProvider;

class ExampleTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [CypressServiceProvider::class];
    }

    /**
     * @test
     * @environment-setup setUpProductionEnvironment
     *
     */
    public function it_does_not_expose_cypress_routes_in_production()
    {
        $this->routeNames()->each(
            fn($name) => $this->assertFalse(Route::has($name))
        );
    }

    /**
     * @test
     * @environment-setup setUpAcceptanceEnvironment
     */
    function it_exposes_cypress_routes_if_not_in_production()
    {
        $this->routeNames()->each(
            fn($name) => $this->assertTrue(Route::has($name))
        );
    }

    protected function routeNames()
    {
        return collect([
            'cypress.factory',
            'cypress.login',
            'cypress.logout',
            'cypress.artisan'
        ]);
    }

    protected function setUpAcceptanceEnvironment()
    {
        app()->detectEnvironment(fn() => 'acceptance');
    }

    protected function setUpProductionEnvironment()
    {
        app()->detectEnvironment(fn() => 'production');
    }
}