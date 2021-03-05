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

namespace Apaapi\interfaces;

/**
 * Interface Group Items Operations
 */
interface ItemOperationInterface
{
    /**
     * @param string $condition
     * @return object
     */
    function setCondition($condition);

    /**
     * @param string $currencyOfPreference
     * @return object
     */
    function setCurrency($currencyOfPreference);

    /**
     * @param string $merchant
     * @return object
     */
    function setMerchant($merchant);

    /**
     * @param string $offerCount
     * @return object
     */
    function setOfferCount($offerCount);

    /**
     * @param string $properties
     * @return object
     */
    function setProperties($properties);
}
