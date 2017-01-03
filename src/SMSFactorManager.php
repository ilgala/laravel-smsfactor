<?php

/*
 * This file is part of Laravel SMSFactor.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\LaravelSMSFactor;

use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;

/**
 * This is the SMSFactor manager class.
 * 
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class SMSFactorManager extends AbstractManager
{

    /**
     * The factory instance.
     *
     * @var \IlGala\LaravelSMSFactor\SMSFactorFactory
     */
    protected $factory;

    /**
     * Create a new digitalocean manager instance.
     *
     * @param \Illuminate\Contracts\Config\Repository          $config
     * @param \IlGala\LaravelSMSFactor\SMSFactorFactory        $factory
     *
     * @return void
     */
    public function __construct(Repository $config, SMSFactorFactory $factory)
    {
        parent::__construct($config);
        $this->factory = $factory;
    }

    /**
     * Create the connection instance.
     *
     * @param array $config
     *
     * @return \IlGala\LaravelSMSFactor\SMSFactor
     */
    protected function createConnection(array $config)
    {
        return $this->factory->make($config);
    }

    /**
     * Get the configuration name.
     *
     * @return string
     */
    protected function getConfigName()
    {
        return 'smsfactor';
    }

    /**
     * Get the factory instance.
     *
     * @return \IlGala\LaravelSMSFactor\SMSFactorFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

}
