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
 * Group items operations.
 */
interface ItemOperationInterface
{
    /**
     * Set condition.
     *
     * @param string $condition
     * @return object
     */
    function setCondition(string $condition) : object;

    /**
     * Set currency.
     *
     * @param string $currency
     * @return object
     */
    function setCurrency(string $currency) : object;

    /**
     * Set merchant.
     *
     * @param string $merchant
     * @return object
     */
    function setMerchant(string $merchant) : object;

    /**
     * Set offer count.
     *
     * @param int $count
     * @return object
     */
    function setOfferCount(int $count) : object;

    /**
     * Set properties.
     *
     * @param string $properties
     * @return object
     */
    function setProperties(string $properties) : object;
}
