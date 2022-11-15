<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.6
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
 * @see https://webservices.amazon.com/paapi5/documentation/offers.html
 */
final class Offers extends Resource
{
	/**
	 * @param void
	 */
	public function __construct()
	{
		$this->items = [
		    'Listings.Availability.MaxOrderQuantity',
		    'Listings.Availability.Message',
		    'Listings.Availability.MinOrderQuantity',
		    'Listings.Availability.Type',
		    'Listings.Condition',
		    'Listings.Condition.ConditionNote',
		    'Listings.Condition.SubCondition',
		    'Listings.DeliveryInfo.IsAmazonFulfilled',
		    'Listings.DeliveryInfo.IsFreeShippingEligible',
		    'Listings.DeliveryInfo.IsPrimeEligible',
		    'Listings.DeliveryInfo.ShippingCharges',
		    'Listings.IsBuyBoxWinner',
		    'Listings.LoyaltyPoints.Points',
		    'Listings.MerchantInfo',
		    'Listings.Price',
		    'Listings.ProgramEligibility.IsPrimeExclusive',
		    'Listings.ProgramEligibility.IsPrimePantry',
		    'Listings.Promotions',
		    'Listings.SavingBasis',
		    'Summaries.HighestPrice',
		    'Summaries.LowestPrice',
		    'Summaries.OfferCount'
		];
	}
}
