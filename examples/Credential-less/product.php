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

use Apaapi\includes\Product;
use Apaapi\includes\Env;

Env::load('../.env.example');

// Init Product
$product = new Product(Env::get('ASIN'), Env::get('LOCALE'), Env::get('TAG'));
$data = $product->get(); // Array
var_dump($data);

// Any PR is welcome!
