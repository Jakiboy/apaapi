<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.5.x
 * @copyright : (c) 2019 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * Apaapi <CustomerReviews> : Sub-level resource.
 */
final class CustomerReviews extends Resource
{
	/**
	 * Set items.
	 */
	public function __construct()
	{
		$this->items = [
			'Count',
			'StarRating'
		];
	}
}
