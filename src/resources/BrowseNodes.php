<?php
/**
 * @package Amazon Product Advertising API
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * BrowseNodes : High Level Resource
 * @see https://webservices.amazon.com/paapi5/documentation/browsenodes.html
 */
class BrowseNodes extends Resource
{
	/**
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		$this->child = [
			'Children',
			'Ancestor',
			'ContextFreeName',
			'DisplayName',
			'Id',
			'IsRoot',
			'SalesRank'
		];
	}
}
