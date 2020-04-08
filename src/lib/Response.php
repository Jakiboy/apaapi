<?php
/**
 * @package Amazon Product Advertising API
 * @copyright (c) 2019 - 2020 Jakiboy
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
     * @var mixed $body Amazon API Response
     * @var mixed $error Response Error
     */
	public $body  = false;
	public $error = false;

    /**
     * @param RequestInterface $request
     * @return void
     */
	public function __construct(RequestInterface $request)
	{
		$response = new RequestClient($request);
		$response->getBody();
		if ($response->error) {
			$this->error = $response->error;
		}
		$this->body = $response->body;
	}
}
