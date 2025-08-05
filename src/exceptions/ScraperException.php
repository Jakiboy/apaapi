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
 * Apaapi scraper exception.
 */
final class ScraperException extends \Exception
{
    public static function invalidKeyword(?string $item = null) : string
    {
        $item = $item ?: 'undefined';
        return "Invalid ASIN or ISBN keyword '{$item}'";
    }
}
