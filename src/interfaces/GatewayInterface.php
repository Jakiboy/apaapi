<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.5.x
 * @copyright : (c) 2019 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
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
