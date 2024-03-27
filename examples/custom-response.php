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

use Apaapi\operations\SearchItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;

// Set operation
$operation = new SearchItems();
$operation->setPartnerTag('{Your-partner-tag}')->setKeywords('{Your-keywords}');

// Prapere request
$request = new Request('{Your-key-id}','{Your-secrect-key}');
$request->setLocale('{Your-locale}')->setPayload($operation);

// Get response
$response = new Response($request, new ResponseType('array'), Response::PARSE);
$body = $response->getBody(); // String
print_r($body);

// Hope you found this useful, any suggestions (Pull requests) are welcome!
