<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.5.x
 * @copyright : (c) 2019 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
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
	public static function invalidGateway() : string
	{
		return 'Invalid Http Gateway (Missing cURL and Stream functions)';
	}

	public static function invalidLocale(?string $item = null) : string
	{
		$item = $item ?: 'undefined';
		return "Invalid request locale '{$item}'";
	}
}
