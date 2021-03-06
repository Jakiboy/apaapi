<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi
 * @version   : 1.0.9
 * @copyright : (c) 2019 - 2021 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib
 */

namespace Apaapi\operations;

use Apaapi\lib\Operation;
use Apaapi\resources\BrowseNodes;
use Apaapi\includes\ResourceParser;

/**
 * Basic Apaapi GetBrowseNodes Operation
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
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		$this->resources = ResourceParser::toString([
			new BrowseNodes
		]);
	}

	/**
	 * @access public
	 * @param array|string $browseNodeIds
	 * @return object
	 */
    public function setBrowseNodeIds($browseNodeIds)
    {
    	$this->browseNodeIds = (array)$browseNodeIds;
    	return $this;
    }
}
