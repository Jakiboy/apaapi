<?php
/**
 * @package Amazon Product Advertising API
 * @version 1.0.7
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\interfaces;

/**
 * Basic Apaapi Response Interface
 */
interface ResponseInterface
{
    /**
     * @param RequestInterface $request
     * @param ResponseTypeInterface $type
     * @return void
     */
	function __construct(RequestInterface $request, ResponseTypeInterface $type = null);
}
