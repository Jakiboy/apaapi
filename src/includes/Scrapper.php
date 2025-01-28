<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.3.x
 * @copyright : (c) 2019 - 2025 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

declare(strict_types=1);

namespace Apaapi\includes;

use DOMDocument, DOMNodeList, DOMXPath;

class Scrapper
{
	/**
	 * @access protected
	 * @var string $keyword
	 * @var string $locale
	 * @var string $tag
	 * @var string $baseUrl
	 * @var array $selectors
	 */
	protected $keyword;
	protected $locale;
	protected $tag;
	protected $baseUrl;
	protected $selectors = [];

	public const BASEURL   = '/dp/';
	public const SEARCH    = '/s/?field-keywords={keyword}';
	public const SELECTORS = [
		'rating'  => [
			'value' => "//div[contains(@class,'AverageCustomerReviews')]",
			'count' => "//div[contains(@class,'averageStarRatingNumerical')]"
		],
		'product' => [
			'title'    => "//span[contains(@id,'productTitle')]",
			'price'    => "//span[contains(@class,'reinventPricePriceToPayMargin')]",
			'discount' => "//span[contains(@class,'basisPrice')]//span[contains(@class,'a-offscreen')]",
			'image'    => "//div[@id='imgTagWrapperId']//img[@id='landingImage']/@src",
			'features' => "//div[@id='feature-bullets']//li"
		]
	];

	/**
	 * Init rating.
	 *
	 * @access public
	 * @param string $keyword
	 * @param string $locale
	 * @param string $tag
	 */
	public function __construct(string $keyword, string $locale = 'com', ?string $tag = null)
	{
		$this->keyword = Normalizer::formatId($keyword);
		$this->locale = Normalizer::formatTLD($locale);
		$this->tag = $tag;

		$this->baseUrl = self::BASEURL;
		$this->selectors = self::SELECTORS;
	}

	/**
	 * Set base URL.
	 *
	 * @access public
	 * @param string $baseUrl
	 * @return array
	 */
	public function setBaseUrl(string $baseUrl) : self
	{
		$this->baseUrl = $baseUrl;
		return $this;
	}

	/**
	 * Set selectors.
	 *
	 * @access public
	 * @param array $selectors
	 * @return self
	 */
	public function setSelectors(array $selectors) : self
	{
		$this->selectors = array_merge($this->selectors, $selectors);
		return $this;
	}

	/**
	 * Get single item.
	 *
	 * @access public
	 * @return array
	 */
	public function getOne() : array
	{
		return $this->parse('product', $this->selectors['product']);
	}

	/**
	 * Search items.
	 *
	 * @access public
	 * @return array
	 * @todo Implement
	 */
	public function search() : array
	{
		return $this->parseMany('product', $this->selectors['product']);
	}

	/**
	 * Get item rating data.
	 *
	 * @access public
	 * @return array
	 */
	public function getRating() : array
	{
		return $this->parse('rating', $this->selectors['rating']);
	}

	/**
	 * Generate request header.
	 *
	 * @access public
	 * @return array
	 */
	public static function generateHeader(string $locale = 'com') : array
	{
		$currency = 'USD';
		if ( $locale !== 'com' ) {
			$currency = Provider::getCurrency($locale)[0] ?? $currency;
		}

		$time = time() . 'l';

		$id = mt_rand(100, 999);
		$id .= '-' . mt_rand(1000000, 9999999);
		$id .= '-' . mt_rand(1000000, 9999999);

		$cookie = [
			"i18n-prefs={$currency}",
			"session-id={$id}",
			"session-id-time={$time}"
		];

		return [
			'Cookie'     => implode('; ', $cookie),
			'Connection' => 'close'
		];
	}

	/**
	 * Parse HTML response (Cached).
	 *
	 * @access protected
	 * @param string $item
	 * @param array $selectors
	 * @return array
	 */
	protected function parse(string $item, array $selectors) : array
	{
		$url = $this->getUrl();

		$default = $this->getDefault($item);
		$default = array_merge($default, ['url' => $url]);

		if ( !Keyword::isASIN($this->keyword) && !Keyword::isISBN($this->keyword) ) {
			return $default;
		}

		$key = "{$item}-{$this->locale}-{$this->keyword}";
		$key = Cache::generateKey($key);

		if ( !($data = Cache::get($key)) ) {

			$header = self::generateHeader($this->locale);
			$client = new Client($url, [
				'header'  => $header,
				'timeout' => 30
			]);

			$client->setEncoding()->get();
			$response = $client->getBody();

			if ( $client->getStatusCode() == 200 ) {
				$data = self::process($response, $selectors);
				$data = self::format($item, $data);
				$data = array_merge($default, $data);
				Cache::set($key, $data);
			}

		}

		return $data ?: $default;
	}

	/**
	 * Parse many HTML response (Cached).
	 *
	 * @access protected
	 * @param string $item
	 * @param array $selectors
	 * @return array
	 * @todo Implement
	 */
	protected function parseMany(string $item, array $selectors) : array
	{
		return [];
	}

	/**
	 * Process HTML response.
	 *
	 * @access protected
	 * @param string $response
	 * @param array $selectors
	 * @return array
	 */
	protected static function process(string $response, array $selectors) : array
	{
		$data = [];

		if ( class_exists('DomDocument') ) {

			// Ignore XML errors
			libxml_use_internal_errors(true);

			// Init DOM document
			$dom = new DOMDocument();
			$dom->loadHTML($response);
			$xPath = new DOMXPath($dom);

			foreach ($selectors as $key => $selector) {

				// Extract data based on selector
				$nodes = $xPath->query($selector);

				if ( $nodes instanceof DOMNodeList && $nodes->length > 0 ) {
					if ( substr($selector, -4) === '//li' ) {

						// Parse <li> content
						$list = [];
						foreach ($nodes as $node) {
							$value = (string)$node->nodeValue;
							$list[] = Normalizer::formatString($value);
						}
						$data[$key] = Normalizer::toJson($list);

					} else {
						// Extract single node value
						$node = $nodes->item(0);
						$value = (string)$node->nodeValue;
						$data[$key] = Normalizer::formatString($value);
					}
				}
			}

			// Clear XML errors
			libxml_clear_errors();
		}

		return $data;
	}

	/**
	 * Format scrapped data.
	 *
	 * @access protected
	 * @param string $item
	 * @param array $data
	 * @return array
	 */
	protected static function format(string $item, array $data) : array
	{
		if ( $item === 'rating' ) {
			$value = $data['value'] ?? '';
			$value = explode(' ', $value);
			$value = $value[0] ?? '';
			$value = str_replace(',', '.', $value);
			$data['value'] = (float)$value;

			$count = $data['count'] ?? '';
			$count = preg_replace('/\D/', '', $count);
			$data['count'] = (float)$count;

		} else {
			$price = $data['price'] ?? '';
			$price = str_replace(',', '.', $price);
			$data['price'] = (float)$price;

			$discount = $data['discount'] ?? '';
			$discount = str_replace(',', '.', $discount);
			$data['discount'] = (float)$discount;

			$image = $data['image'] ?? '';
			$image = preg_replace('/\.__AC_SX.*?\.jpg/', '.__SX300__.jpg', $image);
			$data['image'] = $image;
		}

		return $data;
	}

	/**
	 * Get scrapper URL.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getUrl() : string
	{
		$host = Provider::HOST;
		$url = str_replace('{locale}', $this->locale, $host);
		$url = "{$url}{$this->baseUrl}{$this->keyword}";
		return "{$url}?tag={$this->tag}&linkCode=ll2";
	}

	/**
	 * Get default response.
	 *
	 * @access protected
	 * @param string $item
	 * @return array
	 */
	protected function getDefault(string $item) : array
	{
		$keys = array_keys($this->selectors[$item]);
		return array_fill_keys($keys, null);
	}
}
