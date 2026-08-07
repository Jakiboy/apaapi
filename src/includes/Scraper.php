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

use Apaapi\exceptions\{RequestException, ScraperException};
use DOMDocument, DOMNodeList, DOMXPath;

/**
 * Apaapi data scraper (Credential-less).
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
	protected $lastResponse = '';
	protected $debug = [];

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
	 * Override scraper runtime configuration.
	 *
	 * @access public
	 * @param array $config
	 * @param bool $replace
	 * @return self
	 */
	public function setConfig(array $config, bool $replace = false) : self
	{
		if ( $replace ) {
			ScraperConfig::replace($config);
		} else {
			ScraperConfig::set($config);
		}

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
		$language = self::generateLanguage($locale);
		$acceptLanguage = self::buildAcceptLanguage($language);

		$time = self::generateTime();
		$id = self::generateId();

		$cookie = [
			"i18n-prefs={$currency}",
			"lc-main={$language}",
			"session-id={$id}",
			"session-id-time={$time}"
		];

		return [
			'Cookie'          => implode('; ', $cookie),
			'Connection'      => 'close',
			'Accept-Language' => $acceptLanguage
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
			$this->lastResponse = $response;
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
	 * Get last raw HTML response.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getLastResponse() : string
	{
		return $this->lastResponse;
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
		$client = new Client($url, $this->getClientOptions());

		$client->setEncoding()->get();
		$response = $client->getBody();
		$status = $client->getStatusCode();
		$this->debug[] = "Credential-less request status: {$status}";

		return $response;
	}

	/**
	 * Build credential-less HTTP client options from runtime configuration.
	 *
	 * @access private
	 * @return array
	 */
	private function getClientOptions() : array
	{
		$config = ScraperConfig::all();

		return [
			'header'     => self::generateHeader($this->locale),
			'timeout'    => (int)($config['timeout'] ?? 15),
			'follow'     => (bool)($config['followRedirects'] ?? true),
			'redirect'   => (int)($config['maxRedirects'] ?? 5),
			'retries'    => (int)($config['maxRetries'] ?? 3),
			'retryDelay' => (int)($config['retryDelay'] ?? 2),
			'scraper'    => (bool)($config['stealthEnabled'] ?? true)
		];
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

	private function processSubSelectors(DOMXPath $xPath, array $subSelectors, $context = null) : array
	{
		$sub = [];
		foreach ($subSelectors as $subKey => $subSelector) {
			$nodes = $context ? $xPath->query($subSelector, $context) : $xPath->query($subSelector);
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
	 * @param mixed $context
	 * @return string
	 */
	private function processSelector(DOMXPath $xPath, string $selector, $context = null) : string
	{
		$nodes = $context ? $xPath->query($selector, $context) : $xPath->query($selector);
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
	 * Get debug messages.
	 *
	 * @access public
	 * @return array
	 */
	public function getDebug() : array
	{
		return $this->debug;
	}

	/**
	 * Reset debug messages.
	 *
	 * @access public
	 * @return void
	 */
	public function clearDebug() : void
	{
		$this->debug = [];
	}

	/**
	 * Save HTML snapshot for debugging.
	 *
	 * @access protected
	 * @param string $html
	 * @param string $suffix
	 * @return string
	 */
	protected function saveDebugHtml(string $html, string $suffix = 'debug') : string
	{
		$path = sys_get_temp_dir();
		$file = "apaapi-{$this->item}-{$this->locale}-{$suffix}.html";
		$filePath = Normalizer::formatPath("{$path}/{$file}");
		@file_put_contents($filePath, $html);
		$this->debug[] = "Saved debug HTML: {$filePath}";
		return $filePath;
	}

	/**
	 * Extract text from raw HTML using regex patterns.
	 *
	 * @access protected
	 * @param string $html
	 * @param array $patterns
	 * @param array $filters
	 * @return ?string
	 */
	protected function extractText(string $html, array $patterns, array $filters = []) : ?string
	{
		foreach ($patterns as $pattern) {
			if ( preg_match($pattern, $html, $matches) ) {
				$text = html_entity_decode(strip_tags(trim((string)($matches[1] ?? ''))), ENT_QUOTES, 'UTF-8');
				$text = preg_replace('/\s+/', ' ', $text);
				$text = trim((string)$text);

				if ( empty($text) ) {
					continue;
				}

				$valid = true;
				foreach ($filters as $filter) {
					if ( stripos($text, $filter) !== false ) {
						$valid = false;
						break;
					}
				}

				if ( $valid ) {
					return $text;
				}
			}
		}

		return null;
	}

	/**
	 * Extract URL from raw HTML using regex patterns.
	 *
	 * @access protected
	 * @param string $html
	 * @param array $patterns
	 * @param array $validators
	 * @return ?string
	 */
	protected function extractUrl(string $html, array $patterns, array $validators = []) : ?string
	{
		foreach ($patterns as $pattern) {
			if ( preg_match($pattern, $html, $matches) ) {
				$url = trim((string)($matches[1] ?? ''));
				if ( empty($url) ) {
					continue;
				}

				if ( strpos($url, '//') === 0 ) {
					$url = "https:{$url}";
				}

				if ( empty($validators) ) {
					return $url;
				}

				foreach ($validators as $validator) {
					if ( stripos($url, $validator) !== false ) {
						return $url;
					}
				}
			}
		}

		return null;
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
	 * Generate marketplace language code used for lc-main cookie.
	 *
	 * @access private
	 * @param string $locale
	 * @return string
	 */
	private static function generateLanguage(string $locale = 'com') : string
	{
		if ( $locale === 'com' ) {
			return 'en_US';
		}

		return Provider::getLanguages($locale)[0] ?? 'en_US';
	}

	/**
	 * Build Accept-Language header from a locale language code.
	 *
	 * @access private
	 * @param string $language
	 * @return string
	 */
	private static function buildAcceptLanguage(string $language) : string
	{
		$language = trim($language);
		if ( $language === '' ) {
			return 'en-US,en;q=0.9';
		}

		$tag = str_replace('_', '-', $language);
		$base = strtolower((string)strtok($tag, '-'));

		if ( $base === 'en' ) {
			return "{$tag},en;q=0.9";
		}

		return "{$tag},{$base};q=0.9,en-US;q=0.8,en;q=0.7";
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
