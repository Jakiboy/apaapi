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

namespace Apaapi\includes;

use Apaapi\interfaces\OperationInterface;

/**
 * Basic Apaapi Request Operation Parsing Helper
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
