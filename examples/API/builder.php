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

use Apaapi\includes\Builder;
use Apaapi\includes\Env;

Env::load('../.env');

// Prepare request
$builder = new Builder(Env::get('_CREDENTIAL_ID_'), Env::get('_CREDENTIAL_SECRET_'), Env::get('_TAG_'), Env::get('_LOCALE_'));

// Get response
$data = $builder->search(Env::get('_KEYWORDS_')); // Normalized Array

// Handle response error
if ( $builder->hasError() ) {
    echo "Error: " . $builder->getError() . PHP_EOL; // String
}

var_dump($data);

// Any PR is welcome!
