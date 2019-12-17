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
 * Basic Apaapi ResponseCurl Wrapper Class
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
		$handler = curl_init();
		$header = explode("\n", $request->params["http"]["header"]);

		curl_setopt($handler, CURLOPT_URL, "https://{$request->endpoint}");
		curl_setopt($handler, CURLOPT_POSTFIELDS, ($request->params["http"]["content"]));
		curl_setopt($handler, CURLOPT_HTTPHEADER, $header);
		curl_setopt($handler, CURLOPT_POST, true);
		curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($handler, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($handler, CURLOPT_SSL_VERIFYPEER, false);

		$data = curl_exec($handler);

		if ($data) {
			if ($data !== false && strpos($data, 'Errors') === false) {
				$this->body = $data;
			}
		}
	}
}
