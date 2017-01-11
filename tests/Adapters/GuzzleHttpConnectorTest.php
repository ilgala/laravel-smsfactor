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

use IlGala\SMSFactor\Adapter\GuzzleHttpAdapter;
use IlGala\SMSFactor\Connectors\GuzzleHttpConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the guzzlehttp connector test class.
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class GuzzleHttpConnectorTest extends AbstractTestCase
{

    public function testConnectStandard()
    {
        $connector = $this->getGuzzleHttpConnector();
        $return = $connector->connect(['token' => 'your-token']);
        $this->assertInstanceOf(GuzzleHttpAdapter::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The guzzlehttp connector requires configuration.
     */
    public function testConnectWithoutTokent()
    {
        $connector = $this->getGuzzleHttpConnector();
        $connector->connect([]);
    }

    protected function getGuzzleHttpConnector()
    {
        return new GuzzleHttpConnector();
    }

}
