<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi
 * @version   : 1.0.8
 * @copyright : (c) 2019 - 2021 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib
 */

namespace Apaapi\interfaces;

/**
 * Basic Apaapi Request Interface
 */
interface RequestInterface
{
	/**
	 * @param string $accessKeyID
	 * @param string $secretAccessKey
	 * @return void
	 */
	function __construct($accessKeyID = '', $secretAccessKey = '');

    /**
     * @param void
     * @return object
     */
    function getClient();

    /**
     * @param void
     * @return string
     */
    function getEndpoint();

    /**
     * @param void
     * @return array
     */
    function getParams();

    /**
     * @param void
     * @return string
     */
    function getOperation();

    /**
     * @param string $name
     * @param string $value
     * @return void
     */
    function setRequestHeader($name, $value);

    /**
     * @param string $locale
     * @return object
     */
    function setLocale($locale = 'com');
}
