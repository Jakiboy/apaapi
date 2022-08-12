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

use Apaapi\interfaces\RequestInterface;
use Apaapi\interfaces\ParsableInterface;
use Apaapi\interfaces\RequestClientInterface;
use Apaapi\includes\OperationParser;
use Apaapi\includes\RequestClient;
use Apaapi\exceptions\RequestException;

/**
 * Basic Apaapi Request Wrapper Class
 */
final class Request extends SignatureRequest 
implements RequestInterface
{
    /**
     * @access public
     * @var string HOST, API Host
     */
    const HOST = 'webservices.amazon';

    /**
     * @access private
     * @var array $params
     * @var array $endpoint
     * @var RequestClient $client
     * @var string $operation
     */
    private $params = [];
    private $endpoint = [];
    private $client = null;
    private $operation;

    /**
     * @param string $accessKeyID
     * @param string $secretAccessKey
     * @see https://webservices.amazon.com/paapi5/scratchpad/index.html
     */
    public function __construct($accessKeyID = '', $secretAccessKey = '')
    {
        $this->accessKeyID = $accessKeyID;
        $this->secretAccessKey = $secretAccessKey;
        $this->init();
    }

    /**
     * Set payload
     *
     * @access public
     * @param ParsableInterface $operation
     * @return void
     */
    public function setPayload(ParsableInterface $operation)
    {
        // Setup params
        $this->operation = OperationParser::getName($operation);
        $this->path = $this->path . strtolower($this->operation);
        $this->target = "{$this->target}.{$this->operation}";
        $host = self::HOST . ".{$this->locale}";
        $this->payload = OperationParser::toString($operation);

        // Setup headers
        $this->setRequestHeader('host', $host);
        $this->setRequestHeader('x-amz-target', $this->target);
        $this->setRequestHeader('x-amz-date', $this->timestamp);

        $headers = $this->getHeaders();
        $headerString = '';

        foreach ( $headers as $key => $value ) {
            $headerString .= "{$key}:{$value}\r\n";
        }

        $this->endpoint = "https://{$host}{$this->path}";
        $this->params = [
            'http' => [
                'method'  => 'POST',
                'header'  => $headerString,
                'content' => $this->payload
            ]
        ];
    }

    /**
     * @access public
     * @param RequestClientInterface $client
     * @return void
     */
    public function setClient(RequestClientInterface $client = null)
    {
        if ( !$this->client ) {
            if ( !( $this->client = $client) ) {
                $this->client = new RequestClient(
                    $this->getEndpoint(),
                    $this->getParams()
                );
            }
        }
    }
    
    /**
     * @access public
     * @param string $timestamp
     * @return object
     */
    public function setTimeStamp($timestamp = null)
    {
        $this->timestamp = ($timestamp) ? $timestamp : gmdate('Ymd\THis\Z');
        return $this;
    }

    /**
     * @access public
     * @param string $date
     * @return object
     */
    public function setDate($date = null)
    {
        $this->currentDate = ($date) ? $date : gmdate('Ymd');
        return $this;
    }

    /**
     * @access public
     * @param void
     * @return object
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @access public
     * @param void
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @access public
     * @param void
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @access public
     * @param void
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set request header
     *
     * @access public
     * @param string $name
     * @param string $value
     * @return void
     */
    public function setRequestHeader($name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * Set locale
     *
     * @access public
     * @param string $locale
     * @return object
     * @see https://webservices.amazon.fr/paapi5/documentation/locale-reference.html
     */
    public function setLocale($locale = 'com')
    {
        $locale = strtolower($locale);
        foreach ($this->getRegions() as $name => $value) {
            if ( in_array($locale,$value) ) {
                $this->locale = $locale;
                $this->region = $name;
                break;
            } else {
                $this->locale = false;
            }
        }
        try {
            if ( !$this->locale ) {
                throw new RequestException($locale);
            }
        } catch (RequestException $e) {
            die($e->get(1));
        }
        return $this;
    }

    /**
     * Init request
     *
     * @access private
     * @param void
     * @return void
     */
    private function init()
    {
        $this->setTimeStamp();
        $this->setDate();
        $this->setRequestHeader('content-encoding','amz-1.0');
        $this->setRequestHeader('content-type','application/json; charset=utf-8');
    }

    /**
     * Get regions
     *
     * @access private
     * @param void
     * @return array
     */
    private function getRegions()
    {
        return [
            'eu-west-1' => ['fr','de','in','it','es','nl','com.tr','ae','sa','co.uk','se'],
            'us-east-1' => ['com','com.br','ca','com.mx'],
            'us-west-2' => ['com.au','co.jp','sg']
        ];
    }
}
