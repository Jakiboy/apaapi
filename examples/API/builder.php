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

include '../../src/Autoloader.php';
\apaapi\Autoloader::init();

use Apaapi\includes\Builder;

// Prapere request
$builder = new Builder('_KEY_', '_SECRET_', '_TAG_', '_LOCALE_');

// Get response
$data = $builder->search('_KEYWORDS_'); // Normalized Array

// Handle response error
if ( $builder->hasError() ) {
    echo $builder->getError(); // String
}
var_dump($data);

// Any PR is welcome!
