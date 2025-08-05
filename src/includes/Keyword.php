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

declare(strict_types=1);

namespace Apaapi\includes;

/**
 * Apaapi Keyword Helper.
 */
final class Keyword
{
	/**
	 * Check GTIN (EAN, UPC, ISBN).
	 *
	 * /^(?:\d{8,14}|(?:\d{3}-)?\d{9}[\dX])$/
	 *
	 * @access public
	 * @param string $keyword
	 * @return bool
	 */
	public static function isGTIN(string $keyword) : bool
	{
		return (bool)preg_match('/^(?:\d{8,14}|(?:\d{3}-)?\d{9}[\dX])$/', $keyword);
	}

	/**
	 * Check EAN (EAN-8, EAN-13).
	 *
	 * /^\d{7}\d$/
	 * /^\d{12}\d$/
	 *
	 * @access public
	 * @param string $keyword
	 * @return bool
	 */
	public static function isEAN(string $keyword) : bool
	{
		if ( preg_match('/^\d{7}\d$/', $keyword) ) {
			return true;

		} elseif ( preg_match('/^\d{12}\d$/', $keyword) ) {
			return true;
		}
		return false;
	}

	/**
	 * Check UPC (UPC-E, UPC-A).
	 *
	 * /^\d{6,8}$/
	 * /^\d{12}$/
	 *
	 * @access public
	 * @param string $keyword
	 * @return bool
	 */
	public static function isUPC(string $keyword) : bool
	{
		if ( preg_match('/^\d{6,8}$/', $keyword) ) {
			return true;

		} elseif ( preg_match('/^\d{12}$/', $keyword) ) {
			return true;
		}
		return false;
	}

	/**
	 * Check ISBN (ISBN-10, ISBN-13).
	 *
	 * /^(?:\d{9}[\dX]|\d{13})$/
	 *
	 * @access public
	 * @param string $keyword
	 * @return bool
	 */
	public static function isISBN(string $keyword) : bool
	{
		return (bool)preg_match('/^(?:\d{9}[\dX]|\d{13})$/', $keyword);
	}

	/**
	 * Check ASIN.
	 *
	 * /^B0[A-Z0-9]{8}$/
	 *
	 * @access public
	 * @param string $keyword
	 * @return bool
	 */
	public static function isASIN(string $keyword) : bool
	{
		return (bool)preg_match('/^B0[A-Z0-9]{8}$/', $keyword);
	}

	/**
	 * Check barcode (GTIN, ASIN).
	 * 
	 * @access public
	 * @param string $keyword
	 * @return bool
	 */
	public static function isBarcode(string $keyword) : bool
	{
		if ( self::isGTIN($keyword) || self::isASIN($keyword) ) {
			return true;
		}
		return false;
	}

	/**
	 * Parse ISBN.
	 *
	 * /(?:\d{9}[\dX]|\d{13})/
	 *
	 * @access public
	 * @param string $keyword
	 * @return string
	 */
	public static function parseISBN(string $keyword) : string
	{
		preg_match('/(?:\d{9}[\dX]|\d{13})/', $keyword, $match);
		if ( $match ) {
			$keyword = $match[0] ?? '';
		}
		return (self::isISBN($keyword)) ? $keyword : '';
	}

	/**
	 * Parse ASIN.
	 *
	 * /B0[A-Z0-9]{8}/
	 *
	 * @access public
	 * @param string $keyword
	 * @return string
	 */
	public static function parseASIN(string $keyword) : string
	{
		preg_match('/B0[A-Z0-9]{8}/', $keyword, $match);
		if ( $match ) {
			$keyword = $match[0] ?? '';
		}
		return (self::isASIN($keyword)) ? $keyword : '';
	}

	/**
	 * Parse barcode (GTIN, ASIN).
	 *
	 * @access public
	 * @param string $keyword
	 * @return string
	 */
	public static function parseBarcode(string $keyword) : string
	{
		if ( ($isbn = self::parseISBN($keyword)) ) {
			$keyword = $isbn;

		} elseif ( ($asin = self::parseASIN($keyword)) ) {
			$keyword = $asin;
		}
		return $keyword;
	}
}
