<?php
/**
 * @package Amazon Product Advertising API
 * @copyright Copyright (c) 2019 Jakiboy
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
    public function setCondition($condition);

    /**
     * @param string $currencyOfPreference
     * @return object
     */
    public function setCurrency($currencyOfPreference);

    /**
     * @param string $merchant
     * @return object
     */
    public function setMerchant($merchant);

    /**
     * @param string $offerCount
     * @return object
     */
    public function setOfferCount($offerCount);
}
