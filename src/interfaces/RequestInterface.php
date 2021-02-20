<?php
/**
 * @package Amazon Product Advertising API
 * @version 1.0.7
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
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
	function __construct($accessKeyID, $secretAccessKey);

    /**
     * @param string $region
     * @return object
     */
    function setRegion($region);
}
