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

use Apaapi\interfaces\RequestInterface;

/**
 * Apaapi built-in cache helper.
 * For high-performance applications use a dedicated caching system like Memcached or Redis.
 * @see https://webservices.amazon.com/paapi5/documentation/best-programming-practices.html
 */
final class Cache
{
	private const TTL = 3600;
	private const SALT = '0RhuksYDF';
	private const EXT = 'db';

    /**
     * @access private
	 * @var int $ttl, Cache TTL
	 * @var string $salt, Cache salt
	 * @var string $ext, Cache extension
     */
	private static $ttl;
	private static $salt;
	private static $ext;

	/**
	 * Set custom cache TTL.
	 *
	 * @access public
	 * @param int $ttl
	 * @return void
	 */
	public static function setTtl(int $ttl = self::TTL)
	{
		self::$ttl = $ttl;
	}

	/**
	 * Set hash salt.
	 *
	 * @access public
	 * @param string $salt
	 * @return void
	 */
	public static function setSalt(string $salt = self::SALT)
	{
		self::$salt = $salt;
	}

	/**
	 * Set cache extension.
	 *
	 * @access public
	 * @param string $ext
	 * @return void
	 */
	public static function setExt(string $ext = self::EXT)
	{
		self::$ext = $ext;
	}

	/**
	 * Get request cache key.
	 *
	 * @access public
	 * @param RequestInterface $request
	 * @return string
	 */
	public static function getKey(RequestInterface $request) : string
	{
		$params  = $request->getParams();
		$payload = $params['payload'] ?? '';
		return self::generateKey((string)$payload);
	}

	/**
	 * Generate cache key.
	 *
	 * @access public
	 * @param string $item
	 * @return string
	 */
	public static function generateKey(string $item) : string
	{
		self::init();
		$item = self::$salt . $item;
		return hash('sha256', $item);
	}

	/**
	 * Get cache value.
	 *
	 * @access public
	 * @param string $key
	 * @return mixed
	 */
	public static function get(string $key)
	{
		if ( self::isCached($key) ) {
			$file = self::getFile($key);
			$value = @file_get_contents($file);
			return unserialize($value);
		}
		return false;
	}

	/**
	 * Set cache value.
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	public static function set(string $key, $value) : bool
	{
		self::init();
		if ( $key == self::$salt ) {
			return false;
		}
		$file = self::getFile($key);
		$value = serialize($value);
		return (bool)@file_put_contents($file, $value);
	}

	/**
	 * Check cache key.
	 *
	 * @access private
	 * @param string $key
	 * @return bool
	 */
	private static function isCached(string $key) : bool
	{
		self::init();
		$file = self::getFile($key);
		
		if ( @file_exists($file) ) {
			$time = time() - self::$ttl;
			if ( $time < filemtime($file) ) {
				return true;

			} else {
				@unlink($file);
			}
		}
		
		return false;
	}

	/**
	 * Get cache file.
	 * 
	 * @access private
	 * @param string $key
	 * @return string
	 */
	private static function getFile(string $key) : string
	{
		self::init();
		$path = sys_get_temp_dir();
		$path = "{$path}/{$key}." . self::$ext;
		return Normalizer::formatPath($path);
	}

	/**
	 * Init cache settings.
	 *
	 * @access private
	 * @return void
	 */
	private static function init()
	{
		if ( !self::$ttl ) {
			self::setTTL();
		}
		if ( !self::$salt ) {
			self::setSalt();
		}
		if ( !self::$ext ) {
			self::setExt();
		}		
	}
}
