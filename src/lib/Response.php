<?php
/**
 * @package Amazon Product Advertising API v5
 * @copyright Copyright (c) 2019 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\lib;

use Apaapi\interfaces\ResponseInterface;
use Apaapi\interfaces\RequestInterface;

/**
 * Basic Paapi5 Response Wrapper Class
 */
class Response implements ResponseInterface
{
	public $body;

	public function __construct(RequestInterface $request)
	{
		$stream = stream_context_create($request->params);
		$data = @fopen("https://{$request->endpoint}", 'rb', false, $stream);
		if ( !$data ) {
		    throw new Exception( "Exception Occured" );
		}
		$response = @stream_get_contents($data);
		if ($response === false) {
		    throw new Exception( "Exception Occured" );
		}
		$this->body = $response;
	}
}
