<?php
/**
 * @package Amazon Product Advertising API v5 Example
 * @copyright Copyright (c) 2019 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

use Apaapi\operations\SearchItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;

/**
 * @see With Three Easy Steps, 
 * You can Achieve Connection to API
 */

// Set Operation
$operation = new SearchItems();
$operation->setPartnerTag('{Your-partner-tag}')->setKeywords('{Your-keywords}')
->setResources(['Images.Primary.Small','ItemInfo.Title','Offers.Listings.Price']);

// Prapere Request
$request = new Request('{Your-key-id}','{Your-secrect-key}');
$request->setRegion('{your-region}')->setPayload($operation);

// Send Request & Get Response : JSON
$response = new Response($request);
echo $response->body;