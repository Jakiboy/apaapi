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
 * Apaapi rating scraper.
 */
final class Rating extends Scraper
{
    public const SELECTORS = [
        'value' => "(//span[@id='acrPopover']/@title|//span[contains(@class,'a-icon-alt')]|//i[contains(@class,'a-icon-star')]//span[contains(@class,'a-icon-alt')])[1]",
        'count' => "(//span[@id='acrCustomerReviewText']|//span[@data-hook='total-review-count'])[1]"
    ];
    public const PATTERN   = [
        'amount' => '/[0-9]+(?:[\.,][0-9]+)?/u'
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
        $data['value'] = $this->normalizeAmount((string)($data['value'] ?? ''));

        // Format rating count
        $element = (string)($data['count'] ?? '');
        $element = preg_replace('/\D/u', '', $element);
        $count = Normalizer::toInt($element ?: '0');

        if ( $count === 0 ) {
            $count = $this->extractCountFromHtml($this->getLastResponse());
        }

        $data['count'] = $count;

        return $data;
    }

    /**
     * Normalize localized rating amount into float.
     *
     * @access private
     * @param string $value
     * @return float
     */
    private function normalizeAmount(string $value) : float
    {
        $pattern = self::PATTERN['amount'];
        if ( !preg_match($pattern, $value, $matches) ) {
            return 0.0;
        }

        $amount = str_replace(',', '.', (string)($matches[0] ?? '0'));
        return (float)$amount;
    }

    /**
     * Extract reviews count from raw HTML as fallback.
     *
     * @access private
     * @param string $html
     * @return int
     */
    private function extractCountFromHtml(string $html) : int
    {
        if ( trim($html) === '' ) {
            return 0;
        }

        $patterns = [
            '/id="acrCustomerReviewText"[^>]*>([^<]+)</u',
            '/data-hook="total-review-count"[^>]*>([^<]+)</u',
            '/"acrCustomerReviewText"[^\n\r]{0,120}/u'
        ];

        foreach ($patterns as $pattern) {
            if ( !preg_match($pattern, $html, $matches) ) {
                continue;
            }

            $text = html_entity_decode((string)($matches[1] ?? $matches[0] ?? ''), ENT_QUOTES, 'UTF-8');
            $digits = preg_replace('/\D/u', '', $text);
            $count = Normalizer::toInt($digits ?: '0');

            if ( $count > 0 ) {
                return $count;
            }
        }

        return 0;
    }
}
