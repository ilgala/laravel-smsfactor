<?php

/*
 * This file is part of Laravel SMSFactor.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\SMSFactor\Adapters;

use Buzz\Browser;
use Buzz\Client\Curl;
use Buzz\Client\FileGetContents;
use Buzz\Message\Response;
use IlGala\SMSFactor\Exceptions\HttpException;

/**
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class BuzzAdapter implements AdapterInterface
{

    /**
     * @var Browser
     */
    protected $browser;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $accept;

    /**
     * @param string                 $username
     * @param string                 $password
     * @param string                 $accept
     * @param Browser|null           $browser
     */
    public function __construct($username, $password, $accept, Browser $browser = null)
    {
        $this->browser = $browser ?: new Browser(function_exists('curl_exec') ? new Curl() : new FileGetContents());
        $this->username = $username;
        $this->password = $password;
        $this->accept = $accept;
    }

    /**
     * {@inheritdoc}
     */
    public function get($url)
    {
        $headers = [
            'sfusername: ' . $this->username,
            'sfpassword: ' . $this->password,
            'Accept: ' . $this->accept,
        ];
        $response = $this->browser->get($url, $headers);
        $this->handleResponse($response);
        return $response->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function delete($url)
    {
        $response = $this->browser->delete($url);
        $this->handleResponse($response);
    }

    /**
     * {@inheritdoc}
     */
    public function put($url, $content = '')
    {
        $headers = [
            'sfusername: ' . $this->username,
            'sfpassword: ' . $this->password,
            'Accept: ' . $this->accept,
        ];
        if (is_array($content)) {
            $content = json_encode($content);
            $headers[] = 'Content-Type: application/json';
        }
        $response = $this->browser->put($url, $headers, $content);
        $this->handleResponse($response);
        return $response->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function post($url, $content = '')
    {
        $headers = [
            'sfusername: ' . $this->username,
            'sfpassword: ' . $this->password,
            'Accept: ' . $this->accept,
        ];
        if (is_array($content)) {
            $content = json_encode($content);
            $headers[] = 'Content-Type: application/json';
        }
        $response = $this->browser->post($url, $headers, $content);
        $this->handleResponse($response);
        return $response->getContent();
    }

    /**
     * {@inheritdoc}
     */
    public function getLatestResponseHeaders()
    {
        if (null === $response = $this->browser->getLastResponse()) {
            return;
        }
        return [
            'reset' => (int) $response->getHeader('RateLimit-Reset'),
            'remaining' => (int) $response->getHeader('RateLimit-Remaining'),
            'limit' => (int) $response->getHeader('RateLimit-Limit'),
        ];
    }

    /**
     * @param Response $response
     *
     * @throws HttpException
     */
    protected function handleResponse(Response $response)
    {
        if ($response->isSuccessful()) {
            return;
        }
        $this->handleError($response);
    }

    /**
     * @param Response $response
     *
     * @throws HttpException
     */
    protected function handleError(Response $response)
    {
        $body = (string) $response->getContent();
        $code = (int) $response->getStatusCode();
        $content = json_decode($body);
        throw new HttpException(isset($content->message) ? $content->message : 'Request not processed.', $code);
    }

}
