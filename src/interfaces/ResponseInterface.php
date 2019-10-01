<?php
/**
 * @package Amazon Product Advertising API
 * @copyright Copyright (c) 2019 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\interfaces;

use Apaapi\interfaces\RequestInterface;

/**
 * Basic Apaapi Response Interface
 */
interface ResponseInterface
{
	public function __construct(RequestInterface $request);
}
