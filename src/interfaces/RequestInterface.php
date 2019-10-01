<?php
/**
 * @package Amazon Product Advertising API
 * @copyright Copyright (c) 2019 Jakiboy
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
	public function __construct($accessKeyID, $secretAccessKey);

    /**
     * @access public
     * @param string $region
     * @return object
     */
    public function setRegion($region);
}
