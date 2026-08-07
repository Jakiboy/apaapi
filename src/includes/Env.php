<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Creators API Library
 * @version   : 2.1.x
 * @copyright : (c) 2019 - 2026 Jihad Sinnaour <me@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

declare(strict_types=1);

namespace Apaapi\includes;

/**
 * Apaapi built-in .env file parser.
 */
final class Env
{
    /**
     * @access public
     * @var array $variables Stored environment variables
     * @var array $loaded Whether variables have been loaded
     */
    private static $variables = [];
    private static $loaded = false;

    /**
     * Load and parse .env file.
     *
     * @param string $filePath
     * @param bool $override
     * @return bool
     */
    public static function load(string $filePath = '.env', bool $override = false)
    {
        if ( !file_exists($filePath) ) {
            return false;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ( $lines === false ) {
            return false;
        }

        foreach ($lines as $line) {
            // Skip comments and empty lines
            $line = trim($line);
            if ( empty($line) || strpos($line, '#') === 0 ) {
                continue;
            }

            // Parse key=value pairs
            if ( strpos($line, '=') !== false ) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = self::parseValue(trim($value));

                // Store in our internal array
                self::$variables[$key] = $value;

                // Set in PHP's environment if not exists or override is true
                if ( $override || getenv($key) === false ) {
                    putenv("$key=$value");
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                }
            }
        }

        self::applyRuntimeFlags();
        self::$loaded = true;
        return true;
    }

    /**
     * Get environment variable value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $keys = self::resolveKeys((string)$key);

        foreach ($keys as $name) {
            if ( isset(self::$variables[$name]) ) {
                return self::$variables[$name];
            }

            $value = getenv($name);
            if ( $value !== false ) {
                return $value;
            }

            if ( isset($_ENV[$name]) ) {
                return $_ENV[$name];
            }

            if ( isset($_SERVER[$name]) ) {
                return $_SERVER[$name];
            }
        }

        return $default;
    }

    /**
     * Set environment variable.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value)
    {
        $value = (string)$value;
        self::$variables[$key] = $value;
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;

        self::applyRuntimeFlags();
    }

    /**
     * Check if environment variable exists.
     *
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        $keys = self::resolveKeys((string)$key);

        foreach ($keys as $name) {
            if ( isset(self::$variables[$name]) || getenv($name) !== false || isset($_ENV[$name]) || isset($_SERVER[$name]) ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all loaded variables.
     *
     * @return array
     */
    public static function all()
    {
        return array_merge($_ENV, self::$variables);
    }

    /**
     * Clear all loaded variables from internal storage.
     *
     * @return void
     */
    public static function clear()
    {
        self::$variables = [];
        self::$loaded = false;
    }

    /**
     * Check if .env file has been loaded.
     *
     * @return bool
     */
    public static function isLoaded()
    {
        return self::$loaded;
    }

    /**
     * Parse value from .env file,
     * Handles quoted strings, boolean values, null, and numbers.
     *
     * @param string $value
     * @return mixed
     */
    private static function parseValue($value)
    {
        // Remove comments from end of line
        if ( strpos($value, '#') !== false ) {
            $value = trim(explode('#', $value)[0]);
        }

        // Handle quoted strings
        if (
            (strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
            (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)
        ) {
            return substr($value, 1, -1);
        }

        // Handle boolean values
        $lower = strtolower($value);
        if ( in_array($lower, ['true', 'false']) ) {
            return $lower === 'true';
        }

        // Handle null
        if ( in_array($lower, ['null', 'nil', '']) ) {
            return null;
        }

        // Handle numbers
        if ( is_numeric($value) ) {
            return strpos($value, '.') !== false ? (float)$value : (int)$value;
        }

        return $value;
    }

    /**
     * Apply runtime feature flags from environment values.
     *
     * @return void
     */
    private static function applyRuntimeFlags()
    {
        $raw = self::getExact('DISABLE_CACHE');

        if ( $raw === null ) {
            return;
        }

        $isDisabled = false;

        if ( is_bool($raw) ) {
            $isDisabled = $raw;
        } else {
            $value = strtolower(trim((string)$raw));
            $isDisabled = in_array($value, ['1', 'true', 'yes', 'on'], true);
        }

        if ( $isDisabled ) {
            Cache::disable();
        } else {
            Cache::enable();
        }
    }

    /**
     * Get exact key value without alias expansion.
     *
     * @param string $key
     * @return mixed
     */
    private static function getExact(string $key) : mixed
    {
        if ( array_key_exists($key, self::$variables) ) {
            return self::$variables[$key];
        }

        $value = getenv($key);
        if ( $value !== false ) {
            return $value;
        }

        if ( array_key_exists($key, $_ENV) ) {
            return $_ENV[$key];
        }

        if ( array_key_exists($key, $_SERVER) ) {
            return $_SERVER[$key];
        }

        return null;
    }

    /**
     * Resolve possible aliases for an env key.
     * Supports plain, underscored, and APAAPI-prefixed variations.
     *
     * @param string $key
     * @return array
     */
    private static function resolveKeys(string $key)
    {
        $key = trim($key);
        $base = trim($key, '_');

        $keys = [$key];

        if ( $base !== '' ) {
            $keys[] = $base;
            $keys[] = "_{$base}_";

            if ( strpos($base, 'APAAPI_') === 0 ) {
                $short = substr($base, strlen('APAAPI_'));
                if ( !empty($short) ) {
                    $keys[] = $short;
                    $keys[] = "_{$short}_";
                }
            } else {
                $prefixed = "APAAPI_{$base}";
                $keys[] = $prefixed;
                $keys[] = "_{$prefixed}_";
            }
        }

        $keys = array_filter($keys, fn($item) => !empty($item));
        return array_values(array_unique($keys));
    }
}
