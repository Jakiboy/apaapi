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

namespace Apaapi;

/**
 * Apaapi standalone autoloader.
 */
final class Autoloader
{
	/**
	 * @access private
	 * @var bool $initialized
	 */
	private static $initialized = false;

	/**
	 * Register autoloader.
	 *
	 * @access private
	 */
	private function __construct()
	{
		spl_autoload_register([__CLASS__, 'autoload']);
		static::$initialized = true;
	}

	/**
	 * Unregister autoloader.
	 */
	public function __destruct()
	{
		spl_autoload_unregister([__CLASS__, 'autoload']);
	}

	/**
	 * Autoloader method.
	 *
	 * @access private
	 * @param string $class
	 * @return void
	 */
	private function autoload(string $class) : void
	{
		$namespace = __NAMESPACE__ . '\\';
		if ( strpos($class, $namespace) === 0 ) {
			$class = str_replace($namespace, '', $class);
			$class = dirname(__DIR__) . "/src/{$class}.php";
			$class = str_replace('\\', '/', $class);
			if ( file_exists($class) ) {
				require_once $class;
			}
		}
	}

	/**
	 * Initialize autoloader.
	 *
	 * @access public
	 * @return void
	 */
	public static function init() : void
	{
		if ( !static::$initialized ) {
			new self;
		}
	}
}
