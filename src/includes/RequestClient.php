<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.5
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\includes;

use Apaapi\interfaces\RequestClientInterface;
use Apaapi\exceptions\RequestException;

/**
 * Basic Apaapi Request Client Wrapper Class.
 */
class RequestClient implements RequestClientInterface
{
    /**
     * @access protected
     * @var string $endpoint, Request URL
     * @var array $params, Request params
     * @var mixed $handler, resource|array Request handler 
     * @var string $method, curl|stream
     * @var string $response, Request response content
     * @var string $error, Response error content
     * @var int $code, Response code
     */
    protected $endpoint;
    protected $params;
    protected $handler;
    protected $method;
    protected $response = false;
    protected $error = false;
    protected $code = 200;

    /**
     * @param string $endpoint
     * @param array $params
     * @param bool $throwClientException
     * @throws bool RequestException
     */
    public function __construct($endpoint, $params, $throwClientException = true)
    {
        if ( !self::hasCurl() && !self::hasStream() ) {
            if ( $throwClientException ) {
                throw new RequestException(
                    RequestException::invalidRequestClientMessage()
                );
            } else {
                $this->code = 0;
                $this->error = $this->setError(
                    'HTTP Client',
                    RequestException::invalidRequestClientMessage()
                );
            }
        }

        $this->endpoint = $endpoint;
        $this->params = $params;

        if ( self::hasCurl() ) {
            $this->method = 'curl';

        } elseif ( self::hasStream() ) {
            $this->method = 'stream';
        }

        $this->init();
    }

    /**
     * Get response including error(s).
     *
     * @access public
     * @param void
     * @return mixed
     */
    public function getResponse()
    {
        // Send request
        $this->send();

        // Return error
        if ( $this->hasError() ) {
            return $this->error;
        }

        // Return response
        return $this->response;
    }

    /**
     * Get HTTP response code.
     *
     * @access public
     * @param void
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * Close handler.
     *
     * @access public
     * @param void
     * @return void
     */
    public function close()
    {
        if ( $this->method == 'curl' ) {
            curl_close($this->handler);
        }
    }

    /**
     * Check if Curl activated,
     * Requires: "Curl" extension.
     * 
     * @access public
     * @param void
     * @return bool
     */
    public static function hasCurl()
    {
        return function_exists('curl_init');
    }

    /**
     * Check if Stream activated,
     * Requires: "allow_url_fopen" parameter.
     * 
     * @access public
     * @param void
     * @return bool
     */
    public static function hasStream()
    {
        return intval(ini_get('allow_url_fopen'));
    }

    /**
     * Init HTTP client.
     *
     * @access protected
     * @param void
     * @return void
     */
    protected function init()
    {
        if ( $this->method == 'curl' ) {
            $this->handler = curl_init();
            curl_setopt($this->handler, CURLOPT_URL, $this->endpoint);
            curl_setopt($this->handler, CURLOPT_HTTPHEADER, $this->getRequestHeader());
            curl_setopt($this->handler, CURLOPT_POSTFIELDS, $this->getRequestContent());
            curl_setopt($this->handler, CURLOPT_POST, true);
            curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($this->handler, CURLOPT_SSL_VERIFYPEER, $this->isSSL());
            curl_setopt($this->handler, CURLOPT_TIMEOUT, 30);

        } elseif ( $this->method == 'stream' ) {
            $this->handler = [
                'http' => [
                    'method'  => 'POST',
                    'header'  => $this->getRequestHeader(),
                    'content' => $this->getRequestContent(),
                    'timeout' => 30
                ]
            ];
        }
    }

    /**
     * Check for HTTP error.
     *
     * @access protected
     * @param void
     * @return bool
     */
    protected function hasError()
    {
       return (bool)$this->error;
    }

    /**
     * Normalize HTTP error,
     * Uses "Amazon API error" format (JSON).
     *
     * @access protected
     * @param string $method
     * @param string $error
     * @return string
     */
    protected function setError($method, $error)
    {
        return json_encode([
            '__type' => basename(__CLASS__) . 'Exception',
            'Errors' => [
                [
                    'Code'    => basename(__CLASS__) . "{$method}Error",
                    'Message' => "{$method}: {$error}."
                ]
            ]
        ]);
    }

    /**
     * Send request & catch errors.
     *
     * @access protected
     * @param void
     * @return void
     */
    protected function send()
    {
        if ( $this->method == 'curl' ) {

            // Get cURL response & HTTP status code
            $this->response = curl_exec($this->handler);
            $this->code = curl_getinfo($this->handler)['http_code'];

            // Catch cURL error content
            if ( curl_errno($this->handler) ) {
                $this->error = $this->setError(
                    'Curl',
                    curl_error($this->handler)
                );
            }

            // Catch HTTP error content from response
            if ( $this->response && $this->code !== 200 ) {
                $this->error = $this->response;
                $this->response = false;
            }

        } elseif ( $this->method == 'stream' ) {

            // Create stream context
            $context = stream_context_create($this->handler);

            // Get stream response
            $this->response = @file_get_contents($this->endpoint,false,$context);

            // Catch HTTP response headers
            $headers = @get_headers($this->endpoint,false,$context);

            if ( isset($headers[0]) ) {

                // Catch HTTP status code from headers
                $this->code = (int)substr($headers[0],9,3);

                // Catch HTTP error content from headers
                if ( !$this->response || $this->code !== 200 ) {
                    $this->error = $this->setError(
                        'Stream',
                        $headers[0]
                    );
                }

            } else {
                $this->code = 0;
                $this->error = $this->setError(
                    'Stream',
                    'Failed to open stream, operation failed'
                );
            }
        }
    }

    /**
     * Check SSL.
     * 
     * @access protected
     * @param void
     * @return bool
     */
    protected function isSSL()
    {
        if ( isset($_SERVER['HTTPS']) ) {
            if ( strtolower($_SERVER['HTTPS']) === 'on' ) {
                return true;
            }
            if ( $_SERVER['HTTPS'] == '1' ) {
                return true;
            }
        } elseif ( isset($_SERVER['SERVER_PORT']) 
            && ( $_SERVER['SERVER_PORT'] == '443' ) ) {
            return true;
        }
        return false;
    }

    /**
     * Get request content.
     * 
     * @access protected
     * @param void
     * @return mixed
     */
    protected function getRequestContent()
    {
        return isset($this->params['http']['content'])
        ? $this->params['http']['content'] : '';
    }

    /**
     * Get request header.
     * 
     * @access protected
     * @param void
     * @return mixed
     */
    protected function getRequestHeader()
    {
        $header = isset($this->params['http']['header'])
        ? $this->params['http']['header'] : [];
        return explode("\n",$header);
    }
}
