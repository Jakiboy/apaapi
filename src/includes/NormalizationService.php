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

use Apaapi\interfaces\NormalizationServiceInterface;

/**
 * Default normalization service implementation.
 */
final class NormalizationService implements NormalizationServiceInterface
{
    /**
     * Constructor.
     *
     * @param NormalizerConfig $config
     */
    public function __construct(private readonly NormalizerConfig $config)
    {
    }

    /**
     * @inheritdoc
     * @todo Implement normalization logic
     */
    public function normalize(array $data, string $operation) : array
    {
        return Normalizer::get($data, $operation);
    }

    /**
     * @inheritdoc
     */
    public function formatId(string $id) : string
    {
        return Normalizer::formatId($id);
    }

    /**
     * @inheritdoc
     */
    public function formatKeywords(mixed $keywords) : string
    {
        return Normalizer::formatKeywords($keywords);
    }

    /**
     * @inheritdoc
     */
    public function formatIds(mixed $ids) : array
    {
        return Normalizer::formatIds($ids);
    }

    /**
     * Get configuration.
     *
     * @return NormalizerConfig
     */
    public function getConfig() : NormalizerConfig
    {
        return $this->config;
    }
}
