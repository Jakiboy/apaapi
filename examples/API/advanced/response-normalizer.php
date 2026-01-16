<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Creators API Library
 * @version   : 2.0.x
 * @copyright : (c) 2019 - 2026 Jihad Sinnaour <me@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

declare(strict_types=1);

include '../../../src/Autoloader.php';

\Apaapi\Autoloader::init();

use Apaapi\operations\SearchItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;
use Apaapi\includes\Env;

Env::load('../../.env');

// Set operation
$operation = new SearchItems();
$operation->setPartnerTag(Env::get('_TAG_'));
$operation->setItemCount(3)->setKeywords(Env::get('_KEYWORDS_'));

// Set items (3)
$operation->setItemCount(3);

// Prapere request
$request = new Request(Env::get('_CREDENTIAL_ID_'), Env::get('_CREDENTIAL_SECRET_'));
$request->setLocale(Env::get('_LOCALE_'))->setPayload($operation);

// Get response
$response = new Response($request, Response::NORMALIZE);
$data = $response->get(); // Normalized Array
var_dump($data);

// Any PR is welcome!
