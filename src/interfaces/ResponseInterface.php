<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.3.x
 * @copyright : (c) 2019 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\interfaces;

/**
 * Apaapi response interface.
 */
interface ResponseInterface
{
    /**
     * Send request then get response.
     *
     * @param RequestInterface $request
     * @param bool $normalize
     * @param bool $cache
     */
    function __construct(RequestInterface $request, bool $normalize = false, bool $cache = true);

    /**
     * Get response data.
     *
     * @param array $geo
     * @return array
     */
    function get(?array $geo = null) : array;

    /**
     * Get response body.
     *
     * @return string
     */
    function getBody() : string;

    /**
     * Get response error.
     *
     * @return string
     */
    function getError() : string;

    /**
     * Check response error.
     *
     * @return bool
     */
    function hasError() : bool;
}
