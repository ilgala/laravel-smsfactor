<?php

/*
 * This file is part of Laravel SMSFactor.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\Tests\SMSFactor\Adapters;

use IlGala\SMSFactor\Adapter\AdapterInterface;
use IlGala\SMSFactor\Adapter\BuzzAdapter;
use IlGala\SMSFactor\Connectors\BuzzConnector;
use IlGala\SMSFactor\Connectors\ConnectionFactory;
use IlGala\SMSFactor\Connectors\GuzzleConnector;
use IlGala\SMSFactor\Connectors\GuzzleHttpConnector;
use IlGala\SMSFactor\Connectors\LocalConnector;
use GrahamCampbell\TestBench\AbstractTestCase;
use Mockery;

/**
 * This is the adapter connection factory test class.
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class ConnectionFactoryTest extends AbstractTestCase
{

    public function testMake()
    {
        $factory = $this->getMockedFactory();
        $return = $factory->make(['driver' => 'buzz', 'username' => 'your-username', 'password' => 'your-password', 'accept' => 'application/json']);
        $this->assertInstanceOf(AdapterInterface::class, $return);
    }

    public function createDataProvider()
    {
        return [
            ['buzz', BuzzConnector::class],
            ['guzzle', GuzzleConnector::class],
            ['guzzlehttp', GuzzleHttpConnector::class],
        ];
    }

    /**
     * @dataProvider createDataProvider
     */
    public function testCreateWorkingDriver($driver, $class)
    {
        $factory = $this->getConnectionFactory();
        $return = $factory->createConnector(['driver' => $driver]);
        $this->assertInstanceOf($class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage A driver must be specified.
     */
    public function testCreateEmptyDriverConnector()
    {
        $factory = $this->getConnectionFactory();
        $factory->createConnector([]);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unsupported driver [unsupported].
     */
    public function testCreateUnsupportedDriverConnector()
    {
        $factory = $this->getConnectionFactory();
        $factory->createConnector(['driver' => 'unsupported']);
    }

    protected function getConnectionFactory()
    {
        return new ConnectionFactory();
    }

    protected function getMockedFactory()
    {
        $mock = Mockery::mock(ConnectionFactory::class . '[createConnector]');
        $connector = Mockery::mock(LocalConnector::class);
        $connector->shouldReceive('connect')->once()
                ->with(['name' => 'main', 'driver' => 'buzz', 'username' => 'your-username', 'password' => 'your-password', 'accept' => 'application/json'])
                ->andReturn(Mockery::mock(BuzzAdapter::class));
        $mock->shouldReceive('createConnector')->once()
                ->with(['name' => 'main', 'driver' => 'buzz', 'username' => 'your-username', 'password' => 'your-password', 'accept' => 'application/json'])
                ->andReturn($connector);
        return $mock;
    }

}
