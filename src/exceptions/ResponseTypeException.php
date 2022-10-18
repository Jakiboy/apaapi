<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.4
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\exceptions;

/**
 * Basic Apaapi Response Type Exception Class.
 */
final class ResponseTypeException extends \Exception
{
	public static function invalidResponseTypeFormat($type = null)
	{
		return "Invalid response type format '{$type}', Expected (Object/Array/Serialized)";
	}
}
