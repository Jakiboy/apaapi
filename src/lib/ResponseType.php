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

use Apaapi\interfaces\ResponseTypeInterface;
use Apaapi\interfaces\ResponseInterface;
use Apaapi\exceptions\ResponseTypeException;

/**
 * Basic Apaapi Response Json Parser
 */
class ResponseType implements ResponseTypeInterface
{
    /**
     * @access private
     * @var string $type
     */
	private $type;

    /**
     * @param string $type
     * @return void
     */
	public function __construct($type = 'object')
	{
		$this->type = $type;
	}

    /**
     * @access public
     * @param string $response
     * @return mixed
     */
	public function format($response)
	{
		if (strtolower($this->type) == 'object') {
			return json_decode($response);
		} elseif (strtolower($this->type) == 'array') {
			return json_decode($response, true);
		} else {
			throw new ResponseTypeException('Invalid response type [Object/Array]', 1);
		}
	}
}
