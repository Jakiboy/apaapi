<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.2.0
 * @copyright : (c) 2019 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

declare(strict_types=1);

namespace Apaapi\includes;

use Apaapi\interfaces\ClientInterface;
use Apaapi\exceptions\RequestException;

/**
 * Apaapi request client wrapper class.
 */
class Client implements ClientInterface
{
    /**
     * @access private
     */
    private const CURL = 'curl';
    private const STREAM = 'stream';

    /**
     * @access protected
     * @var string $endpoint, Request URL
     * @var string $method, Request method
     * @var int $timeout, Request timeout
     * @var int $redirect, Request redirect
     * @var string $encoding, Request encoding
     * @var array $params, Request params
     * @var CurlHandle|ressource $handler, Request handler 
     * @var string $gateway, curl|stream
     * @var mixed $response, Request response content
     * @var bool $throwable, Request exception throw
     * @var string $error, Response error content
     * @var int $code, Response code
     */
    protected $endpoint;
    protected $method = 'POST';
    protected $timeout = 30;
    protected $redirect = false;
    protected $encoding = false;
    protected $params;
    protected $handler;
    protected $gateway;
    protected $throwable = false;
    protected $response = false;
    protected $error = false;
    protected $code = 200;

    /**
     * @inheritdoc
     */
    public function __construct(string $endpoint, array $params = [], bool $throwable = false)
    {
        $this->endpoint  = $endpoint;
        $this->params    = $params;
        $this->throwable = $throwable;
        $this->setGateway();
    }

    /**
     * @inheritdoc
     */
    public function setMethod(string $method) : self
    {
        $this->method = strtoupper($method);
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setTimeout(int $timeout) : self
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setRedirect(int $redirect) : self
    {
        $this->redirect = $redirect;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setEncoding(string $encoding) : self
    {
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getResponse() : string
    {
        $this->setHandler();
        $this->send();

        if ( $this->hasError() ) {
            return $this->error;
        }
        
        return (string)$this->response;
    }

    /**
     * @inheritdoc
     */
    public function getCode() : int
    {
        return $this->code;
    }
    
    /**
     * @inheritdoc
     */
    public function close()
    {
        if ( $this->isCurl() ) {
            curl_close($this->handler);

        } elseif ( $this->isStream() ) {
            fclose($this->handler);
        }
    }

    /**
     * Check curl status.
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
     * Set HTTP handler.
     *
     * @access protected
     * @return void
     */
    protected function setHandler()
    {
        if ( $this->isCurl() ) {

            $this->handler = curl_init();

            curl_setopt($this->handler, CURLOPT_URL, $this->endpoint);
            curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->handler, CURLOPT_HTTPHEADER, $this->getRequestHeader());
            curl_setopt($this->handler, CURLOPT_SSL_VERIFYPEER, $this->isSsl());
            curl_setopt($this->handler, CURLOPT_TIMEOUT, $this->timeout);

            if ( $this->encoding !== false ) {
                curl_setopt($this->handler, CURLOPT_ENCODING, $this->encoding);
            }

            if ( $this->redirect ) {
                curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($this->handler, CURLOPT_MAXREDIRS, $this->redirect);
            }

            if ( $this->isPost() ) {
                curl_setopt($this->handler, CURLOPT_POSTFIELDS, $this->getRequestPayload());
                curl_setopt($this->handler, CURLOPT_POST, true);
            }

        } elseif ( $this->isStream() ) {
            $this->handler = stream_context_create([
                'http' => [
                    'method'  => $this->method,
                    'header'  => $this->getRequestHeader(),
                    'content' => $this->getRequestPayload(),
                    'timeout' => $this->timeout
                ]
            ]);
        }
    }

    /**
     * Check client error.
     *
     * @access protected
     * @return bool
     */
    protected function hasError() : bool
    {
       return (bool)$this->error;
    }

    /**
     * Send request.
     *
     * @access protected
     * @return void
     */
    protected function send()
    {
        if ( $this->isCurl() ) {
            $this->sendCurl();

        } elseif ( $this->isStream() ) {
            $this->sendStream();
        }
    }

    /**
     * Check SSL status.
     * 
     * @access protected
     * @return bool
     */
    protected function isSsl() : bool
    {
        if ( isset($_SERVER['HTTPS']) ) {
            if ( strtolower($_SERVER['HTTPS']) === 'on' ) {
                return true;
            }
            if ( $_SERVER['HTTPS'] === '1' ) {
                return true;
            }

        } elseif ( isset($_SERVER['SERVER_PORT']) ) {
            return ($_SERVER['SERVER_PORT'] === '443');
        }
        return false;
    }

    /**
     * Set client gateway.
     *
     * @access protected
     * @return void
     * @throws RequestException
     */
    protected function setGateway()
    {
        if ( !self::hasCurl() && !self::hasStream() ) {
            $this->code = 0;
            $this->error = RequestException::invalidClient();
            if ( $this->throwable ) {
                throw new RequestException($this->error);
            }

        } else {
            if ( self::hasCurl() ) {
                $this->gateway = self::CURL;
    
            } elseif ( self::hasStream() ) {
                $this->gateway = self::STREAM;
            }
        }
    }

    /**
     * Get request payload.
     *
     * @access protected
     * @return string
     */
    protected function getRequestPayload() : string
    {
        $payload = $this->params['payload'] ?? '';
        return (string)$payload;
    }

    /**
     * Get request header.
     *
     * @access protected
     * @return array
     */
    protected function getRequestHeader() : array
    {
        $header = $this->params['header'] ?? '';
        if ( is_array($header)) {
            return $header;
        }
        $header = explode("\r\n", (string)$header);
        return ($header) ? $header : [];
    }

    /**
     * Send curl request.
     *
     * @access protected
     * @return void
     */
    protected function sendCurl()
    {
        $this->response = curl_exec($this->handler);
        $info = curl_getinfo($this->handler);
        $this->code = $info['http_code'] ?? 0;

        if ( curl_errno($this->handler) ) {
            $this->code  = 0;
            $this->error = curl_error($this->handler);
        }

        if ( $this->response && $this->code !== 200 ) {
            $this->error    = $this->response;
            $this->response = false;
        }
    }

    /**
     * Send stream request.
     *
     * @access protected
     * @return void
     */
    protected function sendStream()
    {
        $this->response = @file_get_contents($this->endpoint, false, $this->handler);
        $headers = @get_headers($this->endpoint, false, $this->handler);

        if ( $headers ) {
            $status = $headers[0] ?? '';
            $this->code = (int)substr($status, 9, 3);
            if ( !$this->response || $this->code !== 200 ) {
                $this->error = $status;
            }

        } else {
            $this->code  = 0;
            $this->error = 'Failed to open stream';
        }
    }
    
    /**
     * Check curl gateway.
     *
     * @access private
     * @return bool
     */
    private function isCurl() : bool
    {
        return ($this->gateway == self::CURL);
    }

    /**
     * Check stream gateway.
     *
     * @access private
     * @return bool
     */
    private function isStream() : bool
    {
        return ($this->gateway == self::STREAM);
    }

    /**
     * Check post method.
     *
     * @access private
     * @return bool
     */
    private function isPost() : bool
    {
        return ($this->method == 'POST');
    }
}
