<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi
 * @version   : 1.0.8
 * @copyright : (c) 2019 - 2021 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib
 */

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * Images : High Level Resource
 * @see https://webservices.amazon.com/paapi5/documentation/images.html
 */
final class Images extends Resource
{
	/**
	 * @param void
	 * @return void
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
