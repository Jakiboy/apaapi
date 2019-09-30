<?php
/**
 * @package Amazon Product Advertising API v5
 * @copyright Copyright (c) 2019 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\interfaces;

/**
 * Basic Paapi5 Operation Interface
 */
interface OperationInterface
{
	/**
	 * @param string $tag
	 * @return object
	 */
    public function setPartnerTag($tag);

	/**
	 * @param string $keywords
	 * @return object
	 */
    public function setKeywords($keywords);

	/**
	 * @param array $resources
	 * @return object
     * @todo Parse & Validate Ressources
     * @todo Set Default Ressources
	 */
    public function setResources($resources);
}
