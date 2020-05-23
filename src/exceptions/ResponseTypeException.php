<?php
/**
 * @package Amazon Product Advertising API
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\exceptions;

use \Exception;

/**
 * Basic Apaapi Response Type Exception Class
 */
class ResponseTypeException extends Exception
{
    /**
     * @param void
     * @return string
     */
	public function __toString()
	{
		return __CLASS__ . " : [{$this->code}] {$this->message}";
	}
}
