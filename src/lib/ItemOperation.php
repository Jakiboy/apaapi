<?php
/**
 * @package Amazon Product Advertising API
 * @version 1.0.7
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\lib;

use Apaapi\lib\Operation;

/**
 * Basic Apaapi Grouped Item Operation Wrapper Class
 */
class ItemOperation extends Operation
{
    /**
     * @access public
     *
     * @var string $condition
     * @var null|string $CurrencyOfPreference
     * @var int $offerCount
     * @var string $merchant
     */
    public $condition = 'Any';
    public $currencyOfPreference = null;
    public $offerCount = 1;
    public $merchant = 'All';

    /**
     * @access public
     * @param string $condition
     * @return object
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
        return $this;
    }

    /**
     * @access public
     * @param string $currencyOfPreference
     * @return object
     */
    public function setCurrency($currencyOfPreference)
    {
        $this->currencyOfPreference = $currencyOfPreference;
        return $this;
    }

    /**
     * @access public
     * @param string $merchant
     * @return object
     */
    public function setMerchant($merchant)
    {
        $this->merchant = $merchant;
        return $this;
    }

    /**
     * @access public
     * @param string $offerCount
     * @return object
     */
    public function setOfferCount($offerCount)
    {
        $this->offerCount = $offerCount;
        return $this;
    }
}
