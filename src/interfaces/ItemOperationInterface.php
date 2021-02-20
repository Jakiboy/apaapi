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

use Apaapi\interfaces\OperationInterface;

/**
 * Interface Group Items Operations
 */
interface ItemOperationInterface extends OperationInterface
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
}
