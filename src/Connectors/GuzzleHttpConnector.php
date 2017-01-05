<?php

/*
 * This file is part of Laravel SMSFactor.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\LaravelSMSFactor\Connectors;

use IlGala\LaravelSMSFactor\Adapters\GuzzleHttpAdapter;
use GrahamCampbell\Manager\ConnectorInterface;
use InvalidArgumentException;

/**
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class GuzzleHttpConnector implements ConnectorInterface
{

    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \IlGala\LaravelSMSFactor\Adapters\GuzzleHttpAdapter
     */
    public function connect(array $config)
    {
        $config = $this->getConfig($config);
        return $this->getAdapter($config);
    }

    /**
     * Get the configuration.
     *
     * @param string[] $config
     *
     * @throws \InvalidArgumentException
     *
     * @return string[]
     */
    protected function getConfig(array $config)
    {
        if (!array_key_exists('username', $config) || !array_key_exists('password', $config) || !array_key_exists('accept', $config)) {
            throw new InvalidArgumentException('The guzzlehttp connector requires configuration.');
        }
        return array_only($config, ['username', 'password', 'accept']);
    }

    /**
     * Get the guzzlehttp adapter.
     *
     * @param string[] $config
     *
     * @return \IlGala\LaravelSMSFactor\Adapters\GuzzleHttpAdapter
     */
    protected function getAdapter(array $config)
    {
        return new GuzzleHttpAdapter($config['username'], $config['password'], $config['accept']);
    }
}
