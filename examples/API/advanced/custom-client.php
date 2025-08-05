<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.3.x
 * @copyright : (c) 2019 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

declare(strict_types=1);

include '../../../src/Autoloader.php';
\apaapi\Autoloader::init();

use Apaapi\operations\SearchItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;
use Apaapi\includes\Client;

/**
 * Custom request client class.
 * @deprecated
 */
class MyClient extends Client
{
	/**
	 * Override parent constructor.
	 *
	 * @inheritdoc
	 */
	public function __construct(string $endpoint, array $params = [])
	{
		// Enable request client exception
		parent::__construct($endpoint, $params, true);

		$this->timeout = 5;      // Custom timeout
		$this->redirect = 3;      // Custom redirection
		$this->encoding = 'gzip'; // Custom encoding
	}

	/**
	 * Override handler behavior.
	 *
	 * @inheritdoc
	 */
	protected function setHandler() : void
	{
		$this->handler = curl_init();
		curl_setopt_array($this->handler, [
			CURLOPT_URL            => $this->endpoint,
			CURLOPT_HTTPHEADER     => $this->getRequestHeader(),
			CURLOPT_POSTFIELDS     => $this->getRequestPayload(),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST           => true, // Force POST
			CURLOPT_SSL_VERIFYPEER => true, // Force SSL
			CURLOPT_FOLLOWLOCATION => true, // Force follow location
			CURLOPT_TIMEOUT        => $this->timeout,
			CURLOPT_ENCODING       => $this->encoding,
			CURLOPT_MAXREDIRS      => $this->redirect
		]);
	}
}

// Set operation
$operation = new SearchItems();
$operation->setPartnerTag('_TAG_')->setKeywords('_KEYWORDS_');

// Prapere request
$request = new Request('_KEY_', '_SECRET_');
$request->setLocale('_LOCALE_')->setPayload($operation);

// Set custom client after payload
$request->setClient(
	new MyClient($request->getEndpoint(), $request->getParams())
);

// Get response
$response = new Response($request);
$data = $response->get(); // Array
var_dump($data);

// Any PR is welcome!
