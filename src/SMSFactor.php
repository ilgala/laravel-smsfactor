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

use IlGala\SMSFactor\Exceptions\SMSFactorException;
use IlGala\SMSFactor\Adapters\AdapterInterface;

/**
 * @author Filippo Galante <filippo.galante@b-ground.com>
 */
class SMSFactor
{

    /**
     * @var string
     */
    const ENDPOINT = 'https://api.smsfactor.com/';

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var string
     */
    private $content_type;

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @param AdapterInterface $adapter
     */
    public function __construct(AdapterInterface $adapter, $content_type, $endpoint = null)
    {
        $this->adapter = $adapter;
        $this->content_type = strtolower($content_type);
        $this->endpoint = $endpoint ?: self::ENDPOINT;
    }

    /**
     * Create an account or sub account.
     *
     * @return mixed
     */
    public function createAccount($params)
    {
        // Http request
        $response = $this->adapter->post(sprintf('%s/account', $this->endpoint), $params);

        // Result
        if ($this->content_type == 'application/json') {
            return json_decode($response);
        } else {
            return $response;
        }
    }

    /**
     * Get current credits.
     *
     * @return mixed
     */
    public function credits()
    {
        // Http request
        $response = $this->adapter->get(sprintf('%s/credits', $this->endpoint));

        // Result
        if ($this->content_type == 'application/json') {
            return json_decode($response);
        } else {
            return $response;
        }
    }

    /**
     * Send or simulate sending of single or multiple SMSs.
     *
     * @return mixed
     */
    public function send($params, $method, $simulate = false)
    {
        $path = '%s/send';
        if (strtoupper($method) == 'POST') {
            // Http request
            $path = sprintf('%s/send', $this->endpoint);
            if ($simulate) {
                $path .= '/simulate';
            }

            // Http request
            $response = $this->adapter->post($path, $params);

            // Result
            if ($this->content_type == 'application/json') {
                return json_decode($response);
            } else {
                return $response;
            }
        } elseif (strtoupper($method) == 'GET') {
            // Http request
            $path = sprintf('%s/send?username=%s&password=%s&text=%s&to=%s', $this->endpoint, $params['username'], $params['password'], $params['text'], $params['to']);

            if (array_key_exists('delay', $params)) {
                if ($this->isValidDate($params['delay'])) {
                    $path .= sprintf('&delay=%s', $params['delay']);
                }
            }

            if (array_key_exists('sender', $params)) {
                $path .= sprintf('&sender=%s', $params['sender']);
            }

            if (array_key_exists('gsmsmsid', $params)) {
                $path .= sprintf('&gsmsmsid=%s', $params['gsmsmsid']);
            }

            // Http request
            $response = $this->adapter->get($path, $params);

            // Result
            if ($this->content_type == 'application/json') {
                return json_decode($response);
            } else {
                return $response;
            }
        } else {
            return null;
        }
    }

    /**
     * Send or simulate sending of SMSs to selected lists.
     *
     * @return mixed
     */
    public function sendLists($params, $simulate = false)
    {
        $path = sprintf('%s/send/lists', $this->endpoint);
        if ($simulate) {
            $path .= '/simulate';
        }

        // Http request
        $response = $this->adapter->post($path, $params);

        // Result
        if ($this->content_type == 'application/json') {
            return json_decode($response);
        } else {
            return $response;
        }
    }

    /**
     * Cancel the sending with selected id.
     *
     * @return mixed
     */
    public function delete($id)
    {
        // Http request
        $response = $this->adapter->delete(sprintf('%s/send/%d', $this->endpoint, $id));

        // Result
        if ($this->content_type == 'application/json') {
            return json_decode($response);
        } else {
            return $response;
        }
    }

    /**
     * Create or update a contact list.
     *
     * @return mixed
     */
    public function contactList($params)
    {
        // Http request
        $response = $this->adapter->post(sprintf('%s/list', $this->endpoint), $params);

        // Result
        if ($this->content_type == 'application/json') {
            return json_decode($response);
        } else {
            return $response;
        }
    }

    /**
     * Retrieve contact lists.
     *
     * @return mixed
     */
    public function getContactList($id = null)
    {
        $path = sprintf('%s/send', $this->endpoint);
        if ($id) {
            $path .= sprintf('/%d', $id);
        }

        // Http request
        $response = $this->adapter->get($path);

        // Result
        if ($this->content_type == 'application/json') {
            return json_decode($response);
        } else {
            return $response;
        }
    }

    /**
     * Remove duplicated contacts from list.
     *
     * @return mixed
     */
    public function deduplicate($id)
    {
        $response = $this->adapter->put(sprintf('%s/deduplicate/%d', $this->endpoint, $id));

        // Result
        if ($this->content_type == 'application/json') {
            return json_decode($response);
        } else {
            return $response;
        }
    }

    /**
     * @return mixed
     */
    public function deleteContact($id)
    {
        $response = $this->adapter->delete(sprintf('%s/list/contact/%d', $this->endpoint, $id));

        // Result
        if ($this->content_type == 'application/json') {
            return json_decode($response);
        } else {
            return $response;
        }
    }

    /**
     * Retrieve blacklist contacts.
     *
     * @return mixed
     */
    public function getBlacklist()
    {
        // Http request
        $response = $this->adapter->get(sprintf('%s/blacklist', $this->endpoint));

        // Result
        if ($this->content_type == 'application/json') {
            return json_decode($response);
        } else {
            return $response;
        }
    }

    /**
     * @return mixed
     */
    public function deliveryReport($params)
    {
        if ($this->content_type == 'application/json') {
            throw new SMSFactorException("Delivery report is available accepts only XML");
        }

        $response = $this->adapter->post(sprintf('%s/dr', $this->endpoint), $params);

        // Result
        return $response;
    }

    private function isValidDate($date)
    {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2}):(\d{2})Z$/', $date, $parts) == true) {
            $time = gmmktime($parts[4], $parts[5], $parts[6], $parts[2], $parts[3], $parts[1]);

            $input_time = strtotime($date);
            if ($input_time === false) {
                return false;
            }

            return $input_time == $time;
        } else {
            return false;
        }
    }
}
