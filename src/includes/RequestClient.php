<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi
 * @version   : 1.0.8
 * @copyright : (c) 2019 - 2021 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib
 */

namespace Apaapi\includes;

use Apaapi\interfaces\RequestClientInterface;

/**
 * Basic Apaapi Curl Wrapper Class
 */
class RequestClient implements RequestClientInterface
{
    /**
     * @access protected
     * @var object $endpoint Curl
     * @var object $handler Curl
     * @var object $response
     * @var bool $verifySSL
     */
    protected $endpoint;
    protected $handler;
    protected $response;
    protected $verifySSL;

    /**
     * @param string $endpoint
     * @param array $params
     */
	public function __construct($endpoint, $params)
	{
        $this->endpoint = $endpoint;
        $this->params = $params;
        $this->verifySSL = ($this->isHttps()) ? true : false;
        $this->init();
        $this->setHeader();
	}

    /**
     * Get Curl response, Includes Curl error
     *
     * @access public
     * @param void
     * @return mixed
     */
	public function getResponse()
	{
        $this->send();
        if ( $this->hasError() ) {
        	return $this->getError();
        }
        return $this->response;
	}

    /**
     * Return Curl error status
     *
     * @access public
     * @param void
     * @return bool
     */
    public function hasError()
    {
        if ( curl_errno($this->handler) ) {
            return true;
        }
        return false;
    }

    /**
     * Get Curl http response Code
     * Required opened curl handler
     *
     * @access public
     * @param void
     * @return int
     */
    public function getCode()
    {
        return curl_getinfo($this->handler)['http_code'];
    }
    
    /**
     * Close Curl
     *
     * @access public
     * @param void
     * @return void
     */
    public function close()
    {
        curl_close($this->handler);
    }

    /**
     * Return normalized Curl error
     * Uses Amazon API error format
     *
     * @access protected
     * @param void
     * @return string
     */
    protected function getError()
    {
        return json_encode([
            '__type' => basename(__CLASS__) . 'Exception',
            'Errors' => [
                [
                    'Code' => basename(__CLASS__) . 'CurlError',
                    'Message' => curl_error($this->handler) .'.'
                ]
            ]
        ]);
    }

    /**
     * Init Curl
     *
     * @access protected
     * @param void
     * @return void
     */
	protected function init()
	{
		$this->handler = curl_init();
		curl_setopt($this->handler, CURLOPT_URL, $this->endpoint);
		curl_setopt($this->handler, CURLOPT_POSTFIELDS, $this->params['http']['content']);
		curl_setopt($this->handler, CURLOPT_POST, true);
		curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->handler, CURLOPT_SSL_VERIFYPEER, $this->verifySSL);
		curl_setopt($this->handler, CURLOPT_TIMEOUT, 30);
	}

    /**
     * Execute Curl
     *
     * @access protected
     * @param void
     * @return void
     */
	protected function send()
	{
		$this->response = curl_exec($this->handler);
	}

    /**
     * Set Curl header
     *
     * @access protected
     * @param void
     * @return void
     */
	protected function setHeader()
	{
		$header = explode("\n", $this->params['http']['header']);
		curl_setopt($this->handler, CURLOPT_HTTPHEADER, $header);
	}

    /**
     * @access protected
     * @param void
     * @return bool
     */
    protected function isHttps()
    {
        if ( isset($_SERVER['HTTPS'])
            && !empty($_SERVER['HTTPS']) 
            && $_SERVER['HTTPS'] !== 'off' ) {
            return true;
        }
        return false;
    }
}
