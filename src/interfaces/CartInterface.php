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
 * Cart interface.
 */
interface CartInterface
{
    /**
     * Set request locale.
     *
     * @param string $locale
     * @return object
     * @throws \Apaapi\exceptions\RequestException
     */
    function setLocale(string $locale) : object;

    /**
     * Set partner tag.
     *
     * @param string $tag
     * @return object
     */
    function setPartnerTag(string $tag) : object;

    /**
     * Set cart items.
     *
     * @param array $items
     * @return string
     */
    function set(array $items) : string;
}
