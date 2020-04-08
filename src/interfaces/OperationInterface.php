<?php
/**
 * @package Amazon Product Advertising API
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
    public function setPartnerTag($tag);

	/**
	 * @param string $type
	 * @return object
	 */
    public function setPartnerType($type);

	/**
	 * @param array $resources
	 * @return object
     * @todo Parse & Validate Ressources
     * @todo Set Default Ressources
	 */
    public function setResources($resources);
}
