<?php

/*
 * This file is part of Laravel SMSFactor.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\SMSFactor\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * This is the smsfactor facade class.
 *
 * @author ilgala
 */
class SMSFactor extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'smsfactor';
    }
}
