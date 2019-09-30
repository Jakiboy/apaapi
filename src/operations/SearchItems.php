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

/**
 * Basic Paapi5 SearchItems Operation
 * @see https://webservices.amazon.com/paapi5/documentation/search-items.html
 */
class SearchItems extends Operation 
implements OperationInterface
{
	public function __construct()
	{
		$this->resources = [
			new BrowseNodeInfo,
			new Images,
			new ItemInfo,
			new Offers,
			new SearchRefinements,
			new ParentASIN
		];
	}
}
