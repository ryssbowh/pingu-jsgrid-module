<?php

namespace Modules\JsGrid\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Asset;

class JsGridServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->registerFactories();
        $this->registerAssets();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('jsgrid.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'jsgrid'
        );
    }

    public function registerAssets()
    {
        Asset::container('modules')->add('jsgrid-js', 'modules/JsGrid/js/JsGrid.js');
        Asset::container('modules')->add('jsgrid-css', 'modules/JsGrid/css/JsGrid.css');
    }

    /**
     * Register views.
     *
     * @return void
     */
    // public function registerViews()
    // {
    //     $themePaths = $this->app->make('view.finder')->getThemesPublishPaths('jsgrid');

    //     $sourcePath = __DIR__.'/../Resources/views';

    //     foreach($themePaths as $path => $namespace){
    //         $this->publishes([
    //             $sourcePath => $path
    //         ],$namespace);
    //     }
        
    //     $this->loadViewsFrom(array_merge(array_map(function ($path) {
    //         return $path . '/modules/jsgrid';
    //     }, \Config::get('view.paths')), [$sourcePath]), 'jsgrid');
    // }
    
    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/jsgrid');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ],'views');

        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/jsgrid';
        }, \Config::get('view.paths')), [$sourcePath]), 'jsgrid');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/jsgrid');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'jsgrid');
        } else {
            $this->loadTranslationsFrom(__DIR__ .'/../Resources/lang', 'jsgrid');
        }
    }

    /**
     * Register an additional directory of factories.
     * 
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production')) {
            app(Factory::class)->load(__DIR__ . '/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
