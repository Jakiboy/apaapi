<?php
/**
 * @package Amazon Product Advertising API
 * @copyright Copyright (c) 2019 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\resources;

use Apaapi\lib\Resource;
use Apaapi\resources\BrowseNodes;

/**
 * BrowseNodeInfo : High Level Resource
 * @see https://webservices.amazon.com/paapi5/documentation/browsenodeinfo.html
 */
class BrowseNodeInfo extends Resource
{
	/**
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		$this->child = [
			'BrowseNodes' => new BrowseNodes,
			'WebsiteSalesRank' => [
				'ContextFreeName',
				'DisplayName',
				'SalesRank'
			]
		];
	}
}
