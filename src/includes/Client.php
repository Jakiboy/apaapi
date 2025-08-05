<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.5.x
 * @copyright : (c) 2019 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

declare(strict_types=1);

namespace Apaapi\includes;

use Apaapi\interfaces\{ClientInterface, GatewayInterface};
use Apaapi\exceptions\{RequestException, ClientException};

/**
 * Apaapi HTTP request client (cURL|Stream).
 */
class Client implements ClientInterface
{
    /**
     * @access public
     */
    public const GET       = 'GET';
    public const POST      = 'POST';
    public const HEAD      = 'HEAD';
    public const TIMEOUT   = 10;
    public const REDIRECT  = 1;
    public const PATTERN   = [
        'status'    => '/^\s*HTTP\/\d+(\.\d+)?\s+(?P<code>\d+)\s*(?P<message>.*)?\r?\n?$/',
        'attribute' => '/^\s*(?P<name>[a-zA-Z0-9\-]+)\s*:\s*(?P<value>.*?)\s*(?:\r?\n|$)/'
    ];
    public const USERAGENT = [
        'Mozilla/5.0',
        '(X11; Linux x86_64)',
        'AppleWebKit/537.36',
        '(KHTML, like Gecko)',
        'Chrome/114.0.5735.199',
        'Safari/537.36'
    ];

    /**
     * @access protected
     * @var array $params Client parameters
     * @var string $method Request method
     * @var array $header Request header
     * @var string|array $body Request body
     * @var array $response Response data
     * @var string $baseUrl Base URL
     * @var string $url Request URL
     * @var string $gateway Request gateway
     */
    protected $params = [];
    protected $method;
    protected $header = [];
    protected $body = [];
    protected $response = [];
    protected $baseUrl;
    protected $url;
    protected $gateway;
    protected const CURL   = 'Curl';
    protected const STREAM = 'Stream';

    /**
     * @access private
     * @var array $pattern Parser pattern
     */
    private static $pattern = [];

    /**
     * Init client.
     *
     * @inheritdoc
     */
    public function __construct(?string $baseUrl = null, array $params = [])
    {
        $this->baseUrl = $baseUrl;
        $this->params = self::getParams($params);
        $this->setGateway();
        self::setPattern();
    }

    /**
     * Make Http request.
     *
     * @inheritdoc
     */
    public function request(string $method, string|array $body = [], array $header = [], ?string $url = null) : self
    {
        // Reset request
        $this->reset();

        // Set method
        $this->setMethod($method);

        // Set body
        $this->setBody($body);

        // Set header
        $this->setHeader($header);

        // Set URL
        $this->setUrl($url);

        // Execute request
        $this->execute();

        return $this;
    }

    /**
     * Make Http GET request.
     *
     * @inheritdoc
     */
    public function get(?string $url = null, string|array $body = [], array $header = []) : self
    {
        return $this->request(self::GET, $body, $header, $url);
    }

    /**
     * Make Http POST request.
     *
     * @inheritdoc
     */
    public function post(?string $url = null, string|array $body = [], array $header = []) : self
    {
        return $this->request(self::POST, $body, $header, $url);
    }

    /**
     * Make Http HEAD request.
     *
     * @inheritdoc
     */
    public function head(?string $url = null, string|array $body = [], array $header = []) : self
    {
        return $this->request(self::HEAD, $body, $header, $url);
    }

    /**
     * Set request method.
     *
     * @inheritdoc
     */
    public function setMethod(string $method) : self
    {
        $this->method = strtoupper($method);
        return $this;
    }

    /**
     * Set request header.
     *
     * @inheritdoc
     */
    public function setHeader(array $header = []) : self
    {
        $this->header = $header ?: $this->params['header'];
        return $this;
    }

    /**
     * Set request body (Data).
     *
     * @inheritdoc
     */
    public function setBody(string|array $body = []) : self
    {
        $this->body = $body ?: $this->params['body'];
        return $this;
    }

    /**
     * Set request URL (Append to base URL).
     *
     * @inheritdoc
     */
    public function setUrl(?string $url = null) : self
    {
        $url = (string)$url;
        $baseUrl = (string)$this->baseUrl;
        $this->url = Normalizer::untrailingSlash("{$baseUrl}/{$url}");
        return $this;
    }

    /**
     * Set request timeout.
     *
     * @inheritdoc
     */
    public function setTimeout(int $timeout) : self
    {
        $this->params['timeout'] = $timeout;
        return $this;
    }

    /**
     * Set request max redirections.
     *
     * @inheritdoc
     */
    public function setRedirect(int $redirect) : self
    {
        $this->params['redirect'] = $redirect;
        return $this;
    }

    /**
     * Set request encoding.
     *
     * @inheritdoc
     */
    public function setEncoding(?string $encoding = null) : self
    {
        $this->params['encoding'] = (string)$encoding;
        return $this;
    }

    /**
     * Set User-Agent.
     *
     * @inheritdoc
     */
    public function setUserAgent(?string $ua = null) : self
    {
        $this->params['ua'] = $ua;
        return $this;
    }

    /**
     * Allow cURL transfer return.
     *
     * @inheritdoc
     */
    public function return() : self
    {
        $this->params['return'] = true;
        return $this;
    }

    /**
     * Allow redirection follow.
     *
     * @inheritdoc
     */
    public function follow() : self
    {
        $this->params['follow'] = true;
        return $this;
    }

    /**
     * Allow cURL header in reponse.
     *
     * @inheritdoc
     */
    public function headerIn() : self
    {
        $this->params['headerIn'] = true;
        return $this;
    }

    /**
     * Get response data.
     *
     * @inheritdoc
     */
    public function getResponse() : array
    {
        return $this->response;
    }

    /**
     * Get response status (code, message).
     *
     * @inheritdoc
     */
    public function getStatus() : array
    {
        return $this->getResponse()['status'] ?? [];
    }

    /**
     * Get response status code.
     *
     * @inheritdoc
     */
    public function getStatusCode() : int
    {
        return $this->getStatus()['code'] ?? -1;
    }

    /**
     * Get response status message.
     *
     * @inheritdoc
     */
    public function getStatusMessage() : string
    {
        return $this->getStatus()['message'] ?? '';
    }

    /**
     * Get response header.
     *
     * @inheritdoc
     */
    public function getHeader() : array
    {
        return $this->getResponse()['header'] ?? [];
    }

    /**
     * Get response body.
     *
     * @inheritdoc
     */
    public function getBody(bool $decode = false) : mixed
    {
        $body = $this->getResponse()['body'] ?? '';
        if ( $decode ) {
            return Normalizer::decode($body) ?: [];
        }
        return $body;
    }

    /**
     * Check client error (Http|Gateway).
     *
     * @inheritdoc
     */
    public function hasError(int $httpCode = 400) : bool
    {
        // Check for Gateway error
        $error = $this->getResponse()['error'] ?? false;
        if ( $error ) return true;

        // Check for Http error
        return $this->getStatusCode() >= $httpCode;
    }

    /**
     * Set Http parser patterns.
     *
     * @inheritdoc
     */
    public static function setPattern(array $pattern = []) : void
    {
        self::$pattern = array_merge(self::PATTERN, $pattern);
    }

    /**
     * Get Http parser patterns.
     *
     * @inheritdoc
     */
    public static function getPattern(string $name) : string
    {
        return self::$pattern[$name] ?? '';
    }

    /**
     * Get request body data (query).
     *
     * @inheritdoc
     */
    public static function getQuery(array $body, ?string $url = null) : string
    {
        $query = [];
        foreach ($body as $key => $value) {
            $value = Normalizer::toString($value);
            if ( is_int($key) ) {
                $query[$value] = '';

            } else {
                $query[$key] = $value;
            }
        }

        $query = self::buildQuery($query, '', '$', 2);
        $query = str_replace('=&', '&', $query);
        $query = rtrim($query, '=');

        if ( $url && $query ) {
            $query = "{$url}?{$query}";
        }

        return $query;
    }

    /**
     * Format Http request header.
     *
     * @inheritdoc
     */
    public static function formatHeader(array $header, bool $assoc = false) : mixed
    {
        $format = '';
        foreach ($header as $key => $value) {
            $format .= "{$key}:{$value}\r\n";
        }
        if ( $assoc ) {
            $header = explode("\n", $format);
            $header = array_filter($header, fn($item) => !empty($item));
            return $header ?: [];
        }
        return $format;
    }

    /**
     * Get default User-Agent.
     *
     * @inheritdoc
     */
    public static function getUserAgent() : string
    {
        return implode(', ', static::USERAGENT);
    }

    /**
     * Get default Http client parameters.
     *
     * @inheritdoc
     */
    public static function getParams(array $params = []) : array
    {
        return array_merge([
            'header'   => [],
            'body'     => [],
            'method'   => null,
            'timeout'  => self::TIMEOUT,
            'redirect' => self::REDIRECT,
            'ua'       => self::getUserAgent(),
            'ssl'      => true,
            'encoding' => null,
            'return'   => false,
            'follow'   => false,
            'headerIn' => false
        ], $params);
    }

    /**
     * Check curl gateway.
     *
     * @access public
     * @return bool
     */
    public function isCurl() : bool
    {
        return $this->gateway == self::CURL;
    }

    /**
     * Check stream gateway.
     *
     * @access public
     * @return bool
     */
    public function isStream() : bool
    {
        return $this->gateway == self::STREAM;
    }

    /**
     * Check cURL status.
     *
     * @access public
     * @return bool
     */
    public static function hasCurl() : bool
    {
        return function_exists('curl_init');
    }

    /**
     * Check stream status.
     *
     * @access public
     * @return bool
     */
    public static function hasStream() : bool
    {
        $val = intval(ini_get('allow_url_fopen'));
        return (bool)$val;
    }

    /**
     * Execute request.
     *
     * @access protected
     * @return void
     */
    protected function execute() : void
    {
        $this->response = $this->gateway::request($this->url, [
            'method'   => $this->method,
            'header'   => $this->header,
            'body'     => $this->body,
            'timeout'  => $this->params['timeout'],
            'redirect' => $this->params['redirect'],
            'encoding' => $this->params['encoding'],
            'return'   => $this->params['return'],
            'follow'   => $this->params['follow'],
            'headerIn' => $this->params['headerIn'],
            'ua'       => $this->params['ua'],
            'ssl'      => self::isSsl()
        ]);
        $this->params = [];
    }

    /**
     * Set gateway.
     *
     * @access protected
     * @return void
     * @throws RequestException
     */
    protected function setGateway() : void
    {
        $this->gateway = match (true) {
            self::hasCurl()   => self::CURL,
            self::hasStream() => self::STREAM,
            default           => 'undefined'
        };

        if ( $this->gateway == 'undefined' ) {
            throw new RequestException(
                RequestException::invalidGateway()
            );
        }

        $ns = __NAMESPACE__;
        $gateway = "{$ns}\\{$this->gateway}";
        $this->gateway = new $gateway;

        if ( !$this->gateway instanceof GatewayInterface ) {
            throw new ClientException(
                ClientException::invalidGateway($gateway)
            );
        }
    }

    /**
     * Reset request params.
     *
     * @access protected
     * @return void
     */
    protected function reset() : void
    {
        $this->header = [];
        $this->body = [];
        $this->response = [];
    }

    /**
     * Check SSL status.
     * 
     * @access private
     * @return bool
     */
    private static function isSsl() : bool
    {
        if ( isset($_SERVER['HTTPS']) ) {

            $isOn = strtolower($_SERVER['HTTPS']) === 'on';
            $isOne = $_SERVER['HTTPS'] === '1';

            if ( $isOn || $isOne ) {
                return true;
            }

        } elseif ( isset($_SERVER['SERVER_PORT']) ) {
            return $_SERVER['SERVER_PORT'] === '443';
        }

        return false;
    }

    /**
     * Build query args from string.
     *
     * @access private
     * @param mixed $args
     * @param string $prefix, Numeric index for args (array)
     * @param string $sep, Args separator
     * @param int $enc, Encoding type
     * @return string
     */
    private static function buildQuery(mixed $args, string $prefix = '', ?string $sep = '&', int $enc = 1) : string
    {
        return http_build_query($args, $prefix, $sep, $enc);
    }
}
