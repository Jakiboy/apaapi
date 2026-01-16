<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Creators API Library
 * @version   : 2.0.x
 * @copyright : (c) 2019 - 2026 Jihad Sinnaour <me@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * Apaapi <OffersV2> : High level resource.
 * @see https://affiliate-program.amazon.com/creatorsapi/docs/en-us/offersV2.html
 */
final class OffersV2 extends Resource
{
	/**
	 * Set items.
	 */
	public function __construct()
	{
		$this->items = [
			'listings.availability',
			'listings.condition',
			'listings.dealDetails',
			'listings.isBuyBoxWinner',
			'listings.loyaltyPoints',
			'listings.merchantInfo',
			'listings.price',
			'listings.type'
		];
	}
}
