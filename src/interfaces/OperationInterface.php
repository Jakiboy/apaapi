<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi
 * @version   : 1.1.0
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\interfaces;

/**
 * Interface Group All Operations
 */
interface OperationInterface extends ParsableInterface
{
    /**
     * @param string $type
     * @return object
     */
    function setPartnerType($type);

	/**
	 * @param string $tag
	 * @return object
	 */
    function setPartnerTag($tag);

	/**
	 * @param array $resources
	 * @return object
	 */
    function setResources($resources);

    /**
     * @param array $languagesOfPreference
     * @return object
     */
    function setLanguages($languagesOfPreference);

    /**
     * @param string $marketplace
     * @return object
     */
    function setMarketplace($marketplace);
}
