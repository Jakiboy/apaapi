<?php
/**
 * @package Amazon Product Advertising API v5
 * @copyright Copyright (c) 2019 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\lib;

use Apaapi\interfaces\RequestInterface;
use Apaapi\interfaces\OperationInterface;
use Apaapi\lib\SignatureRequest;
use Apaapi\lib\OperationProvider;

/**
 * Basic Paapi5 Request Wrapper Class
 */
class Request extends SignatureRequest 
implements RequestInterface
{
    /**
     * @access protected
     * @var string $accessKeyID, Amazon API Access Key
     * @var string $secretAccessKey, API Secret Key
     * @var string $date, API Request Date
     * @var string $currentDate, API Current Date
     */
    protected $accessKeyID = null;
    protected $secretAccessKey = null;
    protected $date = null;
    protected $currentDate = null;

    /**
     * @access private
     * @var string $host, API Host (endpoint)
     * @var string $region, API Region (Language)
     */
    private $host = 'webservices.amazon';
    private $region = 'com';

    /**
     * @param string $accessKeyID
     * @param string $secretAccessKey
     * @return void
     */
    public function __construct($accessKeyID, $secretAccessKey)
    {
        $this->accessKeyID = $accessKeyID;
        $this->secretAccessKey = $secretAccessKey;
        $this->init();
    }

    /**
     * @access private
     * @param void
     * @return void
     */
    private function init()
    {
        $this->date = $this->getTimeStamp();
        $this->currentDate = $this->getDate();
        $this->setHeader('content-encoding','amz-1.0');
        $this->setHeader('content-type','application/json; charset=utf-8');
    }

    /**
     * @access public
     * @param string $serviceName
     * @return void
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @access public
     * @param string $method
     * @return void
     */
    public function setRequestMethod($method)
    {
        $this->httpMethodName = $method;
    }

    /**
     * @access public
     * @param string $headerName
     * @param string $headerValue
     * @return void
     */
    public function setHeader($headerName, $headerValue)
    {
        $this->headers[$headerName] = $headerValue;
    }

    /**
     * @access public
     * @param string $region
     * @return object
     */
    public function setRegion($region)
    {
        $this->region = $region;

        if ( in_array($this->region, ['fr','de','in','it','es','com.tr','ae','co.uk']) ) {
            $this->regionName = 'eu-west-1';

        } elseif ( in_array($this->region, ['com','com.br','ca','com.mx']) ) {
            $this->regionName = 'us-east-1';

        } elseif ( in_array($this->region, ['com.au','co.jp']) ) {
            $this->regionName = 'us-east-2';
        }
        return $this;
    }

    /**
     * @access public
     * @param OperationInterface $operation
     * @return void
     */
    public function setPayload(OperationInterface $operation)
    {
        $target = get_class($operation);
        $this->path = $this->path.strtolower($target);
        $target = "com.amazon.paapi5.v1.ProductAdvertisingAPIv1.{$target}";
        $this->host = "{$this->host}.{$this->region}";
        $this->payload = OperationProvider::generate($operation);

        $this->setHeader('host', $this->host);
        $this->setHeader('x-amz-target', $target);
        $this->setHeader('x-amz-date', $this->date);

        $headers = $this->getHeaders();
        $headerString = '';

        foreach ( $headers as $key => $value ) {
            $headerString .= "{$key}: {$value}\r\n";
        }

        $this->endpoint = "{$this->host}{$this->path}";
        $this->params = [
            'http' => [
                'method'  => 'POST',
                'header'  => $headerString,
                'content' => $this->payload
            ]
        ];
    }
}
