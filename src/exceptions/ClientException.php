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
