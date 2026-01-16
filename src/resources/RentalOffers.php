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
 * Apaapi <RentalOffers> : Sub-level resource.
 */
final class RentalOffers extends Resource
{
	/**
	 * Set items.
	 */
	public function __construct()
	{
		$this->items = [
			'listings.basePrice',
			'listings.condition',
			'listings.availability.maxOrderQuantity',
			'listings.availability.minOrderQuantity',
			'listings.availability.type',
			'listings.availability.message',
			'listings.condition.conditionNote',
			'listings.condition.subCondition',
			'listings.deliveryInfo.isAmazonFulfilled',
			'listings.deliveryInfo.isFreeShippingEligible',
			'listings.deliveryInfo.isPrimeEligible',
			'listings.deliveryInfo.shippingCharges',
			'listings.merchantInfo'
		];
	}
}
