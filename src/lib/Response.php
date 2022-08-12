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

use Apaapi\interfaces\ResponseInterface;
use Apaapi\interfaces\RequestInterface;
use Apaapi\interfaces\ResponseTypeInterface;
use Apaapi\includes\ResponseType;

/**
 * Basic Apaapi Response Wrapper Class
 * Based on the Product Advertising API 5.0 Scratchpad
 * @see https://webservices.amazon.com/paapi5/scratchpad/index.html
 */
final class Response implements ResponseInterface
{
    /**
     * @access public
     */
	const PARSE = true;

    /**
     * @access private
     * @var object $client, Request client
     * @var int $statusCode, Response status code
     * @var mixed $body, Response body
     * @var bool $error, Data error
     */
	private $client;
	private $statusCode = 200;
	private $body = false;
	private $error = false;

    /**
     * @param RequestInterface $request
     * @param ResponseTypeInterface $type
     * @param bool $parse
     */
	public function __construct(RequestInterface $request, ResponseTypeInterface $type = null, $parse = null)
	{
		// Set HTTP client
		$request->setClient(); // default
		$this->client = $request->getClient();

		// Set response body
		$this->body = $this->client->getResponse();

		// Set response status code
		$this->statusCode = $this->client->getCode();

		// Close HTTP client
		$this->client->close();

		// Set data error on status 200
		if ( $this->hasDataError() ) {
			$this->error = true;
		}

		// Apply response format on success
		if ( !$this->hasError() && $type ) {
			if ( $parse ) {
				$this->body = $type->parse($this->body,$request->getOperation());
			}
			$this->body = $type->format($this->body);
		}
	}

    /**
     * Get response body
     *
     * @access public
     * @param void
     * @return mixed
     */
	public function get()
	{
		return $this->body;
	}

    /**
     * Get response error
     *
     * @see https://webservices.amazon.com/paapi5/documentation/troubleshooting/error-messages.html
     * @access public
     * @param bool $single
     * @return mixed
     */
	public function getError($single = false)
	{
		$error = false;
		if ( $this->hasError() ) {
			if ( ($response = ResponseType::decode((string)$this->body)) ) {
				foreach ($response->Errors as $err) {
					if ( $single ) {
						return $err->Message;
					}
					$error[] = $err->Message;
				}
			}
		}
		return $error;
	}

    /**
     * Check if response has any error (>=400)
     *
     * @access public
     * @param void
     * @return boolean
     */
	public function hasError()
	{
		if ( !$this->statusCode || $this->statusCode >= 400 ) {
			return true;
		} elseif ( $this->statusCode == 200 && $this->error ) {
			return true;
		}
		return false;
	}

    /**
     * Check if response has data error (==200)
     *
     * @access private
     * @param void
     * @return boolean
     */
	private function hasDataError()
	{
		if ( strpos($this->body,'#ErrorData') !== false ) {
			return true;
		}
		return false;
	}
}
