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

use Apaapi\operations\SearchItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;
use Apaapi\includes\Client;
use Apaapi\includes\Env;

Env::load('../.env');

// Set operation
$operation = new SearchItems();
$operation->setPartnerTag(Env::get('_TAG_'));
$operation->setItemCount(3)->setKeywords(Env::get('_KEYWORDS_'));

// Prepare request
$request = new Request(Env::get('_CREDENTIAL_ID_'), Env::get('_CREDENTIAL_SECRET_'), Env::get('_VERSION_'));
$request->setLocale(Env::get('_LOCALE_'))->setPayload($operation);

// Get response
$response = new Response($request);

$body = $response->getBody(); // String

// Check error
if ( $response->hasError() ) {
    echo 'Error: ' . $response->getError() . PHP_EOL; // String
    exit;
}

$data = $response->get(); // Array
var_dump($data);

// Any PR is welcome!
