<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.7
 * @copyright : (c) 2019 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

/**
 * @see You can use Composer,
 * Or include Apaapi Standalone Autoloader Here.
 */
include('../src/Autoloader.php');
\apaapi\Autoloader::init();

use Apaapi\operations\SearchItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;
use Apaapi\includes\ResponseType;

// Set Operation
$operation = new SearchItems();
$operation->setPartnerTag('{Your-partner-tag}')->setKeywords('{Your-keywords}');

// Prapere Request
$request = new Request('{Your-key-id}','{Your-secrect-key}');
$request->setLocale('{Your-locale}')->setPayload($operation);

// Get Response
$response = new Response($request, new ResponseType('array'), Response::PARSE);
var_dump($response->get()); // Array ready to be used

// Hope you found this useful, any suggestions (Pull requests) are welcome!
