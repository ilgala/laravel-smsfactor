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

use IlGala\SMSFactor\Adapter\AdapterInterface;
use IlGala\SMSFactor\SMSFactor;
use IlGala\SMSFactor\Adapters\ConnectionFactory;
use IlGala\SMSFactor\SMSFactorFactory;
use IlGala\SMSFactor\SMSFactorManager;
use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;
use Mockery;

/**
 * This is the digitalocean factory test class.
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class SMSFactorFactoryTest extends AbstractTestBenchTestCase
{

    public function testMake()
    {
        $config = ['driver' => 'buzz', 'username' => 'your-username', 'password' => 'your-password', 'accept' => 'application/json'];
        $manager = Mockery::mock(SMSFactorManager::class);
        $factory = $this->getMockedFactory($config, $manager);
        $return = $factory->make($config, $manager);
        $this->assertInstanceOf(SMSFactor::class, $return);
    }

    public function testAdapter()
    {
        $factory = $this->getSMSFactorFactory();
        $config = ['driver' => 'guzzlehttp', 'username' => 'your-username', 'password' => 'your-password', 'accept' => 'application/json'];
        $factory->getAdapter()->shouldReceive('make')->once()
                ->with($config)->andReturn(Mockery::mock(AdapterInterface::class));
        $return = $factory->createAdapter($config);
        $this->assertInstanceOf(AdapterInterface::class, $return);
    }

    protected function getSMSFactorFactory()
    {
        $adapter = Mockery::mock(ConnectionFactory::class);
        return new SMSFactorFactory($adapter);
    }

    protected function getMockedFactory($config, $manager)
    {
        $adapter = Mockery::mock(ConnectionFactory::class);
        $mock = Mockery::mock(SMSFactorFactory::class . '[createAdapter]', [$adapter]);
        $mock->shouldReceive('createAdapter')->once()
                ->with($config)->andReturn(Mockery::mock(AdapterInterface::class));
        return $mock;
    }

}
