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

namespace Apaapi;

/**
 * Apaapi Standalone Autoloader.
 */
final class Autoloader
{
	/**
	 * Register Apaapi autoloader.
	 *
	 * @param void
	 */
	public function __construct()
	{
		spl_autoload_register([__CLASS__,'autoload']);
	}

	/**
	 * Unregister Apaapi autoloader.
	 *
	 * @param void
	 * @return void
	 */
	public function __destruct()
	{
		spl_autoload_unregister([__CLASS__,'autoload']);
	}

	/**
	 * Prevent object clone.
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
    public function __clone()
    {
        die(__METHOD__.': Clone denied');
    }

	/**
	 * Prevent object clone.
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
    public function __wakeup()
    {
        die(__METHOD__.': Unserialize denied');
    }

	/**
	 * Autoloader method.
	 * @see https://www.php-fig.org/psr/psr-0/
	 * @see https://www.php-fig.org/psr/psr-4/
	 * 
	 * @access private
	 * @param string $class __CLASS__
	 * @return void
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
		new self;
	}
}