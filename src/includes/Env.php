<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.5.x
 * @copyright : (c) 2019 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
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
    public static function load($filePath = '.env', $override = false)
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if ($lines === false) {
            return false;
        }

        foreach ($lines as $line) {
            // Skip comments and empty lines
            $line = trim($line);
            if (empty($line) || strpos($line, '#') === 0) {
                continue;
            }

            // Parse key=value pairs
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $key = trim($key);
                $value = self::parseValue(trim($value));

                // Store in our internal array
                self::$variables[$key] = $value;

                // Set in PHP's environment if not exists or override is true
                if ($override || getenv($key) === false) {
                    putenv("$key=$value");
                    $_ENV[$key] = $value;
                    $_SERVER[$key] = $value;
                }
            }
        }

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
        // Check our internal storage first
        if (isset(self::$variables[$key])) {
            return self::$variables[$key];
        }

        // Fallback to PHP's environment
        $value = getenv($key);
        if ($value !== false) {
            return $value;
        }

        // Check $_ENV and $_SERVER superglobals
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        }

        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
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
        $value = (string) $value;
        self::$variables[$key] = $value;
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }

    /**
     * Check if environment variable exists.
     *
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return isset(self::$variables[$key]) || getenv($key) !== false || isset($_ENV[$key]) || isset($_SERVER[$key]);
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
        if (strpos($value, '#') !== false) {
            $value = trim(explode('#', $value)[0]);
        }

        // Handle quoted strings
        if ((strpos($value, '"') === 0 && strrpos($value, '"') === strlen($value) - 1) ||
            (strpos($value, "'") === 0 && strrpos($value, "'") === strlen($value) - 1)) {
            return substr($value, 1, -1);
        }

        // Handle boolean values
        $lower = strtolower($value);
        if (in_array($lower, ['true', 'false'])) {
            return $lower === 'true';
        }

        // Handle null
        if (in_array($lower, ['null', 'nil', ''])) {
            return null;
        }

        // Handle numbers
        if (is_numeric($value)) {
            return strpos($value, '.') !== false ? (float) $value : (int) $value;
        }

        return $value;
    }
}
