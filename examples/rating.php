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

include('../src/Autoloader.php');
\apaapi\Autoloader::init();

use Apaapi\includes\Rating;

// Init Rating
$rating = new Rating('_ASIN_', '_LOCALE_');
$data = $rating->get(); // Array
var_dump($data);

// Any PR is welcome!
