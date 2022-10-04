<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.1
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\exceptions;

use \Exception;

/**
 * Basic Apaapi Main Exception Class
 */
class MainException extends Exception
{
	/**
	 * @access public
	 * @var int $code
	 * @return string
	 */
	public function get($code = 1)
	{
		$trace = $this->getTrace()[0];
		$class = basename($trace['class']);
		$message  = "[{$class}Exception][{$code}] Error : {$this->getError($code)}";
		$message .= "<br>Line : {$trace['line']} in {$trace['file']}";
		if ( $this->getMessage() ) {
			$message .= " ({$this->getMessage()})";
		}
		return $message;
	}
}
