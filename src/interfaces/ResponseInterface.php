<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.1
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
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
     * @param bool $parse
     */
	public function __construct(RequestInterface $request, ResponseTypeInterface $type = null, $parse = null);
}
