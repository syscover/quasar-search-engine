<?php namespace Quasar\Admin;

use Illuminate\Support\ServiceProvider;
use Laravel\Scout\EngineManager;
use Quasar\SearchEngine\Engines\SearchEngine;

class SearchEngineServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
        // register routes
        // $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        
        // register translations
        //  $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'admin');

        // register migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // register seeds
        $this->publishes([
            __DIR__ . '/../../database/seeds/' => base_path('/database/seeds')
        ], 'seeds');

        // register config
        $this->publishes([
            __DIR__ . '/../../config/quasar-search-engine.php' => config_path('quasar-search-engine.php')
        ], 'config');

        resolve(EngineManager::class)->extend('quasar-search', function () {
            return new SearchEngine;
        });

        // register events and listener predefined
        // $this->app->register(AdminEventServiceProvider::class);
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
        //
	}
}
