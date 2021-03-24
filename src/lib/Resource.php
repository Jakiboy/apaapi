<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi
 * @version   : 1.0.9
 * @copyright : (c) 2019 - 2021 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib
 */

namespace Apaapi\lib;

use Apaapi\interfaces\ResourceInterface;

/**
 * Basic Apaapi Resource Wrapper Class
 */
class Resource implements ResourceInterface
{
    /**
     * @access public
     * @var array $items
     */
	public $items = false;
}
