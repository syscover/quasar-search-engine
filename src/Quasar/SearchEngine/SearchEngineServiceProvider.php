<?php namespace Quasar\SearchEngine;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Scout\EngineManager;
use Quasar\SearchEngine\Engines\SearchEngine;
use Quasar\SearchEngine\Engines\Modes\ModeContainer;

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
            return new SearchEngine(app(ModeContainer::class));
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
        $engineNamespace    = 'Quasar\\SearchEngine\\Engines\\Modes\\';
        $mode               = $engineNamespace.Str::studly(strtolower(config('quasar-search-engine.mode')));
        $fallbackMode       = $engineNamespace.Str::studly(strtolower(config('quasar-search-engine.min_fulltext_search_fallback')));

        return new ModeContainer(new $mode(), new $fallbackMode());
	}
}
