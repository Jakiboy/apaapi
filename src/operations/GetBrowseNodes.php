<?php
/**
 * @package Amazon Product Advertising API
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\operations;

use Apaapi\lib\Operation;
use Apaapi\interfaces\OperationInterface;
use Apaapi\resources\BrowseNodes;

/**
 * Basic Apaapi GetBrowseNodes Operation
 * @see https://webservices.amazon.com/paapi5/documentation/getbrowsenodes.html
 */
class GetBrowseNodes extends Operation 
implements OperationInterface
{
	/**
	 * @access public
     *
	 * @var array $browseNodeIds
	 */
	public $browseNodeIds = [];

	/**
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		$this->resources = [
			new BrowseNodes
		];
	}

	/**
	 * @param string $browseNodeIds
	 * @return object
	 */
    public function setBrowseNodeIds($browseNodeIds)
    {
    	$this->browseNodeIds = $browseNodeIds;
    	return $this;
    }
}
