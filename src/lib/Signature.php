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

namespace Apaapi\lib;

use Apaapi\interfaces\{OperationInterface, ClientInterface};

/**
 * Apaapi Amazon signature request wrapper class.
 * @see https://webservices.amazon.com/paapi5/documentation/without-sdk.html
 */
abstract class Signature
{
    /**
     * @access protected
     * @var string $path, API path
     * @var string $locale, API region locale
     * @var string $region, API region name
     * @var string $target, API request target
     * @var array $headers, HTTP request Headers
     * @var string $payload, HTTP request payload
     * @var string $accessKeyID, Amazon access key Id
     * @var string $secretAccessKey, Amazon secret  access key
     * @var string $timestamp, API request timestamp
     * @var string $currentDate, API request current date
     */
    protected $path = '/paapi5/';
    protected $locale = 'com';
    protected $region = 'us-east-1';
    protected $target = 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1';
    protected $headers = [];
    protected $payload;
    protected $accessKeyID;
    protected $secretAccessKey;
    protected $timestamp;
    protected $currentDate;

    /**
     * @access private
     * @var string $service, API service
     * @var string $method, HTTP request method
     * @var string $hmac, HTTP request HMAC algorithm
     * @var string $request, HTTP request
     * @var string $algo, Hash algorithm
     * @var string $signedHeaders, HTTP request signed headers
     */
    private $service = 'ProductAdvertisingAPI';
    private $method = 'POST';
    private $hmac = 'AWS4-HMAC-SHA256';
    private $request = 'aws4_request';
    private $algo = 'sha256';
    private $signedHeaders;

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
     * Set request timestamp.
     *
     * @access public
     * @param string $timestamp
     * @return object
     */
    abstract public function setTimeStamp(?string $timestamp = null) : object;

    /**
     * Set request date.
     *
     * @access public
     * @param string $date
     * @return object
     */
    abstract public function setDate(?string $date = null) : object;

    /**
     * Get request headers.
     *
     * @access protected
     * @return array
     */
    protected function getHeader() : array
    {
        ksort($this->headers);
        $canonicalUrl = $this->prepareCanonicalRequest();
        $stringToSign = $this->prepareStringToSign($canonicalUrl);
        if ( ($signature = $this->calculateSignature($stringToSign)) ) {
            $this->headers['Authorization'] = $this->buildAuthorizationString($signature);
        }
        return $this->headers;
    }

    /**
     * Prepare canonical request.
     *
     * @access private
     * @return string
     */
    private function prepareCanonicalRequest() : string
    {
        $url = "{$this->method}\n";
        $url .= "{$this->path}\n\n";
        $signedHeaders = '';
        foreach ($this->headers as $key => $value) {
            $signedHeaders .= "{$key};";
            $url .= "{$key}:{$value}\n";
        }
        $url .= "\n";
        $this->signedHeaders = substr($signedHeaders, 0, -1);
        $url .= "{$this->signedHeaders}\n";
        $url .= $this->generateHex($this->payload);
        return $url;
    }

    /**
     * Prepare string to be sign.
     *
     * @access private
     * @param string $url
     * @return string
     */
    private function prepareStringToSign(string $url) : string
    {
        $string = "{$this->hmac}\n";
        $string .= "{$this->timestamp}\n";
        $string .= "{$this->currentDate}/{$this->region}/";
        $string .= "{$this->service}/{$this->request}\n";
        $string .= $this->generateHex($url);
        return $string;
    }

    /**
     * Calculate signature.
     *
     * @access private
     * @param string $data
     * @return string
     */
    private function calculateSignature(string $data) : string
    {
        $key = $this->getSignatureKey(
            $this->secretAccessKey,
            $this->currentDate,
            $this->region,
            $this->service
        );
        $signature = $this->hash($data, $key);
        return strtolower(bin2hex($signature));
    }

    /**
     * Build authorization string.
     *
     * @access private
     * @param string $signature
     * @return string
     */
    private function buildAuthorizationString(string $signature) : string
    {
        $auth = "{$this->hmac} ";
        $auth .= "Credential={$this->accessKeyID}/";
        $auth .= "{$this->currentDate}/";
        $auth .= "{$this->region}/";
        $auth .= "{$this->service}/";
        $auth .= "{$this->request},";
        $auth .= "SignedHeaders={$this->signedHeaders},";
        $auth .= "Signature={$signature}";
        return $auth;
    }

    /**
     * Generate hex.
     *
     * @access private
     * @param string $date
     * @return string
     */
    private function generateHex(string $date) : string
    {
        $hex = bin2hex(hash($this->algo, $date, true));
        return strtolower($hex);
    }

    /**
     * Get signature key.
     *
     * @access private
     * @param string $key
     * @param string $date
     * @param string $region
     * @param string $service
     * @return string
     */
    private function getSignatureKey(string $key, string $date, string $region, string $service) : string
    {
        $secret = "AWS4{$key}";
        $date = $this->hash($date, $secret);
        $region = $this->hash($region, $date);
        $service = $this->hash($service, $region);
        return $this->hash($this->request, $service);
    }

    /**
     * Hash data.
     *
     * @access private
     * @param string $data
     * @param string $key
     * @return string
     */
    private function hash(string $data, string $key) : string
    {
        return hash_hmac($this->algo, $data, $key, true);
    }
}
