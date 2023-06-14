<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.7
 * @copyright : (c) 2019 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi;

/**
 * Apaapi Standalone Autoloader.
 */
final class Autoloader
{
	/**
	 * @access private
	 * @var bool $initialized
	 */
	private static $initialized = false;

	/**
	 * Register Apaapi autoloader.
	 *
	 * @param void
	 */
	public function __construct()
	{
		if ( !static::$initialized ) {
			spl_autoload_register([__CLASS__,'autoload']);
			static::$initialized = true;
		}
	}

	/**
	 * Unregister Apaapi autoloader.
	 */
	public function __destruct()
	{
		spl_autoload_unregister([__CLASS__,'autoload']);
	}

	/**
	 * Restrict object clone.
	 */
    public function __clone()
    {
        die(__METHOD__.': Clone denied');
    }

	/**
	 * Restrict object clone.
	 */
    public function __wakeup()
    {
        die(__METHOD__.': Unserialize denied');
    }

	/**
	 * Autoloader method.
	 * 
	 * @access private
	 * @param string $class __CLASS__
	 * @return void
	 * @see https://www.php-fig.org/psr/psr-4/
	 * @see https://www.php-fig.org/psr/psr-0/
	 */
	private function autoload($class)
	{
	    if ( strpos($class, __NAMESPACE__ . '\\') === 0 ) {
	        $class = str_replace(__NAMESPACE__ . '\\', '', $class);
	        $class = str_replace('\\','/',$class);
	        $root = str_replace('\\','/',dirname(__DIR__));
	        require_once("{$root}/src/{$class}.php");
	    }
	}

	/**
	 * Initialize autoloader.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function init()
	{
		if ( !static::$initialized ) {
			new static;
		}
	}
}
