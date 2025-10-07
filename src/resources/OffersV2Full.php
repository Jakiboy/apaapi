<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.5.x
 * @copyright : (c) 2019 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * Apaapi <OffersV2Full> : High level resource with DealDetails.
 * Full version of OffersV2 including DealDetails (supported only by GetItems).
 * @see https://webservices.amazon.com/paapi5/documentation/offersV2.html
 */
final class OffersV2Full extends Resource
{
    /**
     * Set items.
     */
    public function __construct()
    {
        $this->items = [
            'Listings.Availability',
            'Listings.Condition',
            'Listings.DealDetails',
            'Listings.IsBuyBoxWinner',
            'Listings.LoyaltyPoints',
            'Listings.MerchantInfo',
            'Listings.Price',
            'Listings.Type'
        ];
    }
}
