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

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * ItemInfo : High Level Resource
 * @see https://webservices.amazon.com/paapi5/documentation/item-info.html
 */
final class ItemInfo extends Resource
{
	/**
	 * @param void
	 * @return void
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
