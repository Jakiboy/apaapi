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

/**
 * Apaapi request interface.
 */
interface RequestInterface
{
    /**
     * Prepare request.
     * @see https://affiliate-program.amazon.com/creatorsapi/docs/
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
     * Set request path.
     *
     * @return string
     */
    function setPath() : void;

    /**
     * Set request target.
     *
     * @return string
     */
    function setTarget() : void;

    /**
     * Set request host.
     *
     * @return string
     */
    function setHost() : void;

    /**
     * Get request host.
     *
     * @return string
     */
    function getHost() : string;

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
     * @see https://affiliate-program.amazon.com/creatorsapi/docs/en-us/locale-reference.html
     *
     * @param string $locale
     * @return object
     * @throws \Apaapi\exceptions\RequestException
     */
    function setLocale(string $locale) : object;

    /**
     * Check valid client.
     *
     * @access public
     * @return bool
     */
    function hasClient() : bool;

    /**
     * Set request client.
     *
     * @access public
     * @param ClientInterface $client
     * @return void
     */
    function setClient(?ClientInterface $client = null);
}
