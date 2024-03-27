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

/**
 * @see You can use Composer,
 * Or include Apaapi standalone autoloader here.
 */
include('../src/Autoloader.php');
\apaapi\Autoloader::init();

use Apaapi\includes\Builder;

// Prapere request
$builder = new Builder('{Your-key-id}', '{Your-secrect-key}', '{Your-partner-tag}', '{Your-locale}');

// Get response
$data = $builder->search('{Your-keywords}'); // Array
print_r($data);

// Any suggestions (PR) are welcome!
