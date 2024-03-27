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

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * Apaapi <Images> : High level resource.
 * @see https://webservices.amazon.com/paapi5/documentation/images.html
 */
final class Images extends Resource
{
	/**
	 * Set items.
	 */
	public function __construct()
	{
		$this->items = [
			'Primary.Small',
			'Primary.Medium',
			'Primary.Large',
			'Variants.Small',
			'Variants.Medium',
			'Variants.Large'
		];
	}
}
