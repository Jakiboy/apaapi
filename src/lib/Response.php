<?php
/**
 * @package Amazon Product Advertising API
 * @version 1.0.7
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\lib;

use Apaapi\interfaces\ResponseInterface;
use Apaapi\interfaces\RequestInterface;
use Apaapi\interfaces\ResponseTypeInterface;

/**
 * Basic Apaapi Response Wrapper Class
 * Based on the Product Advertising API 5.0 Scratchpad
 * @see https://webservices.amazon.com/paapi5/scratchpad/index.html
 */
class Response implements ResponseInterface
{
    /**
     * @access private
     * @var mixed $body Amazon API Response
     * @var mixed $error Response Error
     */
	private $body  = false;
	private $error = false;

    /**
     * @param RequestInterface $request
     * @return void
     */
	public function __construct(RequestInterface $request, ResponseTypeInterface $type = null)
	{
		$response = new RequestClient($request);
		$response->getBody();
		if ( $response->error ) {
			$this->error = $response->error;
		}
		// Response Format
		if ($type) {
			$this->body = $type->format($response->body);
		} else {
			$this->body = $response->body;
		}
	}

    /**
     * @access public
     * @param void
     * @return mixed
     */
	public function get()
	{
		return $this->body;
	}

    /**
     * Check Response Has Any Error
     * @access public
     * @param void
     * @return boolean
     */
	public function hasError()
	{
		// Has Global Error
		if ($this->error) {
			return true;
		}
		// Has Body Error
		$body = (object)$this->body;
		if ( isset($body->Errors) ) {
			$error = (object)$body->Errors[0];
			$this->error = $error->Message;
			return true;
		}
		return false;
	}

    /**
     * Get Response Error
     * @access public
     * @param void
     * @return string
     */
	public function getError()
	{
		return $this->error;
	}
}
