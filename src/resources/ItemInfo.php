<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.7
 * @copyright : (c) 2019 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * ItemInfo : High Level Resource.
 * @see https://webservices.amazon.com/paapi5/documentation/item-info.html
 */
final class ItemInfo extends Resource
{
	/**
	 * @param void
	 */
	public function __construct()
	{
		$this->items = [
	        'ByLineInfo',
	        'ContentInfo',
	        'ContentRating',
	        'Classifications',
	        'ExternalIds',
	        'Features',
	        'ManufactureInfo',
	        'ProductInfo',
	        'TechnicalInfo',
	        'Title',
	        'TradeInInfo'
		];
	}
}
