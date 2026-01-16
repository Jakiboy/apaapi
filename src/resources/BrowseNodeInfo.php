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

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * Apaapi <BrowseNodeInfo> : High level resource.
 * @see https://affiliate-program.amazon.com/creatorsapi/docs/en-us/browsenodeinfo.html
 */
final class BrowseNodeInfo extends Resource
{
	/**
	 * Set items.
	 */
	public function __construct()
	{
		$this->items = [
			'browseNodes',
			'browseNodes.ancestor',
			'browseNodes.salesRank',
			'websiteSalesRank'
		];
	}
}
