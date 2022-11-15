<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.5
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * Offers : High Level Resource.
 */
final class RentalOffers extends Resource
{
	/**
	 * @param void
	 */
	public function __construct()
	{
		$this->items = [
		    'Listings.BasePrice',
		    'Listings.Condition',
		    'Listings.Availability.MaxOrderQuantity',
		    'Listings.Availability.MinOrderQuantity',
		    'Listings.Availability.Type',
		    'Listings.Availability.Message',
		    'Listings.Condition.ConditionNote',
		    'Listings.Condition.SubCondition',
		    'Listings.DeliveryInfo.IsAmazonFulfilled',
		    'Listings.DeliveryInfo.IsFreeShippingEligible',
		    'Listings.DeliveryInfo.IsPrimeEligible',
		    'Listings.DeliveryInfo.ShippingCharges',
		    'Listings.MerchantInfo'
		];
	}
}
