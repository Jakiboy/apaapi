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
use Apaapi\includes\Client;

/**
 * Custom request client class.
 */
class MyClient extends Client
{
	// Enable request client exception
    public function __construct(string $endpoint, array $params = [])
    {
    	parent::__construct($endpoint, $params, true);
    }

	// Override handler behavior
	protected function setHandler()
	{
		if ( self::hasCurl() ) {

			// Override curl
			$this->handler = curl_init();
			curl_setopt($this->handler, CURLOPT_URL, $this->endpoint);
			curl_setopt($this->handler, CURLOPT_HTTPHEADER, $this->getRequestHeader());
			curl_setopt($this->handler, CURLOPT_POSTFIELDS, $this->getRequestContent());
			curl_setopt($this->handler, CURLOPT_POST, true);
			curl_setopt($this->handler, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($this->handler, CURLOPT_SSL_VERIFYPEER, true); // Force SSL
			curl_setopt($this->handler, CURLOPT_TIMEOUT, 10); // Set custom timeout

		} elseif ( self::hasStream() ) {

			// Override stream
            $this->handler = stream_context_create([
                'http' => [
                    'method'  => 'POST',
                    'header'  => $this->getRequestHeader(),
                    'content' => $this->getRequestContent(),
                    'timeout' => $this->timeout // Set custom timeout
                ]
            ]);
		}
	}
}

// Set operation
$operation = new SearchItems();
$operation->setPartnerTag('{Your-partner-tag}')->setKeywords('{Your-keywords}');

// Prapere request
$request = new Request('{Your-key-id}','{Your-secrect-key}');
$request->setLocale('{Your-locale}')->setPayload($operation);

// Set custom client after payload
$request->setClient(
	new MyClient($request->getEndpoint(), $request->getParams())
);

// Get response
$response = new Response($request);
$data = $response->get(); // Array
print_r($data);

// Any suggestions (PR) are welcome!
