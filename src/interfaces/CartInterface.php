<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Creators API Library
 * @version   : 2.0.x
 * @copyright : (c) 2019 - 2026 Jihad Sinnaour <me@jihadsinnaour.com>
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
