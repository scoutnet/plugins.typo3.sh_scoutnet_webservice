<?php

namespace ScoutNet\ShScoutnetWebservice\Helpers;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2015 Stefan "Mütze" Horst <muetze@scoutnet.de>, ScoutNet
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

use ScoutNet\ShScoutnetWebservice\Exceptions\ScoutNetException;

/**
 * JsonRPCClientHelper
 *
 * @method get_data_by_global_id(array|int|null $globalId, mixed $filter)
 * @method deleteObject(string $type, ?int $globalId, int $id, string $username, $auth)
 * @method setData(string $type,int $id, mixed $object, string $username, $auth)
 * @method checkPermission(string $type, ?int $globalId, string $username, $auth)
 * @method requestPermission(string $type, ?int $globalId, string $username, $auth)
 * @method test()
 */
class JsonRPCClientHelper
{
    /**
     * Debug state
     *
     * @var bool
     */
    private bool $debugOutput;

    /**
     * The server URL
     *
     * @var string
     */
    private string $url;
    /**
     * The request id
     *
     * @var int
     */
    private int $request_id;
    /**
     * If true, notifications are performed instead of requests
     *
     * @var bool
     */
    private bool $notification = false;

    /**
     * Takes the connection parameters
     *
     * @param string $url
     * @param bool $debug
     */
    public function __construct(string $url, bool $debug = false)
    {
        // server URL
        $this->url = $url;
        $this->debugOutput = $debug;
        // message id
        $this->request_id = 1;
    }

    /**
     * Sets the notification state of the object. In this state, notifications are performed, instead of requests.
     *
     * @param bool $notification
     */
    public function setRPCNotification(bool $notification): void
    {
        empty($notification) ? $this->notification = false : $this->notification = true;
    }

    /**
     * Performs a jsonRCP request and gets the results as an array
     *
     * @param string $method
     * @param array $params
     *
     * @return array|bool
     * @throws ScoutNetException
     */
    public function __call(string $method, array $params)
    {
        $debug = '';

        // no keys
        $params = array_values($params);

        // sets notification or request task
        if ($this->notification) {
            $currentId = null;
        } else {
            $currentId = $this->request_id;
            ++$this->request_id;
        }

        // prepares the request
        $request = [
            'method' => $method,
            'params' => $params,
            'id' => $currentId,
        ];
        $request = json_encode($request);
        $this->debugOutput && $debug .= '***** Request *****' . "\n" . $request . "\n" . '***** End Of request *****' . "\n\n";

        // performs the HTTP POST
        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/json',
                'content' => $request,
            ]];
        $context  = stream_context_create($opts);

        if ($fp = @fopen($this->url, 'r', false, $context)) {
            $response = '';
            while ($row = fgets($fp)) {
                $response .= trim($row) . "\n";
            }
            $this->debugOutput && $debug .= '***** Server response *****' . "\n" . $response . '***** End of server response *****' . "\n";
            $response = json_decode($response, true);
        } else {
            throw new ScoutNetException('Unable to connect to ' . $this->url, 1572202683);
        }

        // debug output
        if ($this->debugOutput) {
            echo nl2br($debug);
        }

        // final checks and return
        if (!$this->notification) {
            // check
            if ((int)$response['id'] !== $currentId) {
                throw new ScoutNetException('Incorrect response id (request id: ' . $currentId . ', response id: ' . $response['id'] . ')', 1572203283);
            }
            if (isset($response['error'])) {
                if (is_array($response['error'])) {
                    throw new ScoutNetException('Request error: ' . $response['error']['message'] . ' (' . $response['error']['code'] . ')', 1572203301);
                }
                throw new ScoutNetException('Request error: ' . $response['error'], 1572203301);
            }

            return $response['result'];
        }
        return true;
    }
}
