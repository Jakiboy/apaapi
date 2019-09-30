<?php
/**
 * @package Amazon Product Advertising API v5
 * @copyright Copyright (c) 2019 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\lib;

use Apaapi\interfaces\OperationInterface;

/**
 * Basic Paapi5 Request Provider
 */
class OperationProvider
{
    static public function generate(OperationInterface $operation)
    {
    	$wrapper = [];
    	foreach ($operation as $key => $value) {
    		$wrapper[ucfirst($key)] = $value;
    	}
        return json_encode($wrapper);
    }
}
