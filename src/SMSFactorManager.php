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

use GrahamCampbell\Manager\AbstractManager;
use Illuminate\Contracts\Config\Repository;

/**
 * This is the SMSFactor manager class.
 *
 * @method mixed createAccount()
 * @method mixed credits()
 * @method mixed send()
 * @method mixed sendLists()
 * @method mixed delete()
 * @method mixed contactList()
 * @method mixed getContactList()
 * @method mixed deduplicate()
 * @method mixed deleteContact()
 * @method mixed getBlacklist()
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class SMSFactorManager extends AbstractManager
{

    /**
     * The factory instance.
     *
     * @var \IlGala\SMSFactor\SMSFactorFactory
     */
    protected $factory;

    /**
     * Create a new smsfactor manager instance.
     *
     * @param \Illuminate\Contracts\Config\Repository          $config
     * @param \IlGala\SMSFactor\SMSFactorFactory        $factory
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
     * @return \IlGala\SMSFactor\SMSFactor
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
     * @return \IlGala\SMSFactor\SMSFactorFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }
}
