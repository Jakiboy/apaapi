<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5) Example
 * @version   : 1.1.1
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

/**
 * @see You can use Composer, 
 * Or include Apaapi Autoloader Here.
 */

use Apaapi\operations\SearchItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;

// Set Operation
$operation = new SearchItems();
$operation->setPartnerTag('{Your-partner-tag}')->setKeywords('{Your-keywords}')
->setResources(['Images.Primary.Small','ItemInfo.Title','Offers.Listings.Price']);

// Prapere Request
$request = new Request('{Your-key-id}','{Your-secrect-key}');
$request->setLocale('{Your-locale}')->setPayload($operation);

// Get Response
$response = new Response($request);
echo $response->get(); // JSON ready to be parsed

// Hope you found this useful, any suggestions (Pull requests) are welcome!
