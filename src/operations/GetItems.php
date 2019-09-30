<?php
/**
 * @package Amazon Product Advertising API v5
 * @copyright Copyright (c) 2019 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\operations;

use Apaapi\lib\Operation;
use Apaapi\interfaces\OperationInterface;
use Apaapi\resources\BrowseNodeInfo;
use Apaapi\resources\Images;
use Apaapi\resources\ItemInfo;
use Apaapi\resources\Offers;
use Apaapi\resources\ParentASIN;

/**
 * Basic Paapi5 GetItems Operation
 * @see https://webservices.amazon.com/paapi5/documentation/get-items.html
 */
class GetItems extends Operation 
implements OperationInterface
{
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
}
