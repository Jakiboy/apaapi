<?php
/**
 * @package Amazon Product Advertising API
 * @version 1.0.7
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\lib;

use Apaapi\interfaces\OperationInterface;

/**
 * Basic Apaapi Amazon Signature Request Wrapper Class
 */
abstract class SignatureRequest
{
    /**
     * @access protected
     *
     * @var string $path, API Service (API Source)
     * @var string $regionName, API Region Namespace (limit)
     * @var string $serviceName, API Service (Default)
     * @var string $serviceTarget, API Service Source (Default)
     * @var string $httpMethodName, HTTP Method
     * @var string $HMACAlgorithm, HTTP Request Hash
     * @var array $queryParametes, HTTP Queries
     * @var string $request, HTTP Request Method
     * @var array $headers, HTTP Headers
     * @var json $payload, HTTP Request content
     */
    protected $path = '/paapi5/';
    protected $regionName = 'us-east-1';
    protected $serviceName = 'ProductAdvertisingAPI';
    protected $httpMethodName = 'POST';
    protected $HMACAlgorithm = 'AWS4-HMAC-SHA256';
    protected $request = 'aws4_request';
    protected $queryParametes = [];
    protected $headers = [];
    protected $payload;

    /**
     * @access private
     */
    private $strSignedHeader = null;

    /**
     * @access public
     */
    public $params = [];
    public $endpoint = [];

    /**
     * @access public
     * @param OperationInterface $operation
     * @return void
     */
    abstract public function setPayload(OperationInterface $operation);

	/**
	 * @access protected
	 * @param void
	 * @return string
	 */
    protected function prepareCanonicalRequest()
    {
        $canonicalUrl  = '';
        $canonicalUrl .= "{$this->httpMethodName}\n";
        $canonicalUrl .= "{$this->path}\n\n";
        $signedHeaders = '';
        foreach ( $this->headers as $key => $value ) {
            $signedHeaders .= "{$key};";
            $canonicalUrl  .= "{$key}:{$value}\n";
        }
        $canonicalUrl .= "\n";
        $this->strSignedHeader = substr( $signedHeaders, 0, - 1 );
        $canonicalUrl .= "{$this->strSignedHeader}\n";
        $canonicalUrl .= $this->generateHex( $this->payload );
        return $canonicalUrl;
    }

	/**
	 * @access protected
	 * @param string $canonicalURL
	 * @return string
	 */
    protected function prepareStringToSign($canonicalURL)
    {
        $stringToSign  = '';
        $stringToSign .= $this->HMACAlgorithm . "\n";
        $stringToSign .= $this->date . "\n";
        $stringToSign .= $this->currentDate . "/" . $this->regionName . "/";
        $stringToSign .= $this->serviceName . "/" . $this->request . "\n";
        $stringToSign .= $this->generateHex ( $canonicalURL );
        return $stringToSign;
    }

	/**
	 * @access protected
	 * @param string $stringToSign
	 * @return string
	 */
    protected function calculateSignature($stringToSign)
    {
        $signatureKey = $this->getSignatureKey( $this->secretAccessKey, $this->currentDate, $this->regionName, $this->serviceName );
        $signature = hash_hmac('sha256', $stringToSign, $signatureKey, true );
        $strHexSignature = strtolower( bin2hex($signature) );
        return $strHexSignature;
    }

	/**
	 * @access protected
	 * @param string $strSignature
	 * @return string
	 */
    protected function buildAuthorizationString($strSignature)
    {
    	$auth  = "{$this->HMACAlgorithm} ";
    	$auth .= "Credential={$this->accessKeyID}/";
    	$auth .= "{$this->getDate()}/";
    	$auth .= "{$this->regionName}/";
        $auth .= "{$this->serviceName}/";
    	$auth .= "{$this->request},";
    	$auth .= "SignedHeaders={$this->strSignedHeader},";
    	$auth .= "Signature={$strSignature}";

    	return $auth;
    }

	/**
	 * @access protected
	 * @param string $data
	 * @return string
	 */
    protected function generateHex($data)
    {
        return strtolower(bin2hex(hash('sha256', $data, true)));
    }

	/**
	 * @access protected
	 * @param string $key
	 * @param string $date
	 * @param string $regionName
	 * @param string $serviceName
	 * @return string
	 */
    protected function getSignatureKey($key, $date, $regionName, $serviceName)
    {
        $kSecret = "AWS4" . $key;
        $kDate = hash_hmac( "sha256", $date, $kSecret, true );
        $kRegion = hash_hmac( "sha256", $regionName, $kDate, true );
        $kService = hash_hmac( "sha256", $serviceName, $kRegion, true );
        $kSigning = hash_hmac( "sha256", $this->request, $kService, true );

        return $kSigning;
    }

    /**
     * @access protected
     * @param void
     * @return array|void
     */
    protected function getHeaders()
    {
        ksort($this->headers);
        $canonicalURL = $this->prepareCanonicalRequest();
        $stringToSign = $this->prepareStringToSign($canonicalURL);
        $signature = $this->calculateSignature($stringToSign);
        if ( $signature ) {
            $this->headers['Authorization'] = $this->buildAuthorizationString($signature);
            return $this->headers;
        }
    }

	/**
	 * @access protected
	 * @param void
	 * @return TimeStamp
	 */
    protected function getTimeStamp()
    {
        return gmdate("Ymd\THis\Z");
    }

	/**
	 * @access protected
	 * @param void
	 * @return Date
	 */
    protected function getDate()
    {
        return gmdate("Ymd");
    }
}
