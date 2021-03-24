<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi
 * @version   : 1.0.9
 * @copyright : (c) 2019 - 2021 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib
 */

namespace Apaapi\interfaces;

/**
 * Basic Apaapi Response Interface
 */
interface ResponseTypeInterface
{
    /**
     * @param string $type
     * @return void
     */
	function __construct($type = 'Object');

    /**
     * @param string $response
     * @return mixed
     */
	function format($response);

    /**
     * @param object $response
     * @param string $operation
     * @return mixed
     */
    function parse($response, $operation);
}
