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

use IlGala\SMSFactor\Adapters\BuzzAdapter;
use IlGala\SMSFactor\Connectors\BuzzConnector;
use GrahamCampbell\TestBench\AbstractTestCase;

/**
 * This is the buzz connector test class.
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class BuzzConnectorTest extends AbstractTestCase
{
    public function testConnectStandard()
    {
        $connector = $this->getBuzzConnector();

        $return = $connector->connect(['username' => 'your-username', 'password' => 'your-password', 'accept' => 'application/json']);

        $this->assertInstanceOf(BuzzAdapter::class, $return);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The buzz connector requires configuration.
     */
    public function testConnectWithoutToken()
    {
        $connector = $this->getBuzzConnector();

        $connector->connect([]);
    }

    protected function getBuzzConnector()
    {
        return new BuzzConnector();
    }
}
