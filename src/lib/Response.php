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

namespace Apaapi\lib;

use Apaapi\interfaces\{ResponseInterface, RequestInterface};
use Apaapi\includes\{Cache, Geotargeting, Normalizer};

/**
 * Basic Apaapi response wrapper class.
 * @see https://webservices.amazon.com/paapi5/scratchpad/index.html
 */
class Response implements ResponseInterface
{
	/**
	 * @access public
	 */
	public const NORMALIZE = true;
	public const NOCACHE   = false;

	/**
	 * @access protected
	 * @var int $code, Response code
	 * @var string $body, Response body
	 * @var array $data, Response data
	 */
	protected $code = 200;
	protected $body = false;
	protected $data = [];

	/**
	 * @inheritdoc
	 */
	public function __construct(RequestInterface $request, bool $normalize = false, bool $cache = true)
	{
		$cache ? $this->getCached($request) : $this->send($request);

		if ( !$this->hasError() ) {

			$this->data = Normalizer::decode($this->body);

			if ( $normalize ) {
				$this->data = Normalizer::get($this->data, $request->getOperation());
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function get(?array $geo = null) : array
	{
		if ( $geo ) {
			return (new Geotargeting($geo))->get($this->data);
		}
		return $this->data;
	}

	/**
	 * @inheritdoc
	 */
	public function getBody() : string
	{
		return (string)$this->body;
	}

	/**
	 * @inheritdoc
	 */
	public function getError() : string
	{
		return Normalizer::formatError(
			$this->getBody()
		);
	}

	/**
	 * @inheritdoc
	 */
	public function hasError() : bool
	{
		if ( !$this->code || $this->code >= 400 ) {
			return true;
		}

		if ( $this->code == 200 ) {
			// Check for both old and new error formats
			if ( strpos($this->body, '#ErrorData') !== false ) {
				return true;
			}
			// Check for new error format with __type and Errors fields
			if ( strpos($this->body, '__type') !== false && strpos($this->body, 'Errors') !== false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get cached response.
	 *
	 * @access protected
	 * @param RequestInterface $request
	 * @return void
	 */
	protected function getCached(RequestInterface $request) : void
	{
		$key = Cache::getKey($request);
		if ( ($cached = Cache::get($key)) ) {
			$this->body = $cached;

			// If cached response is an error, invalidate and resend
			if ( $this->hasError() ) {
				// Delete the cached error
				$this->send($request);
				if ( !$this->hasError() ) {
					Cache::set($key, $this->body);
				}
			}

		} else {
			$this->send($request);
			if ( !$this->hasError() ) {
				Cache::set($key, $this->body);
			}
		}
	}

	/**
	 * Send request.
	 *
	 * @access protected
	 * @param RequestInterface $request
	 * @return void
	 */
	protected function send(RequestInterface $request) : void
	{
		if ( !$request->hasClient() ) {
			$request->setClient();
		}

		$client = $request->getClient()->return()->post();

		$this->body = $client->getBody();
		$this->code = $client->getStatusCode();
	}
}
