<?php

/*
 * This file is part of Laravel SMSFactor.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\Tests\SMSFactor;

use IlGala\SMSFactor\SMSFactor;
use IlGala\SMSFactor\Connectors\ConnectionFactory as AdapterFactory;
use IlGala\SMSFactor\SMSFactorFactory;
use IlGala\SMSFactor\SMSFactorManager;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;

/**
 * This is the service provider test class.
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use ServiceProviderTrait;

    public function testAdapterFactoryIsInjectable()
    {
        $this->assertIsInjectable(AdapterFactory::class);
    }

    public function testSMSFactorFactoryIsInjectable()
    {
        $this->assertIsInjectable(SMSFactorFactory::class);
    }

    public function testSMSFactorManagerIsInjectable()
    {
        $this->assertIsInjectable(SMSFactorManager::class);
    }

    public function testBindings()
    {
        $this->assertIsInjectable(SMSFactor::class);

        $original = $this->app['smsfactor.connection'];

        $this->app['smsfactor']->reconnect();

        $new = $this->app['smsfactor.connection'];

        $this->assertNotSame($original, $new);

        $this->assertEquals($original, $new);
    }
}
