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
 * Scraper configuration provider.
 */
final class ScraperConfig
{
    /**
     * @access private
     */
    private const DEFAULTS = [
        'timeout'           => 30,
        'connectTimeout'    => 15,
        'maxRetries'        => 3,
        'retryDelay'        => 2,
        'stealthEnabled'    => true,
        'minDelay'          => 1.5,
        'maxDelay'          => 4.0,
        'verifySsl'         => false,
        'verifyHost'        => false,
        'followRedirects'   => true,
        'maxRedirects'      => 5,
        'debugEnabled'      => false,
        'saveDebugHtml'     => false,
        'rotateUserAgents'  => true,
        'customUserAgents'  => [],
        'rotateResolutions' => true,
        'customResolutions' => []
    ];

    private static ?array $settings = null;

    /**
     * Merge runtime overrides.
     *
     * @access public
     * @param array $settings
     * @return void
     */
    public static function set(array $settings) : void
    {
        self::$settings = array_replace_recursive(self::all(), $settings);
    }

    /**
     * Replace runtime configuration (merged with defaults).
     *
     * @access public
     * @param array $settings
     * @return void
     */
    public static function replace(array $settings) : void
    {
        self::$settings = array_replace_recursive(self::DEFAULTS, $settings);
    }

    /**
     * Reset runtime configuration to defaults.
     *
     * @access public
     * @return void
     */
    public static function reset() : void
    {
        self::$settings = null;
    }

    /**
     * Get all config values.
     *
     * @access public
     * @return array
     */
    public static function all() : array
    {
        if ( self::$settings === null ) {
            self::$settings = self::load();
        }

        return self::$settings;
    }

    /**
     * Get single config value.
     *
     * @access public
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null) : mixed
    {
        $settings = self::all();
        return $settings[$key] ?? $default;
    }

    /**
     * Load effective config.
     *
     * @access private
     * @return array
     */
    private static function load() : array
    {
        return self::DEFAULTS;
    }
}
