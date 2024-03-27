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

/**
 * @see You can use Composer,
 * Or include Apaapi standalone autoloader here.
 */
include('../src/Autoloader.php');
\apaapi\Autoloader::init();

use Apaapi\lib\Cart;

// init cart
$cart = new Cart();
$cart->setLocale('{Your-locale}');
$cart->setPartnerTag('{Your-partner-tag}');

// Set items
$items = [
    '{ASIN1|ISBN1}' => '3', // ({ASIN|ISBN} => {Quantity})
    '{ASIN2|ISBN2}' => '5'
];

// Get response
$url = $cart->set($items);
echo $url; // String

// Any suggestions (PR) are welcome!
