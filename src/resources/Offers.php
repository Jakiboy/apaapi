<?php
/**
 * @package Amazon Product Advertising API
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * Offers : High Level Resource
 * @see https://webservices.amazon.com/paapi5/documentation/offers.html
 */
class Offers extends Resource
{
	/**
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		$this->child = [
			'Listings' => [
				'Availability'       => ['MaxOrderQuantity','Message','MinOrderQuantity','Type'],
				'Condition'          => [
					'DisplayValue',
					'Label',
					'Locale',
					'Value',
					'SubCondition' => ['DisplayValue','Label','Locale','Value']
				],
				'DeliveryInfo'       => ['IsAmazonFulfilled','IsFreeShippingEligible','IsPrimeEligible'],
				'Id'                 => false,
				'IsBuyboxWinner'     => false,
				'LoyaltyPoints'      => ['Points'],
				'MerchantInfo'       => ['DefaultShippingCountry','Id','Name'],
				'Price'              => [
					'Amount',
					'Currency',
					'DisplayAmount',
					'PricePerUnit',
					'Savings' => ['Amount','Currency','DisplayAmount','Percentage','PricePerUnit']
				],
				'ProgramEligibility' => ['IsPrimeExclusive','IsPrimePantry'],
				'Promotions'         => ['Amount','Currency','DiscountPercent','DisplayAmount','PricePerUnit','Type'],
				'SavingBasis'        => ['Amount','Currency','DiscountPercent','PricePerUnit'],
				'ViolateMAP'         => false,
			],
			'Summaries' => [
				'Condition'          => ['DisplayValue','Label','Locale','Value'],
				'HighestPrice'       => ['Amount','Currency','DisplayAmount','PricePerUnit'],
				'LowestPrice'        => ['Amount','Currency','DisplayAmount','PricePerUnit'],
				'OfferCount'         => false,
			]
		];
	}
}
