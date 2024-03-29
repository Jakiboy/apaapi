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
	protected function init()
	{
		if ( self::hasCurl() ) {

			// cURL override example
			$this->handler = curl_init();
			curl_setopt($this->handler, CURLOPT_URL, $this->endpoint);
			curl_setopt($this->handler, CURLOPT_HTTPHEADER, $this->getRequestHeader());
			curl_setopt($this->handler, CURLOPT_POSTFIELDS, $this->getRequestContent());
			curl_setopt($this->handler, CURLOPT_POST, true);
			curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($this->handler, CURLOPT_SSL_VERIFYPEER, true); // Force SSL instead of Auto
			curl_setopt($this->handler, CURLOPT_TIMEOUT, 10); // Custom Timeout instead of 30

		} elseif ( self::hasStream() ) {

			// Stream override example
            $this->handler = [
                'http' => [
                    'method'  => 'POST',
                    'header'  => $this->getRequestHeader(),
                    'content' => $this->getRequestContent(),
                    'timeout' => 10 // Custom Timeout instead of 30
                ]
            ];
		}
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
echo $response->get(); // JSON ready to be parsed

// Hope you found this useful, any suggestions (Pull requests) are welcome!
