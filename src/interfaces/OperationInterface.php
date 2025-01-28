<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.3.x
 * @copyright : (c) 2019 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\interfaces;

/**
 * Group all operations.
 */
interface OperationInterface extends ParsableInterface
{
    /**
     * Set partner type.
     *
     * @param string $type
     * @return object
     */
    function setPartnerType(string $type) : object;

    /**
     * Set partner tag.
     *
     * @param string $tag
     * @return object
     */
    function setPartnerTag(string $tag) : object;

    /**
     * Set resources.
     *
     * @param array $resources
     * @return object
     * @throws \Apaapi\exceptions\OperationException
     */
    function setResources(array $resources) : object;

    /**
     * Set languages of preference.
     *
     * @param array $languagesOfPreference
     * @return object
     */
    function setLanguages(array $languagesOfPreference) : object;

    /**
     * Set marketplace.
     *
     * @param string $marketplace
     * @return object
     */
    function setMarketplace(string $marketplace) : object;
}
