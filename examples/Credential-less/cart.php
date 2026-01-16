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

declare(strict_types=1);

include '../../src/Autoloader.php';

\Apaapi\Autoloader::init();

use Apaapi\lib\Cart;
use Apaapi\includes\Env;

Env::load('../.env');

// Init cart
$cart = new Cart();
$cart->setLocale(Env::get('_LOCALE_'))->setPartnerTag(Env::get('_TAG_'));

// Set items
$items = [
    Env::get('_ASIN_') => '3', // ({_ASIN_|_ISBN_} => {Quantity})
    Env::get('_ISBN_') => '5'
];

// Get response
$url = $cart->set($items); // String
var_dump($url);

// Any PR is welcome!
