<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.7
 * @copyright : (c) 2019 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\lib;

use Apaapi\exceptions\RequestException;

/**
 * Basic Apaapi Cart Wrapper Class.
 * @see https://webservices.amazon.com/paapi5/documentation/add-to-cart-form.html
 */
final class Cart
{
    /**
     * @access public
     * @var string HOST, API Host
     * @var string ENDPOINT, API Endpoint
     */
    const HOST = 'https://www.amazon.{locale}';
    const ENDPOINT = '/gp/aws/cart/add.html?AssociateTag={tag}';

    /**
     * @access private
     * @var mixed $locale
     * @var string $tag
     */
    private $locale = false;
    private $tag;

    /**
     * Set request locale.
     *
     * @access public
     * @param string $locale
     * @return object
     * @throws RequestException
     */
    public function setLocale($locale)
    {
        $locale = strtolower($locale);
        if ( in_array($locale, $this->getRegions()) ) {
            $this->locale = $locale;
        }
        if ( !$this->locale ) {
            throw new RequestException(
                RequestException::invalidRequestLocaleMessage($locale)
            );
        }
        return $this;
    }

    /**
     * Set request tag.
     *
     * @access public
     * @param string $tag
     * @return object
     */
    public function setPartnerTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * Add items to cart.
     *
     * @access public
     * @param array $items
     * @return string
     */
    public function add($items = [])
    {
        $url = self::HOST . self::ENDPOINT;
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
     * Get request regions.
     *
     * @access private
     * @param void
     * @return array
     */
    private function getRegions()
    {
        return [
            'fr','com.be','de','in','it','es','nl','pl','com.tr','ae','sa','co.uk','se','eg',
            'com','com.br','ca','com.mx',
            'com.au','co.jp','sg'
        ];
    }
}
