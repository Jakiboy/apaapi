<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi
 * @version   : 1.0.9
 * @copyright : (c) 2019 - 2021 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib
 */

namespace Apaapi\exceptions;

/**
 * Basic Apaapi Operation Exception Class
 */
final class RequestException extends MainException
{
	/**
	 * @access protected
	 * @var int $code
	 * @return string
	 */
	protected function getError($code)
	{
		$code = intval($code);
		switch ($code) {
			case 1:
				return 'Invalid Request Locale';
				break;
		}
	}
}
