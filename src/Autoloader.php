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
	 */
	public function __construct()
	{
		if ( !static::$initialized ) {
			spl_autoload_register([__CLASS__, 'autoload']);
			static::$initialized = true;
		}
	}

	/**
	 * Unregister autoloader.
	 */
	public function __destruct()
	{
		spl_autoload_unregister([__CLASS__, 'autoload']);
	}

	/**
	 * Restrict object clone.
	 */
    public function __clone()
    {
        die(__METHOD__.': Clone denied');
    }

	/**
	 * Restrict object unserialize.
	 */
    public function __wakeup()
    {
        die(__METHOD__.': Unserialize denied');
    }

	/**
	 * Initialize autoloader.
	 * 
	 * @access public
	 * @return void
	 */
	public static function init()
	{
		if ( !static::$initialized ) {
			new static;
		}
	}

	/**
	 * Autoloader method.
	 * 
	 * @access private
	 * @param string $class
	 * @return void
	 * @see https://www.php-fig.org/psr/psr-4/
	 * @see https://www.php-fig.org/psr/psr-0/
	 */
	private function autoload(string $class)
	{
	    if ( strpos($class, __NAMESPACE__ . '\\') === 0 ) {
	        $class = str_replace(__NAMESPACE__ . '\\', '', $class);
	        $class = str_replace('\\', '/', $class);
	        $root = str_replace('\\', '/', dirname(__DIR__));
	        require_once("{$root}/src/{$class}.php");
	    }
	}
}
