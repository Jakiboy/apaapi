<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.3.x
 * @copyright : (c) 2019 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\exceptions;

/**
 * Apaapi request exception.
 */
final class RequestException extends \Exception
{
	public static function invalidClient() : string
	{
		return 'Could not send request (Missing cURL and Stream functions)';
	}

	public static function invalidLocale(?string $locale = null) : string
	{
		$locale = $locale ?: 'undefined';
		return "Invalid request locale '{$locale}'";
	}
}
