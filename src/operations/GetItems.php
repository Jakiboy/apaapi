<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.2
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\operations;

use Apaapi\lib\ItemOperation;
use Apaapi\resources\BrowseNodeInfo;
use Apaapi\resources\Images;
use Apaapi\resources\ItemInfo;
use Apaapi\resources\Offers;
use Apaapi\resources\ParentASIN;
use Apaapi\includes\ResourceParser;

/**
 * Basic Apaapi GetItems Operation.
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
	 * @param void
	 */
	public function __construct()
	{
		$this->resources = ResourceParser::toString([
			new BrowseNodeInfo,
			new Images,
			new ItemInfo,
			new Offers,
			new ParentASIN
		]);
	}

	/**
	 * @access public
	 * @param array|string $type
	 * @return object
	 */
    public function setItemIdType($type)
    {
    	$this->itemIdType = (array)$type;
    	return $this;
    }

	/**
	 * @access public
	 * @param array|string $itemIds
	 * @return object
	 */
    public function setItemIds($itemIds)
    {
    	$this->itemIds = (array)$itemIds;
    	return $this;
    }
}
