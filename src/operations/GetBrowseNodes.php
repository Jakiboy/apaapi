<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.2.0
 * @copyright : (c) 2019 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\operations;

use Apaapi\lib\Operation;
use Apaapi\resources\BrowseNodes;
use Apaapi\includes\Parser;

/**
 * Apaapi <GetBrowseNodes> operation.
 * @see https://webservices.amazon.com/paapi5/documentation/getbrowsenodes.html
 */
final class GetBrowseNodes extends Operation
{
	/**
	 * @access public
	 * @var array $browseNodeIds
	 */
	public $browseNodeIds = [];

	/**
	 * Set resources.
	 */
	public function __construct()
	{
		$this->resources = Parser::convert([
			new BrowseNodes
		]);
	}

	/**
	 * Set browsing node Ids.
	 *
	 * @access public
	 * @param array $ids
	 * @return object
	 */
    public function setBrowseNodeIds(array $ids) : object
    {
    	$this->browseNodeIds = $ids;
    	return $this;
    }
}
