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
 * Apaapi product scraper (Credential-less).
 */
final class Product extends Scraper
{
    private static array $exchangeRateCache = [];

    public const SELECTORS        = [
        'title'    => "(//span[@id='productTitle']|//h1[@id='title']//span|//h1[contains(@class,'a-size-large')])[1]",
        'price'    => "(//div[@id='corePrice_feature_div']//span[contains(@class,'a-price') and not(contains(@class,'a-text-price'))]//span[contains(@class,'a-offscreen')]|//span[contains(@class,'priceToPay')]//span[contains(@class,'a-offscreen')]|//span[@id='price_inside_buybox'])[1]",
        'discount' => "(//span[contains(@class,'basisPrice')]//span[contains(@class,'a-offscreen')]|//span[contains(@class,'a-text-price')]//span[contains(@class,'a-offscreen')])[1]",
        'image'    => "(//img[@id='landingImage']/@data-old-hires|//img[@id='landingImage']/@src|//meta[@property='og:image']/@content)[1]",
        'features' => "//div[@id='feature-bullets']//li"
    ];
    public const SEARCH_SELECTORS = [
        'title'    => "(.//h2//span|.//div[contains(@class,'s-title-instructions-style')])[1]",
        'price'    => "(.//span[contains(@class,'priceToPay')]//span[contains(@class,'a-offscreen')]|.//span[contains(@class,'a-price')]//span[contains(@class,'a-offscreen')])[1]",
        'discount' => "(.//span[contains(@class,'basisPrice')]//span[contains(@class,'a-offscreen')]|.//span[contains(@class,'a-text-price')]//span[contains(@class,'a-offscreen')])[1]",
        'image'    => "(.//img[contains(@class,'s-image')]/@src|.//img[@data-image-latency='s-product-image']/@src)[1]",
        'features' => "//div[@id='feature-bullets']//li",
        '@search'  => [
            'parent' => "//div[contains(@class, 's-search-results')]",
            'item'   => "//div[contains(@class, 's-result-item')]",
            'count'  => "[position() >= 3 and position() <= {count}]",
        ]
    ];
    public const PATTERN          = [
        'image'        => '/\.__AC_SX.*?\.jpg/',
        'imageDefault' => '/\._[^\.]*_\./',
        'amount'       => '/[0-9]+(?:[\s\x{00A0}\x{202F},\.][0-9]{2,3})*(?:[\.,][0-9]{1,2})?/u'
    ];

    /**
     * @access private
     * @var string $imageSize
     * @var bool $imagePadding
     * @var bool $rating
     */
    private $imageSize = '300';
    private $imagePadding = true;
    private $rating = true;

    /**
     * @inheritdoc
     */
    public function __construct(string $keyword, string $locale = 'com', ?string $tag = null)
    {
        parent::__construct($keyword, $locale, $tag);
    }

    /**
     * Disable rating.
     *
     * @access public
     * @return Product
     */
    public function noRating() : self
    {
        $this->rating = false;
        return $this;
    }

    /**
     * Set image size (100-500, full).
     *
     * @access public
     * @param string $size
     * @param bool $padding
     * @return Product
     */
    public function setImage(string $size, bool $padding = true) : self
    {
        $this->imageSize = $size;
        $this->imagePadding = $padding;
        return $this;
    }

    /**
     * Get single item.
     *
     * @access public
     * @return array
     */
    public function get() : array
    {
        $this->addRating();
        $data = $this->parse();

        if ( $this->shouldUseComPriceFallback($data) ) {
            $fallback = $this->fetchComFallbackData();
            if ( is_array($fallback) ) {
                $data = $this->mergeComFallbackPrices($data, $fallback);
            }
        }

        return $data;
    }

    /**
     * Search items.
     *
     * @access public
     * @param int $count
     * @return array
     */
    public function search(int $count = 10) : array
    {
        $this->addRating();
        return $this->parseMany($count);
    }

    /**
     * @inheritdoc
     */
    protected function format(array $data) : array
    {
        $response = $this->getLastResponse();

        $data['price'] = $this->resolveLocalizedMonetaryValue($response, (string)($data['price'] ?? ''), false);
        $data['discount'] = $this->resolveLocalizedMonetaryValue($response, (string)($data['discount'] ?? ''), true);
        $data['image'] = $this->formatImage((string)($data['image'] ?? ''));

        if ( $this->rating ) {
            $data['rating'] = $this->formatRating((array)($data['rating'] ?? []));
        }

        return $data;
    }

    /**
     * Format localized price or discount value.
     *
     * @access private
     * @param string $html
     * @param string $fallback
     * @param bool $discount
     * @return float
     */
    private function resolveLocalizedMonetaryValue(string $html, string $fallback, bool $discount) : float
    {
        $value = $discount
            ? $this->extractPreferredDiscountFromHtml($html, $fallback)
            : $this->extractPreferredPriceFromHtml($html, $fallback);

        return $this->normalizeAmountForLocale($value, $html);
    }

    /**
     * Format product image URL.
     *
     * @access private
     * @param string $value
     * @return string
     */
    private function formatImage(string $value) : string
    {
        if ( $value === '' ) {
            return $value;
        }

        $pattern = self::PATTERN['image'];

        $value = $this->imageSize == 'full'
            ? Normalizer::replaceRegex($pattern, '.__SL.jpg', $value)
            : Normalizer::replaceRegex($pattern, ".__SL{$this->imageSize}__.jpg", $value);

        $value = Normalizer::replaceRegex(self::PATTERN['imageDefault'], '._SL1500_.', $value);

        if ( !$this->imagePadding ) {
            $value = Normalizer::replaceString('.__SL', '.__AC_SL', $value);
        }

        return $value;
    }

    /**
     * Format nested rating payload.
     *
     * @access private
     * @param array $rating
     * @return array
     */
    private function formatRating(array $rating) : array
    {
        $rating['value'] = $this->normalizeAmount((string)($rating['value'] ?? ''));

        $count = preg_replace('/\D/', '', (string)($rating['count'] ?? ''));
        $rating['count'] = Normalizer::toInt($count ?: '0');

        return $rating;
    }

    /**
     * Add rating selectors.
     *
     * @access private
     * @return void
     */
    private function addRating() : void
    {
        if ( $this->rating ) {
            $this->selectors = array_merge($this->selectors, [
                'rating' => [
                    'value' => "(//span[@id='acrPopover']/@title|//span[contains(@class,'a-icon-alt')])[1]",
                    'count' => "(//span[@id='acrCustomerReviewText']|//span[@data-hook='total-review-count'])[1]"
                ]
            ]);
        }
    }

    /**
     * Normalize localized amount text into float.
     *
     * @access private
     * @param string $value
     * @return float
     */
    private function normalizeAmount(string $value) : float
    {
        $pattern = self::PATTERN['amount'];
        $value = str_replace(["\xC2\xA0", "\xE2\x80\xAF"], ' ', $value);

        if ( !preg_match_all($pattern, $value, $matches) ) {
            return 0.0;
        }

        $candidates = $matches[0] ?? [];
        usort($candidates, static function ($a, $b) {
            return strlen((string)$b) <=> strlen((string)$a);
        });

        $amount = (string)($candidates[0] ?? '');
        $amount = preg_replace('/[\s\x{00A0}\x{202F}]+/u', '', $amount);
        $lastDot = strrpos($amount, '.');
        $lastComma = strrpos($amount, ',');

        if ( $lastDot !== false && $lastComma !== false ) {
            if ( $lastDot > $lastComma ) {
                $amount = str_replace(',', '', $amount);
            } else {
                $amount = str_replace('.', '', $amount);
                $amount = str_replace(',', '.', $amount);
            }
        } elseif ( $lastComma !== false ) {
            $amount = str_replace('.', '', $amount);
            $amount = str_replace(',', '.', $amount);
        } else {
            $amount = str_replace(',', '', $amount);
        }

        return (float)$amount;
    }

    /**
     * Prefer locale-currency current price found in raw HTML.
     *
     * @access private
     * @param string $html
     * @param string $fallback
     * @return string
     */
    private function extractPreferredPriceFromHtml(string $html, string $fallback) : string
    {
        return $this->extractLocalizedAmountFromHtml($html, false) ?: $fallback;
    }

    /**
     * Prefer locale-currency discounted/old price found in raw HTML.
     *
     * @access private
     * @param string $html
     * @param string $fallback
     * @return string
     */
    private function extractPreferredDiscountFromHtml(string $html, string $fallback) : string
    {
        return $this->extractLocalizedAmountFromHtml($html, true) ?: $fallback;
    }

    /**
     * Extract first locale-currency amount from raw HTML.
     *
     * @access private
     * @param string $html
     * @param bool $discount
     * @return ?string
     */
    private function extractLocalizedAmountFromHtml(string $html, bool $discount = false) : ?string
    {
        if ( trim($html) === '' ) {
            return null;
        }

        $currency = $this->getTargetCurrencyCode();
        if ( !is_string($currency) || $currency === '' ) {
            return null;
        }

        $symbol = Provider::getSymbol($currency);
        if ( !is_string($symbol) || $symbol === '' ) {
            return null;
        }

        $symbolPattern = preg_quote($symbol, '/');
        $codePattern = preg_quote($currency, '/');

        $patterns = $discount
            ? [
                '/<span[^>]*class="[^"]*basisPrice[^"]*"[^>]*>.*?<span[^>]*class="a-offscreen"[^>]*>([^<]*?(?:' . $symbolPattern . '|' . $codePattern . ')[^<]*)<\/span>/is',
                '/<span[^>]*class="a-text-price"[^>]*data-a-strike="true"[^>]*>.*?<span[^>]*class="a-offscreen"[^>]*>([^<]*?(?:' . $symbolPattern . '|' . $codePattern . ')[^<]*)<\/span>/is'
            ]
            : [
                '/"priceToPay"[^\{\[]*\{[^\}]*"displayAmount"\s*:\s*"([^\"]*?(?:' . $symbolPattern . '|' . $codePattern . ')[^\"]*)"/is',
                '/<div[^>]*id=["\']corePrice_feature_div["\'][^>]*>.*?<span[^>]*class="a-offscreen"[^>]*>([^<]*?(?:' . $symbolPattern . '|' . $codePattern . ')[^<]*)<\/span>/is',
                '/<span[^>]*class="[^"]*priceToPay[^"]*"[^>]*>.*?<span[^>]*class="a-offscreen"[^>]*>([^<]*?(?:' . $symbolPattern . '|' . $codePattern . ')[^<]*)<\/span>/is'
            ];

        foreach ($patterns as $pattern) {
            if ( preg_match($pattern, $html, $matches) ) {
                $value = trim((string)($matches[1] ?? ''));
                if ( $value !== '' ) {
                    return html_entity_decode($value, ENT_QUOTES, 'UTF-8');
                }
            }
        }

        return null;
    }

    /**
     * Normalize amount and convert it to locale currency when needed.
     *
     * @access private
     * @param string $value
     * @return float
     */
    private function normalizeAmountForLocale(string $value, string $html = '') : float
    {
        $amount = $this->normalizeAmount($value);
        if ( $amount <= 0 ) {
            return 0.0;
        }

        $source = $this->detectCurrencyCode($value);
        if ( !is_string($source) || $source === '' ) {
            $source = $this->detectCurrencyCodeFromHtml($html);
        }
        $target = $this->getTargetCurrencyCode();

        if ( !is_string($source) || !is_string($target) || $source === '' || $target === '' || $source === $target ) {
            return $amount;
        }

        $rate = $this->getExchangeRate($source, $target);
        if ( $rate === null || $rate <= 0 ) {
            return $amount;
        }

        return round($amount * $rate, 2);
    }

    /**
     * Detect source currency code from raw HTML metadata.
     *
     * @access private
     * @param string $html
     * @return ?string
     */
    private function detectCurrencyCodeFromHtml(string $html) : ?string
    {
        if ( trim($html) === '' ) {
            return null;
        }

        $patterns = [
            '/name="currencyOfPreference"\s+value="([A-Z]{3})"/i',
            '/name="priceSymbol"\s+value="([A-Z]{3})"/i',
            '/"currencyCode"\s*:\s*"([A-Z]{3})"/i'
        ];

        foreach ($patterns as $pattern) {
            if ( preg_match($pattern, $html, $matches) ) {
                return strtoupper((string)($matches[1] ?? ''));
            }
        }

        return null;
    }

    /**
     * Detect currency code from extracted text.
     *
     * @access private
     * @param string $value
     * @return ?string
     */
    private function detectCurrencyCode(string $value) : ?string
    {
        $value = trim($value);
        if ( $value === '' ) {
            return null;
        }

        if ( preg_match('/\b([A-Z]{3})\b/u', $value, $matches) ) {
            return strtoupper((string)$matches[1]);
        }

        $symbols = Provider::getSymbols();
        foreach ($symbols as $code => $symbol) {
            if ( !is_string($code) || !is_string($symbol) || $symbol === '' ) {
                continue;
            }

            if ( strpos($value, $symbol) !== false ) {
                return strtoupper($code);
            }
        }

        return null;
    }

    /**
     * Get exchange rate between two currency codes.
     *
     * @access private
     * @param string $from
     * @param string $to
     * @return ?float
     */
    private function getExchangeRate(string $from, string $to) : ?float
    {
        $from = strtoupper(trim($from));
        $to = strtoupper(trim($to));

        if ( $from === '' || $to === '' ) {
            return null;
        }

        if ( $from === $to ) {
            return 1.0;
        }

        $key = "{$from}:{$to}";
        if ( array_key_exists($key, self::$exchangeRateCache) ) {
            return self::$exchangeRateCache[$key];
        }

        $context = stream_context_create([
            'http' => [
                'timeout'       => 3,
                'ignore_errors' => true
            ]
        ]);

        $url = "https://open.er-api.com/v6/latest/{$from}";
        $response = @file_get_contents($url, false, $context);

        if ( $response === false ) {
            self::$exchangeRateCache[$key] = null;
            return null;
        }

        $payload = json_decode($response, true);
        if ( !is_array($payload) ) {
            self::$exchangeRateCache[$key] = null;
            return null;
        }

        $rate = $payload['rates'][$to] ?? null;
        if ( !is_numeric($rate) ) {
            self::$exchangeRateCache[$key] = null;
            return null;
        }

        $value = (float)$rate;
        self::$exchangeRateCache[$key] = $value;
        return $value;
    }

    /**
     * Resolve target currency code for current scraper locale.
     *
     * @access private
     * @return ?string
     */
    private function getTargetCurrencyCode() : ?string
    {
        if ( $this->locale === 'com' ) {
            return 'USD';
        }

        $currency = Provider::getCurrency($this->locale)[0] ?? null;
        if ( !is_string($currency) || trim($currency) === '' ) {
            return null;
        }

        return strtoupper(trim($currency));
    }

    /**
     * Decide whether localized page should fallback to .com prices.
     *
     * @access private
     * @param array $data
     * @return bool
     */
    private function shouldUseComPriceFallback(array $data) : bool
    {
        if ( $this->locale === 'com' ) {
            return false;
        }

        $price = (float)($data['price'] ?? 0);
        $discount = (float)($data['discount'] ?? 0);

        return $price <= 0 && $discount <= 0;
    }

    /**
     * Fetch product data from amazon.com for price fallback.
     *
     * @access private
     * @return ?array
     */
    private function fetchComFallbackData() : ?array
    {
        $fallback = new self($this->keyword, 'com', $this->tag);
        $fallback->setImage($this->imageSize, $this->imagePadding);

        if ( !$this->rating ) {
            $fallback->noRating();
        }

        $data = $fallback->get();
        return is_array($data) ? $data : null;
    }

    /**
     * Merge .com prices into localized result and convert to locale currency.
     *
     * @access private
     * @param array $data
     * @param array $fallback
     * @return array
     */
    private function mergeComFallbackPrices(array $data, array $fallback) : array
    {
        $target = $this->getTargetCurrencyCode();
        $rate = null;

        if ( is_string($target) && $target !== '' && $target !== 'USD' ) {
            $rate = $this->getExchangeRate('USD', $target);
        }

        $price = (float)($fallback['price'] ?? 0);
        $discount = (float)($fallback['discount'] ?? 0);

        if ( $price > 0 ) {
            $data['price'] = $this->convertAmount($price, $rate);
        }

        if ( $discount > 0 ) {
            $data['discount'] = $this->convertAmount($discount, $rate);
        }

        return $data;
    }

    /**
     * Convert amount using optional exchange rate.
     *
     * @access private
     * @param float $amount
     * @param ?float $rate
     * @return float
     */
    private function convertAmount(float $amount, ?float $rate = null) : float
    {
        if ( $amount <= 0 ) {
            return 0.0;
        }

        if ( $rate === null || $rate <= 0 ) {
            return $amount;
        }

        return round($amount * $rate, 2);
    }
}
