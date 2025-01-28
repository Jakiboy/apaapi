<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.3.x
 * @copyright : (c) 2019 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\exceptions;

/**
 * Apaapi client exception.
 */
final class ClientException extends \Exception
{
	public static function invalidGateway(?string $item = null) : string
	{
		$item = $item ?: 'undefined';
		return "The gateway '{$item}' does not implement GatewayInterface";
	}
}
