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

use IlGala\LaravelSMSFactor\Adapters\GuzzleAdapter;
use GrahamCampbell\Manager\ConnectorInterface;
use InvalidArgumentException;

/**
 * This is the guzzle connector class.
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class GuzzleConnector
{

    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \IlGala\LaravelSMSFactor\Adapters\GuzzleAdapter
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
            throw new InvalidArgumentException('The guzzle connector requires configuration.');
        }
        return array_only($config, ['username', 'password', 'accept']);
    }

    /**
     * Get the guzzle adapter.
     *
     * @param string[] $config
     *
     * @return \IlGala\LaravelSMSFactor\Adapters\GuzzleAdapter
     */
    protected function getAdapter(array $config)
    {
        return new GuzzleAdapter($config['username'], $config['password'], $config['accept']);
    }
}
