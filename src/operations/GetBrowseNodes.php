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

use Apaapi\lib\Operation;
use Apaapi\resources\BrowseNodes;
use Apaapi\includes\Parser;

/**
 * Apaapi <GetBrowseNodes> operation.
 * @see https://affiliate-program.amazon.com/creatorsapi/docs/en-us/getbrowsenodes.html
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
