<?php
/**
 * @package Amazon Product Advertising API
 * @copyright Copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\lib;

use Apaapi\interfaces\RequestInterface;

/**
 * Basic Apaapi Curl Wrapper Class
 */
class RequestClient
{
    /**
     * @access public
     * @var mixed $body Amazon API Response
     * @var mixed $error Response Error
     */
	public $body  = false;
	public $error = false;

     /**
     * @access private
     * @var RequestInterface $request
     * @var Curl $handler
     * @var boolean $forceSSL
     */
	private $request;
	private $handler;
	private $forceSSL = false;

    /**
     * @param RequestInterface $request
     * @param boolean $forceSSL
     * @param boolean $failOnError
     * @return void
     */
	public function __construct(RequestInterface $request, $forceSSL = false)
	{
		$this->request = $request;
		$this->forceSSL = $forceSSL;
		$this->init();
	}

    /**
     * @access private
     * @param void
     * @return void
     */
	private function init()
	{
		$this->handler = curl_init();
		curl_setopt($this->handler, CURLOPT_URL, "https://{$this->request->endpoint}");
		curl_setopt($this->handler, CURLOPT_POSTFIELDS, $this->request->params['http']['content']);
		curl_setopt($this->handler, CURLOPT_POST, true);
		curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->handler, CURLOPT_SSL_VERIFYHOST, $this->forceSSL);
		curl_setopt($this->handler, CURLOPT_SSL_VERIFYPEER, $this->forceSSL);
		curl_setopt($this->handler, CURLOPT_TIMEOUT, 10);
		$this->setHeader();
	}

    /**
     * @access private
     * @param void
     * @return void
     */
	private function close()
	{
		curl_close($this->handler);
	}

    /**
     * @access private
     * @param void
     * @return void
     */
	private function setHeader()
	{
		$header = explode("\n", $this->request->params['http']['header']);
		curl_setopt($this->handler, CURLOPT_HTTPHEADER, $header);
	}

    /**
     * @access private
     * @param void
     * @return boolean
     */
	private function isValid()
	{
		if ( !curl_errno($this->handler) ) {
			return true;
		}
		return false;
	}

    /**
     * @access private
     * @param void
     * @return int
     */
	private function getHttpCode()
	{
		return curl_getinfo($this->handler)['http_code'];
	}

    /**
     * @access private
     * @param void
     * @return string
     */
	private function getError()
	{
		return curl_error($this->handler);
	}

    /**
     * @access private
     * @param void
     * @return void
     */
	private function setHttpError()
	{
		switch ( $this->getHttpCode() ) {
			case 400:
			case 401:
			case 403:
			case 429:
				$this->error = $this->body;
				$this->body=false;
				break;
			default:
				$this->error = 'Unestablished Server Connection';
		}
	}

    /**
     * @access public
     * @param void
     * @return void
     */
	public function getBody()
	{
		// Exec Curl
		$response = curl_exec($this->handler);
		// Validate curl response
		if ( $this->isValid() ) {
			// merge Amazon JSON and http response
			$httpJson = array( 'Http' => array( 'ResponseCode' => $this->getHttpCode() ) );
			$this->body = json_encode( array_merge( json_decode($response, true), $httpJson) );
			// Amazon JSON only if merge failed
			if (JSON_ERROR_NONE !== json_last_error()) {
				$this->body = $response;
			}
			
			if ( $this->getHttpCode() != 200 ) {
				// Set Amazon API errors
				$this->setHttpError();
			}
		} else {
			// Get Curl errors
			$this->error = $this->getError();
		}
		// End Curl
		$this->close();
	}
}
