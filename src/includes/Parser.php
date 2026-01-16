<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Creators API Library
 * @version   : 2.0.x
 * @copyright : (c) 2019 - 2026 Jihad Sinnaour <me@jihadsinnaour.com>
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
    public static function convert($items) : mixed
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
            // Convert to lowerCamelCase for Creators API
            $parent = lcfirst($parent);

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
                // Creators API uses lowerCamelCase for parameters, not PascalCase
                $wrapper[$key] = $value;
            }
        }
        return json_encode($wrapper);
    }
}
