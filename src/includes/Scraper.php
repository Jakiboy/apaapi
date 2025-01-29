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

use Apaapi\exceptions\{RequestException, ScraperException};
use DOMDocument, DOMNodeList, DOMXPath;

/**
 * Apaapi data scraper.
 */
abstract class Scraper
{
	/**
	 * @access public
	 * @var string BASE_URL
	 * @var string SEARCH_URL
	 * @var array SELECTORS
	 */
	public const BASE_URL         = '/dp/';
	public const SEARCH_URL       = '/s/?field-keywords=';
	public const SELECTORS        = [];
	public const SEARCH_SELECTORS = [];

	/**
	 * @access protected
	 * @var string $keyword
	 * @var string $locale
	 * @var string $tag
	 * @var string $baseUrl
	 * @var array $selectors
	 * @var array $searchSelectors
	 * @var array $item
	 */
	protected $keyword;
	protected $locale;
	protected $tag;
	protected $baseUrl;
	protected $searchUrl;
	protected $selectors = [];
	protected $searchSelectors = [];
	protected $item;

	/**
	 * Init scraper.
	 *
	 * @access protected
	 * @param string $keyword
	 * @param string $locale
	 * @param ?string $tag
	 * @throws RequestException
	 */
	protected function __construct(string $keyword, string $locale = 'com', ?string $tag = null)
	{
		$this->keyword = Normalizer::formatString($keyword);
		$this->locale = Normalizer::formatLocale($locale);
		$this->tag = $tag;

		if ( !$this->locale ) {
			throw new RequestException(
				RequestException::invalidLocale($this->locale)
			);
		}

		$this->baseUrl = static::BASE_URL;
		$this->searchUrl = static::SEARCH_URL;
		$this->selectors = static::SELECTORS;
		$this->searchSelectors = static::SEARCH_SELECTORS;
		$this->item = strtolower(basename(static::class));
	}

	/**
	 * Format scrapped data.
	 *
	 * @access protected
	 * @param array $data
	 * @return array
	 */
	abstract protected function format(array $data) : array;

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
	 * Set search URL.
	 *
	 * @access public
	 * @param string $searchUrl
	 * @return array
	 */
	public function setSearchUrl(string $searchUrl) : self
	{
		$this->searchUrl = $searchUrl;
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
	 * Generate request header.
	 *
	 * @access public
	 * @param string $locale
	 * @return array
	 */
	public static function generateHeader(string $locale = 'com') : array
	{
		$currency = self::generateCurrency($locale);
		$time = self::generateTime();
		$id = self::generateId();

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
	 * Parse data from HTML response (Cached).
	 *
	 * @access protected
	 * @return array
	 * @throws ScraperException
	 */
	protected function parse() : array
	{
		// Validate keyword
		$this->keyword = Normalizer::formatId($this->keyword);
		if ( !$this->isKeyword() ) {
			throw new ScraperException(
				ScraperException::invalidKeyword($this->keyword)
			);
		}

		// Get single item data
		$url = $this->getUrl();
		$default = $this->getDefault();
		$default = array_merge($default, ['url' => $url]);

		// Generate cache key
		$key = [$this->item, $this->locale, $this->keyword, $this->tag];
		$key = implode('-', $key);
		$key = Cache::generateKey($key);

		if ( !($data = Cache::get($key)) ) {

			$response = $this->request($url, $status);
			if ( $status == 200 ) {
				$data = $this->process($response);
				$data = $this->format($data);
				$data = array_merge($default, $data);
				Cache::set($key, $data);
			}

		}

		return $data ?: $default;
	}

	/**
	 * Parse many items data from HTML response (Cached).
	 *
	 * @access protected
	 * @param int $count
	 * @return array
	 */
	protected function parseMany(int $count = 10) : array
	{
		// Set max items to process
		$count = $count > 15 ? 15 : $count;

		// Get search URL
		$url = $this->getUrl(search: true, tag: false);

		$response = $this->request($url);
		$data = $this->processMany($response, $count);

		return $data;
	}

	/**
	 * Send scraper request.
	 *
	 * @access protected
	 * @param string $url
	 * @param ?int &$status
	 * @return string
	 */
	protected function request(string $url, ?int &$status = null) : string
	{
		$header = self::generateHeader($this->locale);
		$client = new Client($url, [
			'header'  => $header,
			'timeout' => 15
		]);

		$client->setEncoding()->get();
		$response = $client->getBody();
		$status = $client->getStatusCode();

		return $response;
	}

	/**
	 * Get scraper URL.
	 *
	 * @access protected
	 * @param bool $search
	 * @param bool $tag
	 * @return string
	 */
	protected function getUrl(bool $search = false, bool $tag = true) : string
	{
		$host = Provider::HOST;
		$url = str_replace('{locale}', $this->locale, $host);

		$base = $this->baseUrl;
		if ( $search ) {
			$this->keyword = urlencode($this->keyword);
			$base = $this->searchUrl;
		}
		$url = "{$url}{$base}{$this->keyword}";

		if ( $tag && $this->tag ) {
			$url = "{$url}?tag={$this->tag}&linkCode=ll2";
		}

		return $url;
	}

	/**
	 * Get default response.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getDefault() : array
	{
		$keys = array_keys($this->selectors);
		return array_fill_keys($keys, null);
	}

	/**
	 * Validate keyword.
	 *
	 * @access protected
	 * @return bool
	 */
	protected function isKeyword() : bool
	{
		return Keyword::isASIN($this->keyword)
			|| Keyword::isISBN($this->keyword);
	}

	/**
	 * Process HTML response.
	 *
	 * @access private
	 * @param string $response
	 * @param array $selectors
	 * @return array
	 */
	private function process(string $response) : array
	{
		$data = [];

		if ( class_exists('DomDocument') ) {

			// Ignore XML errors
			libxml_use_internal_errors(true);

			// Init DOM document
			$dom = new DOMDocument();
			$dom->loadHTML($response);
			$xPath = new DOMXPath($dom);

			foreach ($this->selectors as $key => $selector) {
				if ( is_array($selector) ) {
					$data[$key] = $this->processSubSelectors($xPath, $selector);

				} else {
					$data[$key] = $this->processSelector($xPath, $selector);
				}
			}

			// Clear XML errors
			libxml_clear_errors();
		}

		return $data;
	}

	/**
	 * Process many items from parent HTML response.
	 *
	 * @access private
	 * @param string $response
	 * @param int $count
	 * @return array
	 */
	private function processMany(string $response, int $count = 10) : array
	{
		$data = [];

		if ( class_exists('DomDocument') ) {
			// Ignore XML errors
			libxml_use_internal_errors(true);

			// Init DOM document
			$dom = new DOMDocument();
			$dom->loadHTML($response);
			$xPath = new DOMXPath($dom);

			// Get search selector
			$search = $this->searchSelectors['@search'] ?? [];
			$search = array_values($search);
			$search = implode('', $search);

			// Set items count
			$search = str_replace('{count}', (string)$count, $search);

			// Remove search selector
			unset($this->searchSelectors['@search']);

			// Query search selector
			$items = $xPath->query($search);

			foreach ($items as $item) {
				$sub = [];
				foreach ($this->searchSelectors as $key => $selector) {
					if ( is_array($selector) ) {
						$sub[$key] = $this->processSubSelectors($xPath, $selector, $item);
					} else {
						$sub[$key] = $this->processSelector($xPath, $selector, $item);
					}
				}
				$data[] = $sub;
			}

			// Clear XML errors
			libxml_clear_errors();
		}

		return $data;
	}

	private function processSubSelectors(DOMXPath $xPath, array $subSelectors) : array
	{
		$sub = [];
		foreach ($subSelectors as $subKey => $subSelector) {
			$nodes = $xPath->query($subSelector);
			if ( $nodes instanceof DOMNodeList && $nodes->length > 0 ) {
				$node = $nodes->item(0);
				$value = (string)$node->nodeValue;
				$sub[$subKey] = Normalizer::formatString($value);
			}
		}
		return $sub;
	}

	/**
	 * Process single selector.
	 *
	 * @access protected
	 * @param DOMXPath $xPath
	 * @param string $selector
	 * @return string
	 */
	private function processSelector(DOMXPath $xPath, string $selector) : string
	{
		$nodes = $xPath->query($selector);
		if ( $nodes instanceof DOMNodeList && $nodes->length > 0 ) {
			if ( substr($selector, -4) === '//li' ) {
				return $this->processListNodes($nodes);

			} else {
				$node = $nodes->item(0);
				$value = (string)$node->nodeValue;
				return Normalizer::formatString($value);
			}
		}
		return '';
	}

	/**
	 * Process list nodes.
	 *
	 * @access private
	 * @param DOMNodeList $nodes
	 * @return string
	 */
	private function processListNodes(DOMNodeList $nodes) : string
	{
		$list = [];
		foreach ($nodes as $node) {
			$value = (string)$node->nodeValue;
			$list[] = Normalizer::formatString($value);
		}
		return Normalizer::toJson($list);
	}

	/**
	 * Generate currency.
	 *
	 * @access private
	 * @param string $locale
	 * @return string
	 */
	private static function generateCurrency(string $locale = 'com') : string
	{
		$currency = 'USD';
		if ( $locale !== 'com' ) {
			$currency = Provider::getCurrency($locale)[0] ?? $currency;
		}
		return $currency;
	}

	/**
	 * Generate unique ID.
	 *
	 * @access private
	 * @return string
	 */
	private static function generateId() : string
	{
		$id = mt_rand(100, 999);
		$id .= '-' . mt_rand(1000000, 9999999);
		$id .= '-' . mt_rand(1000000, 9999999);
		return $id;
	}

	/**
	 * Generate time.
	 *
	 * @access private
	 * @return string
	 */
	private static function generateTime() : string
	{
		return time() . 'l';
	}
}
