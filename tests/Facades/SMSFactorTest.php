<?php

/*
 * This file is part of Laravel SMSFactor.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\Tests\SMSFactor\Facades;

use IlGala\SMSFactor\SMSFactorManager;
use IlGala\SMSFactor\Facades\SMSFactor;
use GrahamCampbell\TestBenchCore\FacadeTrait;
use IlGala\Tests\SMSFactor\AbstractTestCase;

/**
 * This is the smsfactor facade test class.
 *
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class SMSFactorTest extends AbstractTestCase
{

    use FacadeTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'smsfactor';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return SMSFactor::class;
    }

    /**
     * Get the facade root.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return SMSFactorManager::class;
    }

}
