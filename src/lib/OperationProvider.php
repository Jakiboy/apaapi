<?php
/**
 * @package Amazon Product Advertising API
 * @version 1.0.7
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\lib;

use Apaapi\interfaces\OperationInterface;

/**
 * Basic Apaapi Request Provider (Parser)
 */
class OperationProvider
{
    /**
     * @access public
     * @param OperationInterface $operation
     * @return JSON
     */
    public static function generate(OperationInterface $operation)
    {
    	$wrapper = [];
    	foreach ($operation as $key => $value) {
            if ($value) {
                $wrapper[ucfirst($key)] = $value;
            }
    	}
        return json_encode($wrapper);
    }

    /**
     * @access public
     * @param OperationInterface $operation
     * @return string
     */
    public static function getName(OperationInterface $operation)
    {
    	return str_replace('Apaapi\operations\\','',get_class($operation));
    }
}
