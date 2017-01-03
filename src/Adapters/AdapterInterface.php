<?php

/*
 * This file is part of Laravel SMSFactor.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\LaravelSMSFactor\Adapters;

use IlGala\LaravelSMSFactor\Exceptions\HttpException;

/**
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
interface AdapterInterface
{

    /**
     * @param string $url
     *
     * @throws HttpException
     *
     * @return string
     */
    public function get($url);

    /**
     * @param string $url
     *
     * @throws HttpException
     */
    public function delete($url);

    /**
     * @param string       $url
     * @param array|string $content
     *
     * @throws HttpException
     *
     * @return string
     */
    public function put($url, $content = '');

    /**
     * @param string       $url
     * @param array|string $content
     *
     * @throws HttpException
     *
     * @return string
     */
    public function post($url, $content = '');

    /**
     * @return array|null
     */
    public function getLatestResponseHeaders();
}
