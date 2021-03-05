<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi
 * @version   : 1.0.8
 * @copyright : (c) 2019 - 2021 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib
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
     * @return void
     */
	public function __construct(RequestInterface $request, ResponseTypeInterface $type = null, $parse = null);
}
