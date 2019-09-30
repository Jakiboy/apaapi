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
 * Basic Paapi5 GetVariations Operation
 * @see https://webservices.amazon.com/paapi5/documentation/get-variations.html
 */
class GetVariations extends Operation 
implements OperationInterface
{
	public function __construct()
	{
		$this->resources = [
			new BrowseNodeInfo,
			new Images,
			new ItemInfo,
			new Offers,
			new VariationSummary,
			new ParentASIN
		];
	}
}
