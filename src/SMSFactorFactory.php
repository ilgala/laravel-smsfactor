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

/**
 * This is the  SMSFactor factory class.
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class SMSFactorFactory
{

    /**
     * The adapter factory instance.
     *
     * @var \IlGala\SMSFactor\Adapters\ConnectionFactory
     */
    protected $adapter;

    /**
     * Create a new filesystem factory instance.
     *
     * @param \IlGala\SMSFactor\Adapters\ConnectionFactory $adapter
     *
     * @return void
     */
    public function __construct(AdapterFactory $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Make a new digitalocean client.
     *
     * @param string[] $config
     *
     * @return \IlGala\SMSFactor\SMSFactor
     */
    public function make(array $config)
    {
        $adapter = $this->createAdapter($config);
        return new SMSFactor($adapter, $config['accept']);
    }

    /**
     * Establish an adapter connection.
     *
     * @param array $config
     *
     * @return \IlGala\SMSFactor\Adapters\AdapterInterface
     */
    public function createAdapter(array $config)
    {
        return $this->adapter->make($config);
    }

    /**
     * Get the adapter factory instance.
     *
     * @return \IlGala\SMSFactor\Adapters\ConnectionFactory
     */
    public function getAdapter()
    {
        return $this->adapter;
    }
}
