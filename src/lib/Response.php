<?php
/**
 * @package Amazon Product Advertising API
 * @copyright Copyright (c) 2019 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\lib;

use Apaapi\interfaces\ResponseInterface;
use Apaapi\interfaces\RequestInterface;

/**
 * Basic Apaapi Response Wrapper Class
 */
class Response implements ResponseInterface
{
    /**
     * @access public
     * @var JSON|False $body, Amazon API Response
     */
	public $body = false;

    /**
     * @param RequestInterface $request
     * @return void
     */
	public function __construct(RequestInterface $request)
	{
		$stream = stream_context_create($request->params);
		$data = @fopen("https://{$request->endpoint}", 'rb', false, $stream);
		if ($data) {
			$response = @stream_get_contents($data);
			if ($response !== false) {
				$this->body = $response;
			}
		}
	}
}
