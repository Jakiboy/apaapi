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
 * Apaapi product scraper (Credential-less).
 */
final class Product extends Scraper
{
    public const SELECTORS        = [
        'title'    => "//span[contains(@id,'productTitle')]",
        'price'    => "//span[contains(@class,'priceToPay')]/span[2]",
        'discount' => "//span[contains(@class,'basisPrice')]//span[contains(@class,'a-offscreen')]",
        'image'    => "//div[@id='imgTagWrapperId']//img[@id='landingImage']/@src",
        'features' => "//div[@id='feature-bullets']//li"
    ];
    public const SEARCH_SELECTORS = [
        'title'    => "//div[contains(@class,'s-title-instructions-style')]",
        'price'    => "//span[contains(@class,'priceToPay')]/span[2]",
        'discount' => "//span[contains(@class,'basisPrice')]//span[contains(@class,'a-offscreen')]",
        'image'    => "//div[@id='imgTagWrapperId']//img[@id='landingImage']/@src",
        'features' => "//div[@id='feature-bullets']//li",
        '@search'  => [
            'parent' => "//div[contains(@class, 's-search-results')]",
            'item'   => "//div[contains(@class, 's-result-item')]",
            'count'  => "[position() >= 3 and position() <= {count}]",
        ]
    ];
    public const PATTERN          = [
        'image' => '/\.__AC_SX.*?\.jpg/'
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
        return $this->parse();
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
        // Format price
        $element = $data['price'] ?? '';
        $data['price'] = Normalizer::toFloat($element);

        // Format discount
        $element = $data['discount'] ?? '';
        $data['discount'] = Normalizer::toFloat($element);

        // Format image
        $pattern = self::PATTERN['image'];
        $element = $data['image'] ?? '';

        $element = $this->imageSize == 'full'
            ? Normalizer::replaceRegex($pattern, '.__SL.jpg', $element)
            : Normalizer::replaceRegex($pattern, ".__SL{$this->imageSize}__.jpg", $element);

        if ( !$this->imagePadding ) {
            $element = Normalizer::replaceString('.__SL', '.__AC_SL', $element);
        }

        $data['image'] = $element;

        // Format rating
        if ( $this->rating ) {

            $sub = $data['rating'] ?? [];

            // Format rating value
            $element = $sub['value'] ?? '';
            $element = explode(' ', $element);
            $element = $element[0] ?? '';
            $sub['value'] = Normalizer::toFloat($element);

            // Format rating count
            $pattern = Rating::PATTERN['count'];
            $element = $sub['count'] ?? '';
            $element = Normalizer::removeRegex($pattern, $element);
            $sub['count'] = Normalizer::toInt($element);

            $data['rating'] = $sub;
        }

        return $data;
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
                'rating' => Rating::SELECTORS
            ]);
        }
    }
}
