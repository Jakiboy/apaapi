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

/**
 * @see You can use Composer,
 * Or include Apaapi Standalone Autoloader Here.
 */
include('../src/Autoloader.php');
\apaapi\Autoloader::init();

use Apaapi\lib\Cart;

// Set Cart
$cart = new Cart();
$cart->setLocale('{Your-locale}');
$cart->setPartnerTag('{Your-partner-tag}');

// Set Items
$items = [
    '{ASIN1}' => '3', // ({ASIN} => {Quantity})
    '{ASIN2}' => '5'
];

// Get Response
$url = $cart->add($items);
var_dump($url); // String URL

// Hope you found this useful, any suggestions (Pull requests) are welcome!
