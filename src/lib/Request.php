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

namespace Apaapi\lib;

use Apaapi\interfaces\{RequestInterface, ClientInterface, OperationInterface};
use Apaapi\includes\{Client, Parser, Provider, Normalizer};
use Apaapi\exceptions\RequestException;

/**
 * Apaapi request wrapper class.
 * @see https://affiliate-program.amazon.com/creatorsapi/docs/
 */
final class Request extends OAuth implements RequestInterface
{
    /**
     * @access public
     * @var string HOST, API Host (Creators API)
     * @var string VERSION, Library version
     */
    public const HOST    = 'creatorsapi.amazon';
    public const VERSION = '2.0.x';

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
    public function __construct(string $credentialID, string $credentialSecret, ?string $version = null)
    {
        $this->credentialID = $credentialID;
        $this->credentialSecret = $credentialSecret;
        $this->version = $version;

        $this->setRequestHeader('content-type', 'application/json; charset=utf-8');
        $this->setRequestHeader('user-agent', $this->getUserAgent());
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

        // Setup headers
        $this->setRequestHeader('host', $this->host);
        $this->setRequestHeader('x-marketplace', $this->getMarketplace());

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
        $operationName = lcfirst($this->operation);
        $this->path .= '/' . $operationName;
    }

    /**
     * @inheritdoc
     */
    public function setTarget() : void
    {
        $this->target = strtolower($this->operation);
    }

    /**
     * @inheritdoc
     */
    public function setHost() : void
    {
        $this->host = self::HOST;
    }

    /**
     * @inheritdoc
     */
    public function getHost() : string
    {
        return $this->host;
    }

    /**
     * Get marketplace identifier from locale.
     *
     * @access private
     * @return string
     */
    private function getMarketplace() : string
    {
        return "www.amazon.{$this->locale}";
    }

    /**
     * Get user agent string for API requests.
     *
     * @access private
     * @return string
     */
    private function getUserAgent() : string
    {
        return 'apaapi-php-lib/' . self::VERSION;
    }

    /**
     * @inheritdoc
     */
    public function setLocale(string $locale) : object
    {
        $this->locale = Normalizer::formatLocale($locale);

        if ( !$this->locale ) {
            throw new RequestException(
                RequestException::invalidLocale($locale)
            );
        }

        if ( !$this->version ) {
            $this->version = Provider::getVersion($this->locale);
        }

        return $this;
    }
}
