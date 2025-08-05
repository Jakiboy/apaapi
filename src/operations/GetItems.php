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

namespace Apaapi\operations;

use Apaapi\lib\ItemOperation;
use Apaapi\resources\{
	BrowseNodeInfo,
	Images,
	ItemInfo,
	Offers,
	OffersV2,
	RentalOffers,
	CustomerReviews,
	ParentASIN
};
use Apaapi\includes\Parser;

/**
 * Apaapi <GetItems> operation.
 * @see https://webservices.amazon.com/paapi5/documentation/get-items.html
 */
final class GetItems extends ItemOperation
{
	/**
	 * @access public
	 * @var string $itemIdType
	 * @var array $itemIds
	 */
	public $itemIdType = 'ASIN';
	public $itemIds = [];

	/**
	 * Set resources.
	 */
	public function __construct(bool $isOffersV2 = false)
	{
		$this->resources = Parser::convert([
			new BrowseNodeInfo,
			new Images,
			new ItemInfo,
			$isOffersV2 ? new OffersV2 : new Offers,
			new RentalOffers,
			new CustomerReviews,
			new ParentASIN
		]);
	}

	/**
	 * Set item Id type.
	 *
	 * @access public
	 * @param string $type
	 * @return object
	 */
	public function setItemIdType(string $type) : object
	{
		$this->itemIdType = $type;
		return $this;
	}

	/**
	 * Set item Ids.
	 *
	 * @access public
	 * @param array $ids
	 * @return object
	 */
	public function setItemIds(array $ids) : object
	{
		$this->itemIds = $ids;
		return $this;
	}
}
