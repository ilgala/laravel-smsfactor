<?php

/*
 * This file is part of Laravel SMSFactor.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use IlGala\LaravelSMSFactor\Adapters\BuzzAdapter;
use GrahamCampbell\Manager\ConnectorInterface;
use InvalidArgumentException;

/**
 * This is the buzz connector class.
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class BuzzConnector implements ConnectorInterface
{

    /**
     * Establish an adapter connection.
     *
     * @param string[] $config
     *
     * @return \IlGala\LaravelSMSFactor\Adapters\BuzzAdapter
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
            throw new InvalidArgumentException('The buzz connector requires configuration.');
        }
        return array_only($config, ['username', 'password', 'accept']);
    }

    /**
     * Get the buzz adapter.
     *
     * @param string[] $config
     *
     * @return \IlGala\LaravelSMSFactor\Adapters\BuzzAdapter
     */
    protected function getAdapter(array $config)
    {
        return new BuzzAdapter($config['username'], $config['password'], $config['accept']);
    }

}
