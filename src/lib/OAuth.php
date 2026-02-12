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

declare(strict_types=1);

namespace Apaapi\lib;

use Apaapi\interfaces\{OperationInterface, ClientInterface};

/**
 * Apaapi Amazon OAuth 2.0 authentication wrapper class.
 * @see https://affiliate-program.amazon.com/creatorsapi/docs/
 */
abstract class OAuth
{
    /**
     * @access protected
     * @var string $path, API path
     * @var string $locale, API region locale
     * @var string $target, API request target
     * @var array $headers, HTTP request Headers
     * @var string $payload, HTTP request payload
     * @var string $credentialID, Amazon credential ID
     * @var string $credentialSecret, Amazon credential secret
     * @var string $accessToken, OAuth 2.0 access token
     * @var int $tokenExpiry, Token expiration timestamp
     */
    protected $path = '/catalog/v1';
    protected $locale = 'com';
    protected $target = 'creatorsapi';
    protected $headers = [];
    protected $payload;
    protected $credentialID;
    protected $credentialSecret;
    protected $accessToken;
    protected $tokenExpiry = 0;
    protected $version = '2.2'; // Default version for EU

    /**
     * @access private
     * @var string $method, HTTP request method
     */
    private $method = 'POST';

    /**
     * Get regional OAuth token endpoint based on credential version.
     * Version 2.1 = NA, 2.2 = EU, 2.3 = FE
     *
     * @access private
     * @return string
     */
    private function getTokenEndpoint() : string
    {
        $endpoints = [
            '2.1' => 'https://creatorsapi.auth.us-east-1.amazoncognito.com/oauth2/token',
            '2.2' => 'https://creatorsapi.auth.eu-south-2.amazoncognito.com/oauth2/token',
            '2.3' => 'https://creatorsapi.auth.us-west-2.amazoncognito.com/oauth2/token'
        ];

        if ( !isset($endpoints[$this->version]) ) {
            throw new \InvalidArgumentException(
                "Unsupported version: {$this->version}. Supported versions are: 2.1, 2.2, 2.3"
            );
        }

        return $endpoints[$this->version];
    }

    /**
     * Set request payload.
     *
     * @access public
     * @param OperationInterface $operation
     * @return void
     */
    abstract public function setPayload(OperationInterface $operation);

    /**
     * Set request client.
     *
     * @access public
     * @param ClientInterface $client
     * @return void
     */
    abstract public function setClient(?ClientInterface $client = null);

    /**
     * Get request headers with OAuth 2.0 Bearer token.
     *
     * @access protected
     * @return array
     */
    protected function getHeader() : array
    {
        // Ensure we have a valid access token
        if ( !$this->isTokenValid() ) {
            $this->refreshAccessToken();
        }

        // Set OAuth 2.0 Bearer token with Version
        if ( $this->accessToken ) {
            $this->headers['Authorization'] = "Bearer {$this->accessToken}, Version {$this->version}";
        }

        return $this->headers;
    }

    /**
     * Check if access token is valid.
     *
     * @access private
     * @return bool
     */
    private function isTokenValid() : bool
    {
        if ( !$this->accessToken ) {
            return false;
        }

        // Check if token has expired (with 30 second buffer to match official SDK)
        return time() < ($this->tokenExpiry - 30);
    }

    /**
     * Refresh OAuth 2.0 access token.
     *
     * @access private
     * @return void
     */
    private function refreshAccessToken() : void
    {
        $endpoint = $this->getTokenEndpoint();

        $headers = [
            "Content-Type: application/x-www-form-urlencoded"
        ];

        $body = http_build_query([
            'grant_type'    => 'client_credentials',
            'client_id'     => $this->credentialID,
            'client_secret' => $this->credentialSecret,
            'scope'         => 'creatorsapi/default'
        ]);

        // Use cURL to get token
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable for local dev
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Add timeout

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ( $curlError ) {
            error_log("OAuth cURL Error: {$curlError}");
        }

        if ( $httpCode === 200 && $response ) {
            $data = json_decode($response, true);

            if ( isset($data['access_token']) ) {
                $this->accessToken = $data['access_token'];
                // Set expiration time with a 30-second buffer to match official SDK
                $expiresIn = isset($data['expires_in']) ? (int)$data['expires_in'] : 3600;
                $this->tokenExpiry = time() + $expiresIn - 30;
            }
        } else {
            // Clear existing token on failure (official SDK pattern)
            $this->clearToken();
            error_log("OAuth Error: Failed to get access token. HTTP Code: {$httpCode}");
        }
    }

    /**
     * Clear OAuth token.
     *
     * @access private
     * @return void
     */
    private function clearToken() : void
    {
        $this->accessToken = null;
        $this->tokenExpiry = 0;
    }

    /**
     * Set request header.
     *
     * @access public
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function setRequestHeader(string $name, $value) : void
    {
        $name = strtolower($name);
        $this->headers[$name] = $value;
    }
}
