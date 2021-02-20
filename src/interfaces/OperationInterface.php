<?php
/**
 * @package Amazon Product Advertising API
 * @version 1.0.7
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\interfaces;

/**
 * Interface Group All Operations
 */
interface OperationInterface
{
	/**
	 * @param string $tag
	 * @return object
	 */
    function setPartnerTag($tag);

	/**
	 * @param string $type
	 * @return object
	 */
    function setPartnerType($type);

	/**
	 * @param array $resources
	 * @return object
	 */
    function setResources($resources);
}
