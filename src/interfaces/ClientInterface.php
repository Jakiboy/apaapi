<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.3.x
 * @copyright : (c) 2019 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\interfaces;

/**
 * Apaapi request client interface.
 */
interface ClientInterface
{
    /**
     * Setup client.
     *
     * @param string $endpoint
     * @param array $params
     */
    function __construct(string $endpoint, array $params = []);

    /**
     * Set HTTP request method.
     *
     * @param string $method
     * @return object
     */
    function setMethod(string $method) : self;

    /**
     * Set HTTP response timeout.
     *
     * @param int $timeout
     * @return object
     */
    function setTimeout(int $timeout) : self;

    /**
     * Set HTTP redirect location.
     *
     * @param int $redirect
     * @return object
     */
    function setRedirect(int $redirect) : self;

    /**
     * Set HTTP encoding.
     *
     * @param string $encoding
     * @return object
     */
    function setEncoding(string $encoding) : self;

    /**
     * Get HTTP response content.
     *
     * @return string
     */
    function getResponse() : string;

    /**
     * Get HTTP response code.
     *
     * @return int
     */
    function getCode() : int;

    /**
     * Close request handler.
     *
     * @return void
     */
    function close() : void;
}
