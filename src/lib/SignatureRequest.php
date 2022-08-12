<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi
 * @version   : 1.1.0
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\lib;

use Apaapi\interfaces\ParsableInterface;
use Apaapi\interfaces\RequestClientInterface;

/**
 * Basic Apaapi Amazon Signature Request Wrapper Class
 */
abstract class SignatureRequest
{
    /**
     * @access protected
     * @var string $path, API path
     * @var string $locale, API region locale
     * @var string $region, API region name
     * @var string $target, API request target
     * @var array $headers, HTTP Headers
     * @var string $payload, HTTP request content
     * @var string $accessKeyID, Amazon API Key ID
     * @var string $secretAccessKey, API Secret Key
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
     * @var string $serviceName, API service name
     * @var string $httpMethodName, HTTP method
     * @var string $HMACAlgorithm, HTTP Request Hash
     * @var string $request, HTTP Request Method
     * @var string $strSignedHeader
     */
    private $serviceName = 'ProductAdvertisingAPI';
    private $httpMethodName = 'POST';
    private $HMACAlgorithm = 'AWS4-HMAC-SHA256';
    private $request = 'aws4_request';
    private $strSignedHeader;

    /**
     * @access public
     * @param ParsableInterface $operation
     * @return void
     */
    abstract public function setPayload(ParsableInterface $operation);

    /**
     * @access public
     * @param RequestClientInterface $client
     * @return void
     */
    abstract public function setClient(RequestClientInterface $client = null);

    /**
     * @access public
     * @param string $timestamp
     * @return object
     */
    abstract public function setTimeStamp($timestamp = null);

    /**
     * @access public
     * @param string $date
     * @return object
     */
    abstract public function setDate($date = null);

    /**
     * @access protected
     * @param void
     * @return array
     */
    protected function getHeaders()
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
     * Prepare canonical request
     *
	 * @access private
	 * @param void
	 * @return string
	 */
    private function prepareCanonicalRequest()
    {
        $canonicalUrl  = "{$this->httpMethodName}\n";
        $canonicalUrl .= "{$this->path}\n\n";
        $signedHeaders = '';
        foreach ( $this->headers as $key => $value ) {
            $signedHeaders .= "{$key};";
            $canonicalUrl  .= "{$key}:{$value}\n";
        }
        $canonicalUrl .= "\n";
        $this->strSignedHeader = substr($signedHeaders,0,-1);
        $canonicalUrl .= "{$this->strSignedHeader}\n";
        $canonicalUrl .= $this->generateHex($this->payload);
        return $canonicalUrl;
    }

	/**
     * Prepare string to be signed
     *
	 * @access private
	 * @param string $canonicalUrl
	 * @return string
	 */
    private function prepareStringToSign($canonicalUrl)
    {
        $string  = "{$this->HMACAlgorithm}\n";
        $string .= "{$this->timestamp}\n";
        $string .= "{$this->currentDate}/{$this->region}/";
        $string .= "{$this->serviceName}/{$this->request}\n";
        $string .= $this->generateHex($canonicalUrl);
        return $string;
    }

	/**
     * Calculate signature
     *
	 * @access private
	 * @param string $stringToSign
	 * @return string
	 */
    private function calculateSignature($stringToSign)
    {
        $signatureKey = $this->getSignatureKey(
            $this->secretAccessKey,
            $this->currentDate,
            $this->region,
            $this->serviceName
        );
        $signature = hash_hmac('sha256',$stringToSign,$signatureKey,true);
        $strHexSignature = strtolower(bin2hex($signature));
        return $strHexSignature;
    }

	/**
	 * @access private
	 * @param string $strSignature
	 * @return string
	 */
    private function buildAuthorizationString($strSignature)
    {
    	$auth  = "{$this->HMACAlgorithm} ";
    	$auth .= "Credential={$this->accessKeyID}/";
    	$auth .= "{$this->currentDate}/";
    	$auth .= "{$this->region}/";
        $auth .= "{$this->serviceName}/";
    	$auth .= "{$this->request},";
    	$auth .= "SignedHeaders={$this->strSignedHeader},";
    	$auth .= "Signature={$strSignature}";
    	return $auth;
    }

	/**
     * Generate Hex
     *
	 * @access private
	 * @param string $data
	 * @return string
	 */
    private function generateHex($data)
    {
        return strtolower(bin2hex(hash('sha256',$data,true)));
    }

	/**
	 * @access private
	 * @param string $key
	 * @param string $date
	 * @param string $region
	 * @param string $serviceName
	 * @return string
	 */
    private function getSignatureKey($key, $date, $region, $serviceName)
    {
        $kSecret = "AWS4{$key}";
        $kDate = hash_hmac('sha256',$date,$kSecret,true);
        $kRegion = hash_hmac('sha256',$region,$kDate,true);
        $kService = hash_hmac('sha256',$serviceName,$kRegion,true);
        return hash_hmac('sha256',$this->request,$kService,true);
    }
}
