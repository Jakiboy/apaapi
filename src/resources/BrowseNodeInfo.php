<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi
 * @version   : 1.1.0
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * BrowseNodeInfo : High Level Resource
 * @see https://webservices.amazon.com/paapi5/documentation/browsenodeinfo.html
 */
final class BrowseNodeInfo extends Resource
{
	/**
	 * @param void
	 */
	public function __construct()
	{
		$this->items = [
			'BrowseNodes',
			'BrowseNodes.Ancestor',
			'BrowseNodes.SalesRank',
			'WebsiteSalesRank'
		];
	}
}
