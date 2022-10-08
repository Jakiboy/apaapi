<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.2
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\interfaces;

/**
 * Basic Apaapi Request Client Interface.
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
     * @return mixed
     */
	function getResponse();

    /**
     * @param void
     * @return int
     */
    function getCode();
    
    /**
     * @param void
     * @return void
     */
    function close();
}
