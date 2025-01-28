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

use Apaapi\interfaces\{RequestInterface, ClientInterface, OperationInterface};
use Apaapi\includes\{Client, Parser, Provider, Normalizer};
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
     * @var string $host
     * @var string $operation
     * @var array $params
     * @var Client $client
     */
    private $host;
    private $operation;
    private $params = [];
    private $client;

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
    public function setPayload(OperationInterface $operation) : void
    {
        // Setup params
        $this->operation = Parser::getName($operation);
        $this->payload = Parser::convert($operation);

        // Setup host
        $this->setHost();
        $this->setPath();
        $this->setTarget();

        // Setup header
        $this->setRequestHeader('host', $this->host);
        $this->setRequestHeader('x-amz-target', $this->target);
        $this->setRequestHeader('x-amz-date', $this->timestamp);

        $this->params = [
            'method' => Client::POST,
            'header' => Client::formatHeader($this->getHeader(), true),
            'body'   => $this->payload
        ];
    }

    /**
     * @inheritdoc
     */
    public function setClient(?ClientInterface $client = null) : void
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
        $this->timestamp = $timestamp ?: gmdate('Ymd\THis\Z');
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setDate(?string $date = null) : object
    {
        $this->currentDate = $date ?: gmdate('Ymd');
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
        return "https://{$this->host}{$this->path}";
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
    public function setPath() : void
    {
        $this->path .= strtolower($this->operation);
    }

    /**
     * @inheritdoc
     */
    public function setTarget() : void
    {
        $this->target = "{$this->target}.{$this->operation}";
    }

    /**
     * @inheritdoc
     */
    public function setHost() : void
    {
        $this->host = self::HOST . ".{$this->locale}";
    }

    /**
     * @inheritdoc
     */
    public function getHost() : string
    {
        return $this->host;
    }

    /**
     * @inheritdoc
     */
    public function setRequestHeader(string $name, $value) : void
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
