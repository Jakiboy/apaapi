<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.6
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\exceptions;

/**
 * Basic Apaapi Request Exception Class.
 */
final class RequestException extends \Exception
{
	public static function invalidRequestClientMessage()
	{
		return 'Could not send request (Missing cURL and Stream functions)';
	}

	public static function invalidRequestLocaleMessage($local = null)
	{
		return "Invalid request locale '{$local}'";
	}
}
