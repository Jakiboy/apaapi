<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.5.x
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
use Apaapi\includes\Env;

Env::load('../../.env');

/**
 * Custom request client class.
 */
class MyClient extends Client
{
	/**
	 * Override parent constructor.
	 *
	 * @inheritdoc
	 */
	public function __construct(?string $baseUrl = null, array $params = [])
	{
		// Enable request client exception
		parent::__construct($baseUrl, $params);

		$this->setTimeout(5);       // Custom timeout
		$this->setRedirect(3);      // Custom redirection
		$this->setEncoding('gzip'); // Custom encoding
	}
}

// Set operation
$operation = new SearchItems();
$operation->setPartnerTag(Env::get('_TAG_'));
$operation->setItemCount(3)->setKeywords(Env::get('_KEYWORDS_'));

// Prapere request
$request = new Request(Env::get('_KEY_'), Env::get('_SECRET_'));
$request->setLocale(Env::get('_LOCALE_'))->setPayload($operation);

// Set custom client after payload
$request->setClient(
	new MyClient()
);

// Get response
$response = new Response($request);
$data = $response->get(); // Array
var_dump($data);

// Any PR is welcome!
