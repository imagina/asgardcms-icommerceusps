<?php

namespace Modules\Icommerceusps\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\Icommerceusps\Events\Handlers\RegisterIcommerceuspsSidebar;

class IcommerceuspsServiceProvider extends ServiceProvider
{
    use CanPublishConfiguration;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBindings();
        $this->app['events']->listen(BuildingSidebar::class, RegisterIcommerceuspsSidebar::class);

        $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
            $event->load('icommerceusps', array_dot(trans('icommerceusps::icommerceusps')));
            // append translations

        });
    }

    public function boot()
    {
        $this->publishConfig('icommerceusps', 'permissions');
        $this->publishConfig('icommerceusps', 'config');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    private function registerBindings()
    {
        $this->app->bind(
            'Modules\Icommerceusps\Repositories\IcommerceuspsRepository',
            function () {
                $repository = new \Modules\Icommerceusps\Repositories\Eloquent\EloquentIcommerceuspsRepository(new \Modules\Icommerceusps\Entities\Icommerceusps());

                if (! config('app.cache')) {
                    return $repository;
                }

                return new \Modules\Icommerceusps\Repositories\Cache\CacheIcommerceuspsDecorator($repository);
            }
        );
// add bindings

    }
}
