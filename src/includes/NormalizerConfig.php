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

/**
 * Normalizer configuration class.
 */
final class NormalizerConfig
{
    /**
     * Constructor.
     *
     * @param int $limit List limit
     * @param bool $format String format
     * @param bool $error Error format
     * @param bool $order Data order
     */
    public function __construct(
        public readonly int $limit = 5,
        public readonly bool $format = true,
        public readonly bool $error = true,
        public readonly bool $order = true
    ) {
    }

    /**
     * Create new config with updated limit.
     *
     * @param int $limit
     * @return self
     */
    public function withLimit(int $limit) : self
    {
        return new self($limit, $this->format, $this->error, $this->order);
    }

    /**
     * Create new config without format.
     *
     * @return self
     */
    public function withoutFormat() : self
    {
        return new self($this->limit, false, $this->error, $this->order);
    }

    /**
     * Create new config without error formatting.
     *
     * @return self
     */
    public function withoutError() : self
    {
        return new self($this->limit, $this->format, false, $this->order);
    }

    /**
     * Create new config without ordering.
     *
     * @return self
     */
    public function withoutOrder() : self
    {
        return new self($this->limit, $this->format, $this->error, false);
    }
}
