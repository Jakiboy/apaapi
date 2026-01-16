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

use Apaapi\includes\Rating;
use Apaapi\includes\Env;

Env::load('../.env');

// Init Rating
$rating = new Rating(Env::get('_ASIN_'), Env::get('_LOCALE_'), Env::get('_TAG_'));
$data = $rating->get(); // Array
var_dump($data);

// Any PR is welcome!
