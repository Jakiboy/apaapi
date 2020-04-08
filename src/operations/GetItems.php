<?php
/**
 * @package Amazon Product Advertising API
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\operations;

use Apaapi\lib\ItemOperation;
use Apaapi\interfaces\OperationInterface;
use Apaapi\resources\BrowseNodeInfo;
use Apaapi\resources\Images;
use Apaapi\resources\ItemInfo;
use Apaapi\resources\Offers;
use Apaapi\resources\ParentASIN;

/**
 * Basic Apaapi GetItems Operation
 * @see https://webservices.amazon.com/paapi5/documentation/get-items.html
 */
class GetItems extends ItemOperation 
implements OperationInterface
{
	/**
	 * @access public
     *
	 * @var string $itemIdType
	 * @var array $itemIds
	 */
	public $itemIdType = 'ASIN';
	public $itemIds = [];

	/**
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		$this->resources = [
			new BrowseNodeInfo,
			new Images,
			new ItemInfo,
			new Offers,
			new ParentASIN,
		];
	}

	/**
	 * @param string $idType
	 * @return object
	 */
    public function setItemIdType($idType)
    {
    	$this->itemIdType = $idType;
    	return $this;
    }

	/**
	 * @param array $itemIds
	 * @return object
	 */
    public function setItemIds($itemIds)
    {
    	$this->itemIds = $itemIds;
    	return $this;
    }
}
