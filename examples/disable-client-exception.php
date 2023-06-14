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
use Apaapi\includes\RequestClient;

/**
 * Create Custom Request Client Class.
 */
class MyRequestClient extends RequestClient
{
    public function __construct($endpoint, $params)
    {
        // Disable request client exception when both cURL & Stream functions are disabled
    	parent::__construct($endpoint,$params,false);
    }
}

// Set Operation
$operation = new SearchItems();
$operation->setPartnerTag('{Your-partner-tag}')->setKeywords('{Your-keywords}')
->setResources(['Images.Primary.Small','ItemInfo.Title','Offers.Listings.Price']);

// Prapere Request
$request = new Request('{Your-key-id}','{Your-secrect-key}');
$request->setLocale('{Your-locale}')->setPayload($operation);

// Set Custom Client after Payload
$request->setClient(
	new MyRequestClient($request->getEndpoint(), $request->getParams())
);

// Get Response
$response = new Response($request);
$data = $response->get(); // JSON error ready for parsing

// Get formated error without exception handling
if ( $response->hasError() ) {
	/**
	 * @param bool $single error
	 * @return string|array
	 */
	echo $response->getError(true); // Parsed error
}

// Hope you found this useful, any suggestions (Pull requests) are welcome!
