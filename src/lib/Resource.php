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

namespace Apaapi\lib;

use Apaapi\interfaces\ResourceInterface;

/**
 * Apaapi resource wrapper class.
 */
class Resource implements ResourceInterface
{
    /**
     * @access public
     * @var array $items
     */
	public $items = false;
}
