<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.3.x
 * @copyright : (c) 2019 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\lib;

use Apaapi\interfaces\{
    RequestInterface,
    ClientInterface,
    OperationInterface
};
use Apaapi\includes\{
    Client,
    Parser,
    Provider,
    Normalizer
};
use Apaapi\exceptions\RequestException;

/**
 * Apaapi request wrapper class.
 */
final class Request extends Signature implements RequestInterface
{
    /**
     * @access public
     * @var string HOST, API Host
     */
    public const HOST = 'webservices.amazon';

    /**
     * @access private
     * @var array $params
     * @var string $endpoint
     * @var Client $client
     * @var string $operation
     */
    private $params = [];
    private $endpoint;
    private $client;
    private $operation;

    /**
     * @inheritdoc
     */
    public function __construct(string $accessKeyID, string $secretAccessKey)
    {
        $this->accessKeyID = $accessKeyID;
        $this->secretAccessKey = $secretAccessKey;
        
        $this->setTimeStamp();
        $this->setDate();
        $this->setRequestHeader('content-encoding', 'amz-1.0');
        $this->setRequestHeader('content-type', 'application/json; charset=utf-8');
    }

    /**
     * @inheritdoc
     */
    public function setPayload(OperationInterface $operation)
    {
        // Setup params
        $this->operation = Parser::getName($operation);
        $this->payload   = Parser::convert($operation);
        $this->path      = $this->path . strtolower($this->operation);
        $this->target    = "{$this->target}.{$this->operation}";
        $host = self::HOST . ".{$this->locale}";

        // Setup headers
        $this->setRequestHeader('host', $host);
        $this->setRequestHeader('x-amz-target', $this->target);
        $this->setRequestHeader('x-amz-date', $this->timestamp);

        $headers = $this->getHeaders();
        $header  = '';

        foreach ( $headers as $key => $value ) {
            $header .= "{$key}:{$value}\n";
        }

        $this->endpoint = "https://{$host}{$this->path}";
        $this->params = [
            'method'  => 'POST',
            'header'  => $header,
            'payload' => $this->payload
        ];
    }

    /**
     * @inheritdoc
     */
    public function setClient(?ClientInterface $client = null)
    {
        if ( !($this->client = $client) ) {
            $this->client = new Client(
                $this->getEndpoint(),
                $this->getParams()
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function setTimeStamp(?string $timestamp = null) : object
    {
        $this->timestamp = ($timestamp) 
        ? $timestamp : gmdate('Ymd\THis\Z');
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setDate(?string $date = null) : object
    {
        $this->currentDate = ($date) 
        ? $date : gmdate('Ymd');
        return $this;
    }

    /**
     * Check valid client.
     *
     * @access public
     * @return bool
     */
    public function hasClient() : bool
    {
        $interface = 'Apaapi\interfaces\RequestInterface';
        return is_subclass_of($this->client, $interface);
    }

    /**
     * @inheritdoc
     */
    public function getClient() : object
    {
        return $this->client;
    }

    /**
     * @inheritdoc
     */
    public function getEndpoint() : string
    {
        return $this->endpoint;
    }

    /**
     * @inheritdoc
     */
    public function getParams() : array
    {
        return $this->params;
    }

    /**
     * @inheritdoc
     */
    public function getOperation() : string
    {
        return $this->operation;
    }

    /**
     * @inheritdoc
     */
    public function setRequestHeader(string $name, $value)
    {
        $this->headers[$name] = $value;
    }

    /**
     * @inheritdoc
     */
    public function setLocale(string $locale) : object
    {
        $this->locale = Normalizer::formatLocale($locale);
        $this->region = Provider::getRegion($locale);

        if ( !$this->locale ) {
            throw new RequestException(
                RequestException::invalidLocale($locale)
            );
        }
        
        return $this;
    }
}
