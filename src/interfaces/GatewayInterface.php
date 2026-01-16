<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Creators API Library
 * @version   : 2.0.x
 * @copyright : (c) 2019 - 2026 Jihad Sinnaour <me@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\interfaces;

interface GatewayInterface
{
    /**
     * Advanced HTTP request.
     *
     * @param string $url
     * @param array $params
     * @return array
     */
    static function request(string $url, array $params = []) : array;
}
