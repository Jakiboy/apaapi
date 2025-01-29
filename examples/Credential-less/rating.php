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

use Apaapi\includes\Rating;

// Init Rating
$rating = new Rating('_ASIN_', '_LOCALE_', '__TAG__');
$data = $rating->get(); // Array
var_dump($data);

// Any PR is welcome!
