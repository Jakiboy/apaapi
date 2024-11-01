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
 * Apaapi operation exception.
 */
final class OperationException extends \Exception
{
	public static function invalidRessources(?string $item = null) : string
	{
		if ( !$item ) $item = 'undefined';
		return "Invalid operation resources '{$item}'";
	}
}
