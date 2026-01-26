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
 * Apaapi data structure normalizer.
 */
final class Normalizer
{
	/**
	 * @access private
	 * @var int $limit, List limit
	 * @var bool $format, String format
	 * @var bool $format, Error format
	 * @var bool $order, Data order
	 */
	private static $limit = 5;
	private static $format = true;
	private static $error = true;
	private static $order = true;

	/**
	 * Set lists limit.
	 *
	 * @access public
	 * @param int $limit
	 * @return void
	 */
	public static function limit(int $limit) : void
	{
		self::$limit = $limit;
	}

	/**
	 * Keep default string format.
	 *
	 * @access public
	 * @return void
	 */
	public static function noFormat() : void
	{
		self::$format = false;
	}

	/**
	 * Keep default error format.
	 *
	 * @access public
	 * @return void
	 */
	public static function noError() : void
	{
		self::$error = false;
	}

	/**
	 * Disable order.
	 *
	 * @access public
	 * @return void
	 */
	public static function noOrder() : void
	{
		self::$order = false;
	}

	/**
	 * Get normalized response data.
	 *
	 * @access public
	 * @param array $data
	 * @param string $operation
	 * @return array
	 */
	public static function get(array $data, string $operation) : array
	{
		$data = self::parse($data, $operation);
		if ( isset($data['node']) ) {
			return self::normalizeNode($data['node']);
		}
		if ( isset($data['category']) ) {
			return self::normalizeCategory($data['category']);
		}
		return array_map(function ($item) {
			return self::normalize($item);
		}, $data);
	}

	/**
	 * Parse response data items.
	 *
	 * @access public
	 * @param array $data
	 * @param string $operation
	 * @return array
	 */
	public static function parse(array $data, string $operation) : array
	{
		return match ($operation) {
			'GetItems'       => self::parseItem($data),
			'SearchItems'    => self::parseSearch($data),
			'GetVariations'  => self::parseVariation($data),
			'GetBrowseNodes' => self::parseNode($data),
			default          => $data
		};
	}

	/**
	 * Format single id.
	 *
	 * @access public
	 * @param string $id
	 * @return string
	 */
	public static function formatId(string $id) : string
	{
		$id = self::stripSpace(
			strtoupper($id)
		);
		if ( self::isUrl($id) ) {
			return Keyword::parseBarcode($id);
		}
		return $id;
	}

	/**
	 * Format ids.
	 *
	 * @access public
	 * @param mixed $ids
	 * @return array
	 */
	public static function formatIds($ids) : array
	{
		if ( is_string($ids) ) {
			$ids = self::stripSpace(
				strtoupper($ids)
			);
			$ids = explode(',', $ids);
		}

		$ids = array_map(function ($id) {
			return self::formatId($id);
		}, (array)$ids);

		return $ids;
	}

	/**
	 * Format single keyword.
	 *
	 * @access public
	 * @param string $keyword
	 * @param bool $single
	 * @return string
	 */
	public static function formatKeyword(string $keyword) : string
	{
		$keyword = trim($keyword);

		if ( strpos($keyword, ',') !== false ) {
			$keyword = explode(',', $keyword);
			$keyword = (string)array_shift($keyword);
		}

		if ( self::isUrl($keyword) ) {
			return Keyword::parseBarcode($keyword);
		}

		return $keyword;
	}

	/**
	 * Format keywords.
	 *
	 * @access public
	 * @param mixed $keywords
	 * @return string
	 */
	public static function formatKeywords($keywords) : string
	{
		if ( !is_array($keywords) ) {
			$keywords = trim((string)$keywords);
			$keywords = explode(',', $keywords);
		}

		$keywords = array_map(function ($keyword) {
			return self::formatKeyword($keyword);
		}, $keywords);

		return implode(', ', $keywords);
	}

	/**
	 * Format cart items.
	 *
	 * @access public
	 * @param mixed $items
	 * @return array
	 */
	public static function formatCart($items) : array
	{
		if ( is_string($items) ) {
			$items = self::formatIds($items);
		}

		$items = (array)$items;
		$cart = [];

		foreach ($items as $key => $item) {
			if ( is_int($key) ) {
				$item = self::formatId((string)$item);
				$cart[$item] = '1';

			} else {
				$key = self::formatId($key);
				$cart[$key] = (string)intval($item);
			}
		}

		return $cart;
	}

	/**
	 * Format filter price.
	 *
	 * @access public
	 * @param mixed $price
	 * @return int
	 */
	public static function formatPrice($price) : int
	{
		$price = (string)$price;

		if ( strpos($price, '.') === false ) {
			$price = "{$price}00";

		} else {
			$price = self::removeString('.', $price);
		}

		return (int)$price;
	}

	/**
	 * Convert string to float.
	 *
	 * @access public
	 * @param string $string
	 * @return float
	 */
	public static function toFloat(string $string) : float
	{
		$string = self::stripSpace($string);
		return (float)self::replaceString(',', '.', $string);
	}

	/**
	 * Convert string to int.
	 *
	 * @access public
	 * @param string $string
	 * @return int
	 */
	public static function toInt(string $string) : int
	{
		$string = self::stripSpace($string);
		return (int)self::removeString(',', $string);
	}

	/**
	 * Format filter delivery.
	 *
	 * @access public
	 * @param mixed $delivery
	 * @return array
	 */
	public static function formatDelivery($delivery) : array
	{
		if ( is_string($delivery) ) {
			$delivery = self::stripSpace(
				strtolower($delivery)
			);
			$delivery = explode(',', $delivery);
		}

		$delivery = (array)$delivery;
		$flags = [];

		$delivery = array_map(function ($item) {
			return trim($item);
		}, $delivery);

		$shipping = Provider::SHIPPING;
		foreach ($delivery as $key => $item) {
			if ( isset($shipping[$item]) ) {
				$flags[] = $shipping[$item];
			}
		}

		return $flags;
	}

	/**
	 * Format filter sort.
	 *
	 * @access public
	 * @param string $sort
	 * @return string
	 */
	public static function formatSort(string $sort) : string
	{
		$sort = self::stripSpace(
			strtolower($sort)
		);

		$values = Provider::SORT;
		if ( isset($values[$sort]) ) {
			$sort = $values[$sort];

		} else {
			$sort = 'Relevance';
		}

		return $sort;
	}

	/**
	 * Format filter condition.
	 *
	 * @access public
	 * @param string $condition
	 * @return string
	 */
	public static function formatCondition(string $condition) : string
	{
		$condition = self::stripSpace(
			strtolower($condition)
		);
		return ucfirst($condition);
	}

	/**
	 * Format filter category.
	 *
	 * @access public
	 * @param string $category
	 * @return string
	 */
	public static function formatCategory(string $category) : string
	{
		return self::stripSpace($category);
	}

	/**
	 * Format filter language.
	 *
	 * @access public
	 * @param mixed $language
	 * @return array
	 */
	public static function formatLanguage($language) : array
	{
		return (array)$language;
	}

	/**
	 * Format filter currency.
	 *
	 * @access public
	 * @param string $currency
	 * @return string
	 */
	public static function formatCurrency(string $currency) : string
	{
		return self::stripSpace($currency);
	}

	/**
	 * Format filter marketplace.
	 *
	 * @access public
	 * @param string $marketplace
	 * @return string
	 */
	public static function formatMarketplace(string $marketplace) : string
	{
		return self::stripSpace(
			strtolower($marketplace)
		);
	}

	/**
	 * Format request locale.
	 *
	 * @access public
	 * @param string $locale
	 * @return mixed
	 */
	public static function formatLocale(string $locale) : mixed
	{
		$locale = self::stripSpace(
			strtolower($locale)
		);
		if ( ($l = explode('_', $locale)) ) {
			$locale = $l[0];
		}
		if ( !in_array($locale, Provider::getLocales()) ) {
			$locale = false;
		}
		return $locale;
	}

	/**
	 * Format response error.
	 *
	 * @access public
	 * @param string $body
	 * @return string
	 */
	public static function formatError(string $body) : string
	{
		// Empty or whitespace-only body
		if ( empty($body) || trim($body) === '' ) {
			return 'Empty error response from API';
		}

		$trimmed = trim($body);

		// Check for empty JSON objects/arrays before decoding
		if ( $trimmed === '{}' || $trimmed === '[]' ) {
			return 'Invalid credentials';
		}

		// Decode JSON response
		$data = self::decode($body);

		// Valid JSON with data
		if ( !empty($data) ) {

			// Creators API error format: {message, reason, type}
			if ( isset($data['message']) ) {
				return rtrim($data['message'], '.');
			}

			// Non-standard JSON error format
			return json_encode($data);
		}

		// Non-JSON response (HTML, plain text, etc.)
		$error = self::stripTags($body);
		$error = self::stripSpace($error, ' ');
		$error = self::removeRegex('/Not Found/', $error);
		$error = self::removeRegex('/[0-9]/', $error);
		$error = rtrim(trim($error), '.');

		// If all cleaning resulted in empty string
		if ( empty($error) ) {
			return 'Request failed - check credentials or API endpoint';
		}

		return $error;
	}

	/**
	 * Format path.
	 *
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function formatPath(string $path) : string
	{
		$path = self::replaceString(['\\', '//'], '/', $path);
		return rtrim($path, '/');
	}

	/**
	 * Parse keyword.
	 *
	 * @access public
	 * @param string $keyword
	 * @param int $limit
	 * @return string
	 */
	public static function parseKeyword(string $keyword, int $limit = 30) : string
	{
		return substr($keyword, 0, $limit);
	}

	/**
	 * Decode data.
	 *
	 * @access public
	 * @param string $data
	 * @return array
	 */
	public static function decode(string $data) : array
	{
		return (array)@json_decode($data, true);
	}

	/**
	 * Convert types to string.
	 *
	 * @access public
	 * @param mixed $value
	 * @param bool $null Nullable
	 * @return string
	 * @internal
	 */
	public static function toString(mixed $value, bool $null = false) : string
	{
		return match (true) {
			$value === false         => 'false',
			$value === true          => 'true',
			$value === null && $null => 'null',
			is_array($value)         => self::serialize($value),
			is_object($value)        => self::serialize($value),
			default                  => (string)$value
		};
	}

	/**
	 * Serialize value.
	 *
	 * @access public
	 * @param mixed $values
	 * @return string
	 * @internal
	 */
	public static function serialize(mixed $value) : string
	{
		$value = self::toJson($value, 256);
		return (string)@serialize(trim($value));
	}

	/**
	 * Encode JSON using flags.
	 *
	 * @access public
	 * @param mixed $value
	 * @param int $flags
	 * @param int $depth
	 * @return mixed
	 */
	public static function toJson($value, int $flags = 64 | 256, int $depth = 512) : mixed
	{
		return json_encode($value, $flags, $depth);
	}

	/**
	 * Format country code (Fix 3 DIGIT ISO),
	 * Lowercased ISO (3166‑1 alpha‑2).
	 *
	 * @access private
	 * @param string $code
	 * @return string
	 * @see https://countrycode.org/
	 */
	public static function formatCountryCode(string $code) : string
	{
		return substr(strtolower($code), 0, 2);
	}

	/**
	 * Format TLD from country code.
	 *
	 * @access public
	 * @param string $code
	 * @return string
	 */
	public static function formatTLD(string $code) : string
	{
		return match ($code) {
			'au'       => 'com.au',
			'be'       => 'com.be',
			'br'       => 'com.br',
			'jp'       => 'co.jp',
			'mx'       => 'com.mx',
			'tr'       => 'com.tr',
			'uk', 'gb' => 'co.uk',
			'us'       => 'com',
			default    => $code
		};
	}

	/**
	 * Apply dynamic args recursively.
	 * 
	 * @access public
	 * @param mixed $data
	 * @param array $args
	 * @return mixed
	 */
	public static function applyArgs($data, array $args) : mixed
	{
		if ( is_array($data) ) {
			return array_map(function ($item) use ($args) {
				return self::applyArgs($item, $args);
			}, $data);

		} elseif ( is_string($data) ) {
			return self::replaceString(
				array_keys($args),
				array_values($args),
				$data
			);
		}

		return $data;
	}

	/**
	 * Order data.
	 *
	 * @access public
	 * @param array $data
	 * @param mixed $orderby
	 * @param string $order
	 * @param bool $preserve (keys)
	 * @return array
	 */
	public static function order(array $data, $orderby, string $order = 'asc') : array
	{
		if ( !self::$order ) {
			return $data;
		}

		$orderby = self::formatOrderBy($orderby);

		foreach ($orderby as $key => $dir) {
			$orderby[$key] = ('desc' === strtolower($dir)) ? 'desc' : 'asc';
		}

		$sort = function ($a, $b) use ($orderby) {

			$a = (array)$a;
			$b = (array)$b;

			foreach ($orderby as $key => $dir) {

				if ( !isset($a[$key]) || !isset($b[$key]) ) {
					continue;
				}

				if ( $a[$key] == 0 ) return 1;
				if ( $b[$key] == 0 ) return -1;

				if ( $a[$key] == $b[$key] ) {
					continue;
				}

				$val = ('desc' === $dir) ? [1, -1] : [-1, 1];

				if ( is_numeric($a[$key]) && is_numeric($b[$key]) ) {
					return ($a[$key] < $b[$key]) ? $val[0] : $val[1];
				}

				return 0 > strcmp($a[$key], $b[$key]) ? $val[0] : $val[1];
			}

			return 0;
		};

		usort($data, $sort);
		return $data;
	}

	/**
	 * Format string.
	 *
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function formatString(string $string) : string
	{
		if ( !self::$format ) {
			$string = self::replaceRegex('/[^\x{0000}-\x{FFFF}]/u', ' ', $string);
			$string = self::replaceString(['【',], ' [', $string);
			$string = self::replaceString(['】',], '] ', $string);
		}

		$string = trim($string);
		$string = self::replaceString("\r", "\n", $string);
		$string = self::replaceRegex(['/\n+/', '/[ \t]+/'], ["\n", ' '], $string);

		return $string;
	}

	/**
	 * Replace string.
	 *
	 * @access public
	 * @param mixed $search
	 * @param mixed $replace
	 * @param mixed $string
	 * @return string
	 */
	public static function replaceString($search, $replace, $string) : string
	{
		return (string)str_replace($search, $replace, $string);
	}

	/**
	 * Remove string.
	 *
	 * @access public
	 * @param string $search
	 * @param string $string
	 * @return string
	 */
	public static function removeString($search, $string) : string
	{
		return self::replaceString($search, '', $string);
	}

	/**
	 * Strip space.
	 *
	 * @access public
	 * @param string $string
	 * @param string $replace
	 * @return string
	 */
	public static function stripSpace(string $string, string $replace = '') : string
	{
		return self::replaceRegex('/\s+/u', $replace, trim($string));
	}

	/**
	 * Strip tags.
	 *
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function stripTags(string $string) : string
	{
		return strip_tags($string);
	}

	/**
	 * Replace string using regex.
	 *
	 * @access public
	 * @param mixed $regex
	 * @param mixed $replace
	 * @param mixed $subject
	 * @return string
	 */
	public static function replaceRegex($regex, $replace, $subject) : string
	{
		return (string)preg_replace($regex, $replace, $subject);
	}

	/**
	 * Remove string using regex.
	 *
	 * @access public
	 * @param mixed $regex
	 * @param mixed $subject
	 * @return string
	 */
	public static function removeRegex($regex, $subject) : string
	{
		return (string)self::replaceRegex($regex, '', $subject);
	}

	/**
	 * Remove trailing slashes and backslashes if exist.
	 * 
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function untrailingSlash(string $string) : string
	{
		return rtrim($string, '/\\');
	}

	/**
	 * Format order by.
	 *
	 * @access private
	 * @param mixed $order
	 * @return array
	 */
	private static function formatOrderBy($order) : array
	{
		if ( is_string($order) ) {
			$order = [$order => 'asc'];
		}

		$order = (array)$order;

		foreach ($order as $key => $dir) {
			if ( is_int($key) ) {
				unset($order[$key]);
				$key = (string)$dir;
				$dir = 'asc';

			} else {
				$key = (string)$key;
				$dir = (string)$dir;
			}
			$order[$key] = $dir;
		}

		return $order;
	}

	/**
	 * Normalize item.
	 *
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private static function normalize(array $item) : array
	{
		return [
			'asin'         => self::extractASIN($item),
			'ean'          => self::extractEAN($item),
			'node'         => self::extractNode($item),
			'root'         => self::extractRoot($item),
			'rank'         => self::extractRank($item),
			'title'        => self::extractTitle($item),
			'category'     => self::extractCategory($item),
			'brand'        => self::extractBrand($item),
			'url'          => self::extractUrl($item),
			'image'        => self::extractImage($item),
			'price'        => self::extractPrice($item),
			'discount'     => self::extractDiscount($item),
			'discounted'   => self::extractDiscounted($item),
			'percent'      => self::extractPercent($item),
			'currency'     => self::extractCurrency($item),
			'availability' => self::extractAvailability($item),
			'shipping'     => self::extractShipping($item),
			'gallery'      => self::extractGallery($item),
			'attributes'   => self::extractAttributes($item),
			'features'     => self::extractFeatures($item)
		];
	}

	/**
	 * Normalize node.
	 *
	 * @access private
	 * @param array $data
	 * @return array
	 */
	private static function normalizeNode(array $data) : array
	{
		$node = [];
		if ( ($id = $data['id'] ?? false) ) {
			$node = [
				'id'       => $id,
				'name'     => $data['contextFreeName'] ?? '',
				'root'     => $data['isRoot'] ?? false,
				'url'      => Provider::getNodeUrl($id),
				'ancestor' => self::parseAncestor($data),
				'children' => self::parseChildren($data)
			];
		}
		return $node;
	}

	/**
	 * Normalize category.
	 *
	 * @access private
	 * @param array $data
	 * @return array
	 */
	private static function normalizeCategory(array $data) : array
	{
		$categories = [];
		foreach ($data as $category) {
			if ( ($id = $category['id'] ?? false) ) {
				$categories[] = [
					'id'   => $id,
					'name' => $category['displayName'] ?? ''
				];
			}
		}
		return $categories;
	}

	/**
	 * Parse node.
	 *
	 * @access private
	 * @param array $data
	 * @return array
	 */
	private static function parseNode(array $data) : array
	{
		$data = $data['browseNodesResult']['browseNodes'][0] ?? [];
		return ['node' => $data];
	}

	/**
	 * Parse item.
	 *
	 * @access private
	 * @param array $data
	 * @return array
	 */
	private static function parseItem(array $data) : array
	{
		return $data['itemsResult']['items'] ?? [];
	}

	/**
	 * Parse search.
	 *
	 * @access private
	 * @param array $data
	 * @return array
	 */
	private static function parseSearch(array $data) : array
	{
		if ( Builder::$isCategory ) {
			if ( isset($data['searchResult']['searchRefinements']) ) {
				$data = $data['searchResult']['searchRefinements'];
				$data = $data['searchIndex']['bins'] ?? [];
				return ['category' => $data];
			}
		}
		return $data['searchResult']['items'] ?? [];
	}

	/**
	 * Parse variation.
	 *
	 * @access private
	 * @param array $data
	 * @return array
	 */
	private static function parseVariation(array $data) : array
	{
		return $data['variationsResult']['items'] ?? [];
	}

	/**
	 * Parse node ancestor.
	 *
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private static function parseAncestor(array $item) : array
	{
		$ancestor = [];
		if ( ($id = $item['ancestor']['id'] ?? false) ) {
			$ancestor = [
				'id'   => $id,
				'name' => $item['ancestor']['contextFreeName'] ?? '',
				'url'  => Provider::getNodeUrl($id)
			];
		}
		return $ancestor;
	}

	/**
	 * Parse node children.
	 *
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private static function parseChildren(array $item) : array
	{
		$children = [];
		$item = $item['children'] ?? [];
		foreach ($item as $child) {
			if ( ($id = $child['id']) ) {
				$children[] = [
					'id'   => $id,
					'name' => $child['contextFreeName'] ?? '',
					'url'  => Provider::getNodeUrl($id)
				];
			}
		}
		return $children;
	}

	/**
	 * Extract item EAN.
	 *
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function extractEAN(array $item) : string
	{
		return $item['itemInfo']['externalIds']['eans']['displayValues'][0] ?? '';
	}

	/**
	 * Extract item ASIN.
	 * 
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function extractASIN(array $item) : string
	{
		return $item['asin'] ?? '';
	}

	/**
	 * Extract item title.
	 * 
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function extractTitle(array $item) : string
	{
		$title = $item['itemInfo']['title']['displayValue'] ?? '';
		return self::formatString($title);
	}

	/**
	 * Extract item url.
	 * 
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function extractUrl(array $item) : string
	{
		return $item['detailPageURL'] ?? '';
	}

	/**
	 * Extract item image.
	 * 
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function extractImage(array $item) : string
	{
		return $item['images']['primary']['large']['url'] ?? '';
	}

	/**
	 * Extract item category.
	 *
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function extractCategory(array $item) : string
	{
		$node = $item['browseNodeInfo']['browseNodes'][0] ?? [];
		$count = count($node);
		for ($i = 0; $i < $count; $i++) {
			if ( !isset($node['ancestor']) ) break;
			$node = $node['ancestor'];
		}
		return $node['contextFreeName'] ?? '';
	}

	/**
	 * Extract item node Id.
	 *
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function extractNode(array $item) : string
	{
		$node = $item['browseNodeInfo']['browseNodes'][0] ?? [];
		return $node['id'] ?? '';
	}

	/**
	 * Extract item root Id.
	 *
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function extractRoot(array $item) : string
	{
		$node = $item['browseNodeInfo']['browseNodes'][0] ?? [];
		$count = count($node);
		for ($i = 0; $i < $count; $i++) {
			if ( !isset($node['ancestor']) ) break;
			$node = $node['ancestor'];
		}
		return $node['id'] ?? '';
	}

	/**
	 * Extract item rank.
	 *
	 * @access private
	 * @param array $item
	 * @return int
	 */
	private static function extractRank(array $item) : int
	{
		$node = $item['browseNodeInfo']['browseNodes'][0] ?? [];
		return $node['salesRank'] ?? 0;
	}

	/**
	 * Extract item price.
	 * 
	 * @access private
	 * @param array $item
	 * @return float
	 */
	private static function extractPrice(array $item) : float
	{
		$listing = $item['offersV2']['listings'][0] ?? [];
		return $listing['price']['money']['amount'] ?? 0;
	}

	/**
	 * Extract item currency.
	 * 
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function extractCurrency(array $item) : string
	{
		$listing = $item['offersV2']['listings'][0] ?? [];
		return $listing['price']['money']['currency'] ?? '';
	}

	/**
	 * Extract item discount.
	 *
	 * @access private
	 * @param array $item
	 * @return float
	 */
	private static function extractDiscount(array $item) : float
	{
		$listing = $item['offersV2']['listings'][0] ?? [];
		return $listing['price']['savings']['money']['amount'] ?? 0;
	}

	/**
	 * Extract item discount percent.
	 *
	 * @access private
	 * @param array $item
	 * @return float
	 */
	private static function extractPercent(array $item) : float
	{
		$listing = $item['offersV2']['listings'][0] ?? [];
		return $listing['price']['savings']['percentage'] ?? 0;
	}

	/**
	 * Extract item discounted.
	 *
	 * @access private
	 * @param array $item
	 * @return float
	 */
	private static function extractDiscounted(array $item) : float
	{
		$listing = $item['offersV2']['listings'][0] ?? [];

		// Original price
		if ( isset($listing['price']['savingBasis']['money']['amount']) ) {
			return round($listing['price']['savingBasis']['money']['amount'], 2);
		}

		// Fallback: calculate from current price + discount
		$discounted = self::extractPrice($item) + self::extractDiscount($item);
		return round($discounted, 2);
	}

	/**
	 * Extract item shipping.
	 * 
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private static function extractShipping(array $item) : array
	{
		$listing = $item['offersV2']['listings'][0] ?? [];

		return [
			'fulfilled' => $listing['deliveryInfo']['isAmazonFulfilled'] ?? false,
			'free'      => $listing['deliveryInfo']['isFreeShippingEligible'] ?? false,
			'prime'     => $listing['deliveryInfo']['isPrimeEligible'] ?? false
		];
	}

	/**
	 * Extract item availability.
	 * 
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function extractAvailability(array $item) : string
	{
		$listing = $item['offersV2']['listings'][0] ?? [];
		return $listing['availability']['type'] ?? '';
	}

	/**
	 * Extract item brand.
	 * 
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function extractBrand(array $item) : string
	{
		return $item['itemInfo']['byLineInfo']['brand']['displayValue'] ?? '';
	}

	/**
	 * Extract item features.
	 * 
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private static function extractFeatures(array $item) : array
	{
		$features = $item['itemInfo']['features']['displayValues'] ?? [];
		if ( self::$limit ) {
			$features = array_slice($features, 0, self::$limit);
		}
		foreach ($features as $key => $feature) {
			$feature = self::formatString($feature);
			$features[$key] = $feature;
		}
		return $features;
	}

	/**
	 * Extract item gallery.
	 * 
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private static function extractGallery(array $item) : array
	{
		$gallery = [];
		$variants = $item['images']['variants'] ?? [];
		foreach ($variants as $variant) {
			$gallery[] = $variant['large']['url'];
		}
		if ( self::$limit ) {
			$gallery = array_slice($gallery, 0, self::$limit);
		}
		return $gallery;
	}

	/**
	 * Extract item attributes.
	 * 
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private static function extractAttributes(array $item) : array
	{
		return [
			...self::extractManufacturingInfo($item),
			...self::extractProductInfo($item),
			...self::extractDimensions($item)
		];
	}

	/**
	 * Extract manufacturing information.
	 * 
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private static function extractManufacturingInfo(array $item) : array
	{
		$info = $item['itemInfo']['manufactureInfo'] ?? [];
		return [
			'model'    => $info['model']['displayValue'] ?? '',
			'warranty' => $info['warranty']['displayValue'] ?? ''
		];
	}

	/**
	 * Extract product information.
	 * 
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private static function extractProductInfo(array $item) : array
	{
		$atts = $item['itemInfo']['productInfo'] ?? [];
		return [
			'color' => $atts['color']['displayValue'] ?? '',
			'size'  => $atts['size']['displayValue'] ?? '',
			'date'  => $atts['releaseDate']['displayValue'] ?? '',
			'count' => $atts['unitCount']['displayValue'] ?? 1,
			'adult' => $atts['isAdultProduct']['displayValue'] ?? false
		];
	}

	/**
	 * Extract dimensions information.
	 * 
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private static function extractDimensions(array $item) : array
	{
		$dims = $item['itemInfo']['productInfo']['itemDimensions'] ?? [];
		$dimensions = ['height', 'length', 'weight', 'width'];

		$result = [];
		foreach ($dimensions as $dimension) {
			$result[$dimension] = self::extractDimensionValue($dims, $dimension);
		}
		return $result;
	}

	/**
	 * Extract single dimension value.
	 * 
	 * @access private
	 * @param array $dims
	 * @param string $type
	 * @return array
	 */
	private static function extractDimensionValue(array $dims, string $type) : array
	{
		return [
			'value' => $dims[$type]['displayValue'] ?? 0,
			'unit'  => $dims[$type]['unit'] ?? ''
		];
	}

	/**
	 * Check URL.
	 *
	 * @access private
	 * @param string $keyword
	 * @return bool
	 */
	private static function isUrl(string $keyword) : bool
	{
		if ( strpos(strtolower($keyword), 'amazon') !== false ) {
			return (bool)filter_var($keyword, FILTER_VALIDATE_URL);
		}
		return false;
	}
}
