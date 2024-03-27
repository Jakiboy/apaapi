<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.2.0
 * @copyright : (c) 2019 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

declare(strict_types=1);

namespace Apaapi\includes;

/**
 * Apaapi static helper.
 * @see https://webservices.amazon.com/paapi5/documentation/locale-reference.html
 */
final class Provider
{
    /**
     * @access public
     * @var string HOST, Dynamic API host
     * @var array CONDITIONS, API product conditions
     */
    public const HOST = 'https://www.amazon.{locale}';
    public const CONDITIONS = ['Any', 'New', 'Used', 'Refurbished', 'Collectible'];
    
    /**
     * Get request regions.
     *
     * @access public
     * @return array
     */
    public static function getRegions() : array
    {
        return self::load('regions');
    }

    /**
     * Get request region by locale.
     *
     * @access public
     * @param string $locale
     * @return string
     */
    public static function getRegion(string $locale) : string
    {
        $default = 'eu-west-1';
        foreach (self::getRegions() as $name => $region) {
            if ( in_array($locale, $region) ) {
                $default = $name;
                break;
            }
        }
        return $default;
    }

    /**
     * Get request locales.
     *
     * @access public
     * @return array
     */
    public static function getLocales() : array
    {
        $locales = [];
        foreach (self::getRegions() as $region => $locale) {
            $locales = array_merge($locales, $locale);
        }
        return $locales;
    }

    /**
     * Get languages.
     *
     * @access public
     * @param string $locale
     * @return array
     */
    public static function getLanguages(?string $locale = null) : array
    {
        $languages = self::load('languages');
        if ( $locale ) {
            return $languages[$locale] ?? [];
        }
        return $languages;
    }

    /**
     * Get country.
     *
     * @access public
     * @param string $locale
     * @return string
     */
    public static function getCountry(string $locale) : string
    {
        return self::getCountries()[$locale] ?? '';
    }

    /**
     * Get countries.
     *
     * @access public
     * @return array
     */
    public static function getCountries() : array
    {
        return self::load('countries');
    }

    /**
     * Get currency.
     *
     * @access public
     * @param string $locale
     * @return array
     */
    public static function getCurrency(string $locale) : array
    {
        return self::getCurrencies()[$locale] ?? [];
    }

    /**
     * Get currencies.
     *
     * @access public
     * @return array
     */
    public static function getCurrencies() : array
    {
        return self::load('currencies');
    }

    /**
     * Get symbols (currency).
     *
     * @access public
     * @return array
     */
    public static function getSymbols() : array
    {
        return self::load('symbols');
    }

    /**
     * Get symbol (currency).
     *
     * @access public
     * @param string $currency
     * @return string
     */
    public static function getSymbol(string $currency) : string
    {
        return self::getSymbols()[$currency] ?? '';
    }

    /**
     * Get static categories (Search Index).
     *
     * @access public
     * @param string $locale
     * @return array
     */
    public static function getCategories(?string $locale = null) : array
    {
        $categories = self::load('categories');
        if ( $locale ) {
            return $categories[$locale] ?? [];
        }
        return $categories;
    }

    /**
     * Get static category Id (Search Index).
     *
     * @access public
     * @param string $category
     * @param string $locale
     * @return string
     */
    public static function getCategoryId(string $category, string $locale) : string
    {
        $key = Cache::generateKey("category-id-{$category}-{$locale}");

        if ( !($cached = Cache::get($key)) ) {

            $categories = self::getCategories($locale);
            $column = array_column($categories, 'name');
            $index  = array_search($category, $column);
            if ( is_int($index) ) {
                $cached = $categories[$index]['id'] ?? '';
                Cache::set($key, $cached);
            }
        }

        return (string)$cached;
    }

    /**
     * Get node url.
     *
     * @access public
     * @param string $id
     * @return string
     */
    public static function getNodeUrl(string $id) : string
    {
        return self::HOST . "/b?node={$id}&tag={tag}";
    }

	/**
	 * Generate header.
	 *
	 * @access public
	 * @return string
	 */
    public static function generateHeader(string $locale = 'com') : string
	{
		$currency = 'USD';
		if ( $locale !== 'com' ) {
			$currency = self::getCurrency($locale)[0] ?? $currency;
		}

        $time = time() . 'l';

		$id  = mt_rand(100, 999);
		$id .= '-' . mt_rand(1000000, 9999999);
		$id .= '-' . mt_rand(1000000, 9999999);

        $cookie = [
            "i18n-prefs={$currency}",
            "session-id={$id}",
            "session-id-time={$time}"
        ];
        
        $header = [
            'Cookie'     => implode('; ', $cookie),
            'Connection' => 'close'
        ];

        return implode("\r\n", array_map(function($key, $value) {
            return "{$key}: {$value}";
        }, array_keys($header), $header));
    }

    /**
     * Load file.
     *
     * @access private
     * @param string $name
     * @return array
     */
    private static function load(string $name) : array
    {
        $file = __DIR__ . "/bin/{$name}.json";
        $data = [];
        if ( file_exists($file) ) {
            $content = @file_get_contents($file);
            $data = Normalizer::decode($content);
        }
        return $data;
    }
}
