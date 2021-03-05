<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API v5 Example
 * @version   : 1.0.8
 * @copyright : (c) 2019 - 2021 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib
 */

use Apaapi\operations\SearchItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;
use Apaapi\includes\ResponseType;

// Set Operation
$operation = new SearchItems();
$operation->setPartnerTag('{Your-partner-tag}')->setKeywords('{Your-keywords}');

// Prapere Request
$request = new Request('{Your-key-id}','{Your-secrect-key}');
$request->setLocale('{your-locale}')->setPayload($operation);

// Get Response
$response = new Response($request, new ResponseType('array'), Response::PARSE);
var_dump($response->get()); // Array ready to be used

// Hope you found this useful, any suggestions (Pulls) are welcome !
