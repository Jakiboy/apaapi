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
     * Init client.
     *
     * @param ?string $baseUrl
     * @param array $params
     */
    function __construct(?string $baseUrl = null, array $params = []);

    /**
     * Make Http request.
     *
     * @param string $method
     * @param string|array $body
     * @param array $header
     * @param ?string $url
     * @return self
     */
    function request(string $method, string|array $body = [], array $header = [], ?string $url = null) : self;

    /**
     * Make Http GET request.
     *
     * @param ?string $url
     * @param string|array $body
     * @param array $header
     * @return self
     */
    function get(?string $url = null, string|array $body = [], array $header = []) : self;

    /**
     * Make Http POST request.
     *
     * @param ?string $url
     * @param string|array $body
     * @param array $header
     * @return object
     */
    function post(?string $url = null, string|array $body = [], array $header = []) : self;

    /**
     * Make Http HEAD request.
     *
     * @param ?string $url
     * @param string|array $body
     * @param array $header
     * @return self
     */
    function head(?string $url = null, string|array $body = [], array $header = []) : self;

    /**
     * Set request method.
     *
     * @param string $method
     * @return self
     */
    function setMethod(string $method) : self;

    /**
     * Set request header.
     *
     * @param array $header
     * @return self
     */
    function setHeader(array $header = []) : self;

    /**
     * Set request body (Data).
     *
     * @param string|array $body
     * @return self
     */
    function setBody(string|array $body = []) : self;

    /**
     * Set request URL (Append to base URL).
     *
     * @param ?string $url
     * @return self
     */
    function setUrl(?string $url = null) : self;

    /**
     * Set request timeout.
     *
     * @param int $timeout
     * @return self
     */
    function setTimeout(int $timeout) : self;

    /**
     * Set request max redirections.
     *
     * @param int $redirect
     * @return self
     */
    function setRedirect(int $redirect) : self;

    /**
     * Set request encoding.
     *
     * @param string $encoding
     * @return self
     */
    function setEncoding(?string $encoding = null) : self;

    /**
     * Set User-Agent.
     *
     * @param ?string $ua
     * @return self
     */
    function setUserAgent(?string $ua = null) : self;

    /**
     * Allow cURL transfer return.
     *
     * @return self
     */
    function return() : self;

    /**
     * Allow cURL redirection follow.
     *
     * @return self
     */
    function follow() : self;

    /**
     * Allow cURL header in response.
     *
     * @return self
     */
    function headerIn() : self;

    /**
     * Get response data.
     *
     * @return array
     */
    function getResponse() : array;

    /**
     * Get response status (code, message).
     *
     * @return array
     */
    function getStatus() : array;

    /**
     * Get response status code.
     *
     * @return int
     */
    function getStatusCode() : int;

    /**
     * Get response status message.
     *
     * @return string
     */
    function getStatusMessage() : string;

    /**
     * Get response header.
     *
     * @return array
     */
    function getHeader() : array;

    /**
     * Get response body.
     *
     * @param bool $decode JSON
     * @return mixed
     */
    function getBody(bool $decode = true) : mixed;

    /**
     * Check client error (Http|Gateway).
     *
     * @param int $httpCode
     * @return bool
     */
    function hasError(int $httpCode = 400) : bool;

    /**
     * Set Http parser patterns.
     *
     * @param array $pattern
     * @return void
     */
    static function setPattern(array $pattern = []) : void;

    /**
     * Get Http parser patterns.
     *
     * @param string $name
     * @return string
     */
    static function getPattern(string $name) : string;

    /**
     * Get request body data (query).
     *
     * @param array $body
     * @param ?string $url
     * @return string
     */
    static function getQuery(array $body, ?string $url = null) : string;

    /**
     * Format Http request header.
     *
     * @param array $header
     * @param bool $assoc
     * @return mixed
     */
    static function formatHeader(array $header, bool $assoc = false) : mixed;

    /**
     * Get default User-Agent.
     *
     * @return string
     */
    static function getUserAgent() : string;

    /**
     * Get default Http client parameters.
     *
     * @param array $params
     * @return array
     */
    static function getParams(array $params = []) : array;
}
