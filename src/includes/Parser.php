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

declare(strict_types=1);

namespace Apaapi\includes;

use Apaapi\interfaces\ParsableInterface;

/**
 * Apaapi request parser.
 */
final class Parser
{
    /**
     * Get class name.
     * 
     * @access public
     * @param ParsableInterface $parsable
     * @return string
     */
    public static function getName(ParsableInterface $parsable) : string
    {
        $class = get_class($parsable);
        return basename(str_replace('\\', '/', $class));
    }

    /**
     * Convert items.
     * 
     * @access public
     * @param mixed $items
     * @return mixed
     */
    public static function convert($items)
    {
    	if ( is_array($items) ) {
            return self::resourcesToArray($items);
        }
        return self::operationToString($items);
    }

    /**
     * Convert resources object to array.
     *
     * @access private
     * @param array $resources
     * @return array
     */
    private static function resourcesToArray(array $resources) : array
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

    /**
     * Convert operation to string.
     * 
     * @access private
     * @param ParsableInterface $operation
     * @return string
     */
    private static function operationToString(ParsableInterface $operation) : string
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
