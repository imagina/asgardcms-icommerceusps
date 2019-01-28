<?php

namespace Modules\IcommerceUsps\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Core\Traits\CanPublishConfiguration;
use Modules\Core\Events\BuildingSidebar;
use Modules\Core\Events\LoadingBackendTranslations;
use Modules\IcommerceUsps\Events\Handlers\RegisterIcommerceUspsSidebar;

class IcommerceUspsServiceProvider extends ServiceProvider
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
    $this->app['events']->listen(BuildingSidebar::class, RegisterIcommerceUspsSidebar::class);
    
    $this->app['events']->listen(LoadingBackendTranslations::class, function (LoadingBackendTranslations $event) {
      $event->load('configusps', array_dot(trans('icommerceusps::configusps')));
      // append translations
      
    });
  }
  
  public function boot()
  {
    $this->publishConfig('IcommerceUsps', 'permissions');
    $this->publishConfig('IcommerceUsps', 'config');
    $this->publishConfig('IcommerceUsps', 'settings');
    
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
      'Modules\IcommerceUsps\Repositories\ConfiguspsRepository',
      function () {
        $repository = new \Modules\IcommerceUsps\Repositories\Eloquent\EloquentConfiguspsRepository(new \Modules\IcommerceUsps\Entities\Configusps());
        
        if (!config('app.cache')) {
          return $repository;
        }
        
        return new \Modules\IcommerceUsps\Repositories\Cache\CacheConfiguspsDecorator($repository);
      }
    );
// add bindings
  
  }
}
