<?php
/**
 * @package Amazon Product Advertising API
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
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
}
