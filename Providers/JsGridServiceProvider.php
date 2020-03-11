<?php

namespace Pingu\Jsgrid\Providers;

use Asset;
use Illuminate\Database\Eloquent\Factory;
use Pingu\Core\Support\ModuleServiceProvider;
use Pingu\JsGrid\JsGrid;

class JsGridServiceProvider extends ModuleServiceProvider
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
        $this->registerFactories();
        $this->registerAssets();
        $this->loadModuleViewsFrom(__DIR__ . '/../Resources/views', 'jsgrid');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'jsgrid'
        );
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('jsgrid.php')
        ], 'jsgrid-config');
    }

    public function registerAssets()
    {
        Asset::container('modules')->add('jsgrid-js', 'module-assets/Jsgrid.js');
        Asset::container('modules')->add('jsgrid-css', 'module-assets/Jsgrid.css');
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
