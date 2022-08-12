<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi
 * @version   : 1.1.0
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\interfaces;

/**
 * Basic Apaapi Request Client Interface
 */
interface RequestClientInterface
{
    /**
     * @param string $endpoint
     * @param array $params
     */
    function __construct($endpoint, $params);

    /**
     * @param void
     * @return void
     */
	function getResponse();

    /**
     * @param void
     * @return bool
     */
    function hasError();

    /**
     * @param void
     * @return void
     */
    function getCode();
    
    /**
     * @param void
     * @return void
     */
    function close();
}
