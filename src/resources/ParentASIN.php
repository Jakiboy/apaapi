<?php
/**
 * @package Amazon Product Advertising API v5
 * @copyright Copyright (c) 2019 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * ParentASIN : High Level Resource
 * @see https://webservices.amazon.com/paapi5/documentation/parent-asin.html
 */
class ParentASIN extends Resource
{
	/**
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		$this->child = __CLASS__;
	}
}
