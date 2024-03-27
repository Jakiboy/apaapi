<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.2.0
 * @copyright : (c) 2019 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\lib;

use Apaapi\interfaces\ItemOperationInterface;

/**
 * Apaapi item operation wrapper class.
 */
class ItemOperation extends Operation implements ItemOperationInterface
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
     * @inheritdoc
     */
    public function setCondition(string $condition) : object
    {
        $this->condition = $condition;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setCurrency(string $currency) : object
    {
        $this->currencyOfPreference = $currency;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setMerchant(string $merchant) : object
    {
        $this->merchant = $merchant;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setOfferCount(int $count) : object
    {
        $this->offerCount = $count;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setProperties(string $properties) : object
    {
        $this->properties = $properties;
        return $this;
    }
}
