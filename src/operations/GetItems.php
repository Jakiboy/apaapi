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

namespace Apaapi\operations;

use Apaapi\lib\ItemOperation;
use Apaapi\resources\{
	BrowseNodeInfo,
	Images,
	ItemInfo,
	OffersV2,
	CustomerReviews,
	ParentASIN
};
use Apaapi\includes\Parser;

/**
 * Apaapi <GetItems> operation.
 * @see https://affiliate-program.amazon.com/creatorsapi/docs/en-us/get-items.html
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
	public function __construct()
	{
		$this->resources = Parser::convert([
			new BrowseNodeInfo,
			new Images,
			new ItemInfo,
			new OffersV2,
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
