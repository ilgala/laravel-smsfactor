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

use IlGala\SMSFactor\Adapter\GuzzleAdapter;
use IlGala\SMSFactor\Connectors\GuzzleConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the guzzle connector test class.
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class GuzzleConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getGuzzleConnector();

        $return = $connector->connect(['username' => 'your-username', 'password' => 'your-password', 'accept' => 'application/json']);

        $this->assertInstanceOf(GuzzleAdapter::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The guzzle connector requires configuration.
     */
    public function testConnectWithoutTokent()
    {
        $connector = $this->getGuzzleConnector();

        $connector->connect([]);
    }

    protected function getGuzzleConnector()
    {
        return new GuzzleConnector();
    }
}
