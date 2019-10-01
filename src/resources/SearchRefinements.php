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

/**
 * SearchRefinements : High Level Resource
 * @see https://webservices.amazon.com/paapi5/documentation/search-refinements.html
 */
class SearchRefinements extends Resource
{
	/**
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		$this->child = [
			'BrowseNode'       => ['Id','DisplayName','Bins'],
			'SearchIndex'      => ['Id','DisplayName','Bins'],
			'OtherRefinements' => false
		];
	}
}
