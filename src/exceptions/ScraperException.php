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
