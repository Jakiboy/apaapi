<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Creators API Library
 * @version   : 2.1.x
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

Env::load('../.env.example');

// Init cart
$cart = new Cart();
$cart->setLocale(Env::get('LOCALE'))->setPartnerTag(Env::get('TAG'));

// Set items
$items = [
    Env::get('ASIN') => '3', // ({ASIN|ISBN} => {Quantity})
    Env::get('ISBN') => '5'
];

// Get response
$url = $cart->set($items); // String
var_dump($url);

// Any PR is welcome!
