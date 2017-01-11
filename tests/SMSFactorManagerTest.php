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
use IlGala\SMSFactor\SMSFactorFactory;
use IlGala\SMSFactor\SMSFactorManager;
use GrahamCampbell\TestBench\AbstractTestCase as AbstractTestBenchTestCase;
use Illuminate\Contracts\Config\Repository;
use Mockery;

/**
 * This is the smsfactor manager test class.
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class SMSFactorManagerTest extends AbstractTestBenchTestCase
{

    public function testCreateConnection()
    {
        $config = ['username' => 'your-username', 'password' => 'your-password', 'accept' => 'application/json'];
        $manager = $this->getManager($config);
        $manager->getConfig()->shouldReceive('get')->once()
                ->with('smsfactor.default')->andReturn('main');
        $this->assertSame([], $manager->getConnections());
        $return = $manager->connection();
        $this->assertInstanceOf(SMSFactor::class, $return);
        $this->assertArrayHasKey('main', $manager->getConnections());
    }

    protected function getManager(array $config)
    {
        $repo = Mockery::mock(Repository::class);
        $factory = Mockery::mock(SMSFactorFactory::class);
        $manager = new SMSFactorManager($repo, $factory);
        $manager->getConfig()->shouldReceive('get')->once()
                ->with('smsfactor.connections')->andReturn(['main' => $config]);
        $config['name'] = 'main';
        $manager->getFactory()->shouldReceive('make')->once()
                ->with($config)->andReturn(Mockery::mock(SMSFactor::class));
        return $manager;
    }

}
