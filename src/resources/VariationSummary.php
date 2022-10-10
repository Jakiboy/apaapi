<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.3
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * VariationSummary : High Level Resource.
 * @see https://webservices.amazon.com/paapi5/documentation/variation-summary.html
 */
final class VariationSummary extends Resource
{
	/**
	 * @param void
	 */
	public function __construct()
	{
		$this->items = [
			'Price.HighestPrice',
			'Price.LowestPrice',
			'VariationDimension'
		];
	}
}
