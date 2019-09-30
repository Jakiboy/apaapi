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
 * VariationSummary : High Level Resource
 * @see https://webservices.amazon.com/paapi5/documentation/variation-summary.html
 */
class VariationSummary extends Resource
{
	/**
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		$this->child = [
			'VariationCount'      => false,
			'PageCount'           => ['Id','DisplayName','Bins'],
			'Price'               => ['HighestPrice','LowestPrice'],
			'VariationDimensions' => ['DisplayName','Locale','Name','Values']
		];
	}
}
