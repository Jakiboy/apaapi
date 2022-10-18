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

/**
 * Basic Apaapi Request Resource Parsing Helper.
 */
final class ResourceParser extends Parser
{
    /**
     * @access public
     * @param array $resources
     * @return array
     */
    public static function toString($resources)
    {
        $wrapper = [];
        foreach ($resources as $resource) {
            $parent = self::getName($resource);
            if ( is_array($resource->items) ) {
                foreach ($resource->items as $item) {
                    $wrapper[] = "{$parent}.{$item}";
                }
            } elseif ( $resource->items === false ) {
                $wrapper[] = $parent;
            }
        }
        return $wrapper;
    }
}
