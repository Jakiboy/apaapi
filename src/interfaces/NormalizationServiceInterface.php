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

namespace Apaapi\interfaces;

/**
 * Normalization service interface.
 */
interface NormalizationServiceInterface
{
    /**
     * Normalize response data.
     *
     * @param array $data
     * @param string $operation
     * @return array
     */
    public function normalize(array $data, string $operation) : array;
    
    /**
     * Format single ID.
     *
     * @param string $id
     * @return string
     */
    public function formatId(string $id) : string;
    
    /**
     * Format keywords.
     *
     * @param mixed $keywords
     * @return string
     */
    public function formatKeywords(mixed $keywords) : string;
    
    /**
     * Format IDs.
     *
     * @param mixed $ids
     * @return array
     */
    public function formatIds(mixed $ids) : array;
}
