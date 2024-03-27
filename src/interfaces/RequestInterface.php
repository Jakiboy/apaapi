<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.2.0
 * @copyright : (c) 2019 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\interfaces;

/**
 * Apaapi request interface.
 */
interface RequestInterface
{
    /**
     * Prepare request.
     * @see https://webservices.amazon.com/paapi5/scratchpad/index.html
     *
     * @param string $accessKeyID
     * @param string $secretAccessKey
     */
    function __construct(string $accessKeyID, string $secretAccessKey);

    /**
     * Get request client.
     *
     * @return object
     */
    function getClient() : object;

    /**
     * Get request endpoint.
     *
     * @return string
     */
    function getEndpoint() : string;

    /**
     * Get request parameters.
     *
     * @return array
     */
    function getParams() : array;

    /**
     * Get request operation.
     *
     * @return string
     */
    function getOperation() : string;

    /**
     * Set request header.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    function setRequestHeader(string $name, $value);

    /**
     * Set request locale.
     * @see https://webservices.amazon.fr/paapi5/documentation/locale-reference.html
     *
     * @param string $locale
     * @return object
     * @throws RequestException
     */
    function setLocale(string $locale) : object;
}
