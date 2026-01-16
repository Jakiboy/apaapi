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
 * Apaapi rating scraper.
 */
final class Rating extends Scraper
{
    public const SELECTORS = [
        'value' => "//div[contains(@class,'AverageCustomerReviews')]",
        'count' => "//div[contains(@class,'averageStarRatingNumerical')]"
    ];
    public const PATTERN   = [
        'count' => '/\D/'
    ];

    /**
     * @inheritdoc
     */
    public function __construct(string $keyword, string $locale = 'com', ?string $tag = null)
    {
        parent::__construct($keyword, $locale, $tag);
    }

    /**
     * Get item rating data.
     *
     * @access public
     * @return array
     */
    public function get() : array
    {
        return $this->parse();
    }

    /**
     * @inheritdoc
     */
    protected function format(array $data) : array
    {
        // Format rating value
        $element = $data['value'] ?? '';
        $element = explode(' ', $element);
        $element = $element[0] ?? '';
        $data['value'] = Normalizer::toFloat($element);

        // Format rating count
        $pattern = self::PATTERN['count'];
        $element = $data['count'] ?? '';
        $element = Normalizer::removeRegex($pattern, $element);
        $data['count'] = Normalizer::toInt($element);

        return $data;
    }
}
