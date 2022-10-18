<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.4
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\includes;

use Apaapi\interfaces\OperationInterface;

/**
 * Basic Apaapi Request Operation Parsing Helper.
 */
final class OperationParser extends Parser
{
    /**
     * @access public
     * @param OperationInterface $operation
     * @return string
     */
    public static function toString(OperationInterface $operation)
    {
    	$wrapper = [];
    	foreach ($operation as $key => $value) {
            if ( $value ) {
                $wrapper[ucfirst($key)] = $value;
            }
    	}
        return json_encode($wrapper);
    }
}
