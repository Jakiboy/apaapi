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
use Apaapi\resources\BrowseNodes;

/**
 * Basic Paapi5 GetBrowseNodes Operation
 * @see https://webservices.amazon.com/paapi5/documentation/getbrowsenodes.html
 */
class GetBrowseNodes extends Operation 
implements OperationInterface
{
	public function __construct()
	{
		$this->resources = [
			new BrowseNodes
		];
	}
}
