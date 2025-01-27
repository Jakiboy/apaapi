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

declare(strict_types=1);

include '../src/Autoloader.php';
\apaapi\Autoloader::init();

use Apaapi\lib\Cart;

// Init cart
$cart = new Cart();
$cart->setLocale('_LOCALE_')->setPartnerTag('_TAG_');

// Set items
$items = [
    '_ASIN_' => '3', // ({_ASIN_|_ISBN_} => {Quantity})
    '_ISBN_' => '5'
];

// Get response
$url = $cart->set($items); // String
var_dump($url);

// Any PR is welcome!
