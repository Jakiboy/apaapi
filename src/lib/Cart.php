<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.5.x
 * @copyright : (c) 2019 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\lib;

use Apaapi\exceptions\RequestException;
use Apaapi\interfaces\CartInterface;
use Apaapi\includes\{Provider, Normalizer};

/**
 * Basic Apaapi cart wrapper class.
 * @see https://webservices.amazon.com/paapi5/documentation/add-to-cart-form.html
 */
class Cart implements CartInterface
{
    /**
     * @access public
     * @var string ENDPOINT, Dynamic API endpoint
     */
    public const ENDPOINT = '/gp/aws/cart/add.html?AssociateTag={tag}';

    /**
     * @access protected
     * @var mixed $locale
     * @var string $tag
     * @var int $limit, Items limit
     */
    protected $locale = false;
    protected $tag;
    protected static $limit = 5;

    /**
     * @inheritdoc
     */
    public function setLocale(string $locale) : object
    {
        $this->locale = Normalizer::formatLocale($locale);
        if ( !$this->locale ) {
            throw new RequestException(
                RequestException::invalidLocale($locale)
            );
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setPartnerTag(string $tag) : object
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function set(array $items) : string
    {
        if ( static::$limit ) {
            $items = array_slice($items, 0, static::$limit);
        }

        $url = Provider::HOST . self::ENDPOINT;
        $url = str_replace('{locale}', $this->locale, $url);
        $url = str_replace('{tag}', $this->tag, $url);
        $i = 0;
        foreach ($items as $asin => $quantity) {
            $i++;
            $url .= "&ASIN.{$i}={$asin}&Quantity.{$i}={$quantity}";
        }
        return $url;
    }

    /**
     * Set limit.
     *
     * @access public
     * @param int $limit
     * @return void
     */
    public static function limit(int $limit) : void
    {
        self::$limit = $limit;
    }
}
