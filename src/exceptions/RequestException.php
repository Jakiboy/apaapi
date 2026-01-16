<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Creators API Library
 * @version   : 2.0.x
 * @copyright : (c) 2019 - 2026 Jihad Sinnaour <me@jihadsinnaour.com>
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
