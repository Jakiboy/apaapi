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

use Apaapi\interfaces\ParsableInterface;

/**
 * Basic Apaapi Request Parser
 */
class Parser
{
    /**
     * @access public
     * @param ParsableInterface $parsable
     * @return string
     */
    public static function getName(ParsableInterface $parsable)
    {
        return basename(str_replace('\\','/',get_class($parsable)));
    }
}
