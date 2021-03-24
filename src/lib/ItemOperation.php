<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi
 * @version   : 1.0.9
 * @copyright : (c) 2019 - 2021 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib
 */

namespace Apaapi\lib;

use Apaapi\interfaces\ItemOperationInterface;

/**
 * Basic Apaapi Grouped Item Operation Wrapper Class
 */
class ItemOperation extends Operation
implements ItemOperationInterface
{
    /**
     * @access public
     * @var string $condition
     * @var string $currencyOfPreference
     * @var string $merchant
     * @var int $offerCount
     * @var string $properties
     */
    public $condition = 'Any';
    public $currencyOfPreference = null;
    public $merchant = 'All';
    public $offerCount = 1;
    public $properties = null;

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

    /**
     * @access public
     * @param string $properties
     * @return object
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
        return $this;
    }
}
