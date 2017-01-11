<?php

/*
 * This file is part of Laravel SMSFactor.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\SMSFactor;

use IlGala\SMSFactor\SMSFactor;
use IlGala\SMSFactor\Connectors\ConnectionFactory as AdapterFactory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * This is the SMSFactor service provider class.
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class SMSFactorServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->setupConfig();
    }

    /**
     * Setup the config.
     *
     * @return void
     */
    protected function setupConfig()
    {
        $source = realpath(__DIR__ . '/../config/smsfactor.php');
        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('smsfactor.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('smsfactor');
        }
        $this->mergeConfigFrom($source, 'smsfactor');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAdapterFactory();
        $this->registerSMSFactorFactory();
        $this->registerManager();
        $this->registerBindings();
    }

    /**
     * Register the adapter factory class.
     *
     * @return void
     */
    protected function registerAdapterFactory()
    {
        $this->app->singleton('smsfactor.adapterfactory', function () {
            return new AdapterFactory();
        });
        $this->app->alias('smsfactor.adapterfactory', AdapterFactory::class);
    }

    /**
     * Register the smsfactor factory class.
     *
     * @return void
     */
    protected function registerSMSFactorFactory()
    {
        $this->app->singleton('smsfactor.factory', function (Container $app) {
            $adapter = $app['smsfactor.adapterfactory'];
            return new SMSFactorFactory($adapter);
        });
        $this->app->alias('smsfactor.factory', SMSFactorFactory::class);
    }

    /**
     * Register the manager class.
     *
     * @return void
     */
    protected function registerManager()
    {
        $this->app->singleton('smsfactor', function (Container $app) {
            $config = $app['config'];
            $factory = $app['smsfactor.factory'];
            return new SMSFactorManager($config, $factory);
        });
        $this->app->alias('smsfactor', SMSFactorManager::class);
    }

    /**
     * Register the bindings.
     *
     * @return void
     */
    protected function registerBindings()
    {
        $this->app->bind('smsfactor.connection', function (Container $app) {
            $manager = $app['smsfactor'];
            return $manager->connection();
        });
        $this->app->alias('smsfactor.connection', SMSFactor::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return string[]
     */
    public function provides()
    {
        return [
            'smsfactor.adapterfactory',
            'smsfactor.factory',
            'smsfactor',
            'smsfactor.connection',
        ];
    }

}
