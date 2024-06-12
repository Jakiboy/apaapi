<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.2.0
 * @copyright : (c) 2019 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

declare(strict_types=1);

namespace Apaapi\includes;

/**
 * Apaapi data normalizer.
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
	public static function limit(int $limit)
	{
		self::$limit = $limit;
	}

	/**
	 * Keep default string format.
	 *
	 * @access public
	 * @return void
	 */
	public static function noFormat()
	{
		self::$format = false;
	}

	/**
	 * Keep default error format.
	 *
	 * @access public
	 * @return void
	 */
	public static function noError()
	{
		self::$error = false;
	}

	/**
	 * Disable order.
	 *
	 * @access public
	 * @return void
	 */
	public static function noOrder()
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
		return array_map(function($item) {
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
        switch ($operation) {
            case 'GetItems':
                return self::parseItem($data);
                break;

            case 'SearchItems':
				return self::parseSearch($data);
                break;
				
            case 'GetVariations':
				return self::parseVariation($data);
                break;
				
            case 'GetBrowseNodes':
                return self::parseNode($data);
                break;
        }
		return $data;
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
		
		$ids = array_map(function($id) {
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

		$keywords = array_map(function($keyword) {
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
		$cart  = [];

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
		$flags    = [];

		$delivery = array_map(function($item) {
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
	public static function formatLocale(string $locale)
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
		$error = 'Unknown error';

		if ( ($data = self::decode($body)) ) {

			$error = $data['Errors'][0]['Message'] ?? '';
			$error = self::removeRegex('/\s(?<![A-Z0-9])[A-Z0-9]{20}(?![A-Z0-9])/', $error);
			$error = self::removeRegex('/\sPlease visit associate(s|) central at.*/', $error);
			$error = self::removeRegex('/\sPlease sign up for Product Advertising API at.*/', $error);
			$error = self::removeRegex('/\sPlease verify the number of requests made per second to.*/', $error);
			$error = self::removeRegex('/\sIf you are using an AWS SDK.*/', $error);
			$error = self::removeRegex('/\s\[.*\]/', $error);
			$error = self::replaceRegex('/ItemId .*? provided/', 'ItemId provided', $error);
			$error = rtrim($error, '.');

		} else {

			$error = self::stripTags($body);
			$error = self::stripSpace($error, ' ');
			$error = self::replaceRegex('/webservices.amazon.*/', 'Amazon server', $error);
			$error = self::removeRegex('/Not Found/', $error);
			$error = self::removeRegex('/[0-9]/', $error);
			$error = rtrim(trim($error), '.');
		}

		if ( self::$error ) {

			$search = [
				'The Access Key ID or security token included in the request is invalid',
				'Your access key is not mapped to Primary of approved associate store',
				'The request was denied due to request throttling',
				'The partner tag is not mapped to a valid associate store with your access key',
				'PartnerTag should be provided',
				'The value provided in the request for ItemIds is invalid',
				'The ItemId provided in the request is invalid',
			];
			$format = [
				'The API key or secret key is incorrect',
				'The API key is not associated with the specified store',
				'The request was refused due to Amazon API limitation',
				'The partner tag is invalid',
				'The partner tag is required',
				'The Id is not a valid ASIN or ISBN',
				'The Id is not a valid ASIN or ISBN',
			];
			$error = self::replaceString($search, $format, $error);

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
	 * Format order by.
	 *
	 * @access public
	 * @param mixed $order
	 * @return array
	 */
	public static function formatOrderBy($order) : array
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
		switch ($code) {
			case 'au':
				return 'com.au';
				break;
			case 'be':
				return 'com.be';
				break;
			case 'br':
				return 'com.br';
				break;
			case 'jp':
				return 'co.jp';
				break;
			case 'mx':
				return 'com.mx';
				break;
			case 'tr':
				return 'com.tr';
				break;
			case 'uk':
			case 'gb':
				return 'co.uk';
				break;
			case 'us':
				return 'com';
				break;
			default:
				return $code;
				break;
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
	public static function applyArgs($data, array $args)
	{
		$recursive = function ($item) use (&$recursive, $args) {
			if ( is_array($item) ) {
				foreach ($item as $key => $value) {
					$item[$key] = $recursive($value);
				}
				
			} elseif ( is_string($item) ) {
				$item = self::replaceString(
					array_keys($args),
					array_values($args),
					$item
				);
			}
			return $item;
		};
		return $recursive($data);
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

	    $sort = function($a, $b) use ($orderby) {

	        $a = (array)$a;
	        $b = (array)$b;

	        foreach ($orderby as $key => $dir) {

	            if ( !isset($a[$key]) || !isset($b[$key]) ) {
	                continue;
	            }

				if ($a[$key] == 0) return 1;
				if ($b[$key] == 0) return -1;

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
	 * Normalize item.
	 *
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private static function normalize(array $item) : array
	{
		return [
			'asin'         => self::sanitizeASIN($item),
			'ean'          => self::sanitizeEAN($item),
			'node'         => self::sanitizeNode($item),
			'root'         => self::sanitizeRoot($item),
			'rank'         => self::sanitizeRank($item),
			'title'        => self::sanitizeTitle($item),
			'category'     => self::sanitizeCategory($item),
			'url'          => self::sanitizeUrl($item),
			'image'        => self::sanitizeImage($item),
			'gallery'      => self::sanitizeGallery($item),
			'price'        => self::sanitizePrice($item),
			'discount'     => self::sanitizeDiscount($item),
			'discounted'   => self::sanitizeDiscounted($item),
			'percent'      => self::sanitizePercent($item),
			'currency'     => self::sanitizeCurrency($item),
			'availability' => self::sanitizeAvailability($item),
			'shipping'     => self::sanitizeShipping($item),
			'features'     => self::sanitizeFeatures($item)
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
		if ( ($id = $data['Id'] ?? false) ) {
			$node = [
				'id'       => $id,
				'name'     => $data['ContextFreeName'] ?? '',
				'root'     => $data['IsRoot'] ?? false,
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
			if ( ($id = $category['Id'] ?? false) ) {
				$categories[] = [
					'id'   => $id,
					'name' => $category['DisplayName'] ?? ''
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
		$data = $data['BrowseNodesResult']['BrowseNodes'][0] ?? [];
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
		return $data['ItemsResult']['Items'] ?? [];
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
			if ( isset($data['SearchResult']['SearchRefinements']) ) {
				$data = $data['SearchResult']['SearchRefinements'];
				$data = $data['SearchIndex']['Bins'] ?? [];
				return ['category' => $data];
			}
		}
		return $data['SearchResult']['Items'] ?? [];
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
		return $data['VariationsResult']['Items'] ?? [];
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
		if ( ($id = $item['Ancestor']['Id'] ?? false) ) {
			$ancestor = [
				'id'   => $id,
				'name' => $item['Ancestor']['ContextFreeName'] ?? '',
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
		$item = $item['Children'] ?? [];
		foreach ($item as $child) {
			if ( ($id = $child['Id']) ) {
				$children[] = [
					'id'   => $id,
					'name' => $child['ContextFreeName'] ?? '',
					'url'  => Provider::getNodeUrl($id)
				];
			}
		}
		return $children;
	}

	/**
	 * Sanitize item EAN.
	 *
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function sanitizeEAN(array $item) : string
	{
		return $item['ItemInfo']['ExternalIds']['EANs']['DisplayValues'][0] ?? '';
	}

	/**
	 * Sanitize item ASIN.
	 * 
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function sanitizeASIN(array $item) : string
	{
		return $item['ASIN'] ?? '';
	}

	/**
	 * Sanitize item title.
	 * 
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function sanitizeTitle(array $item) : string
	{
		$title = $item['ItemInfo']['Title']['DisplayValue'] ?? '';
		return self::formatString($title);
	}

	/**
	 * Sanitize item url.
	 * 
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function sanitizeUrl(array $item) : string
	{
		return $item['DetailPageURL'] ?? '';
	}

	/**
	 * Sanitize item image.
	 * 
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function sanitizeImage(array $item) : string
	{
		return $item['Images']['Primary']['Large']['URL'] ?? '';
	}

	/**
	 * Sanitize item gallery.
	 * 
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private static function sanitizeGallery(array $item) : array
	{
		$gallery = [];
		$variants = $item['Images']['Variants'] ?? [];
		foreach ($variants as $variant) {
			$gallery[] = $variant['Large']['URL'];
		}
		if ( self::$limit ) {
			$gallery = array_slice($gallery, 0, self::$limit);
		}
		return $gallery;
	}

	/**
	 * Sanitize item category.
	 *
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function sanitizeCategory(array $item) : string
	{
		$node = $item['BrowseNodeInfo']['BrowseNodes'][0] ?? [];
		$count = count($node);
		for ($i = 0; $i < $count; $i++) {
			if ( !isset($node['Ancestor']) ) break;
			$node = $node['Ancestor'];
		}
		return $node['ContextFreeName'] ?? '';
	}

	/**
	 * Sanitize item node Id.
	 *
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function sanitizeNode(array $item) : string
	{
		$node = $item['BrowseNodeInfo']['BrowseNodes'][0] ?? [];
		return $node['Id'] ?? '';
	}

	/**
	 * Sanitize item root Id.
	 *
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function sanitizeRoot(array $item) : string
	{
		$node = $item['BrowseNodeInfo']['BrowseNodes'][0] ?? [];
		$count = count($node);
		for ($i = 0; $i < $count; $i++) {
			if ( !isset($node['Ancestor']) ) break;
			$node = $node['Ancestor'];
		}
		return $node['Id'] ?? '';
	}

	/**
	 * Sanitize item rank.
	 *
	 * @access private
	 * @param array $item
	 * @return int
	 */
	private static function sanitizeRank(array $item) : int
	{
		$node = $item['BrowseNodeInfo']['BrowseNodes'][0] ?? [];
		return $node['SalesRank'] ?? 0;
	}

	/**
	 * Sanitize item price.
	 * 
	 * @access private
	 * @param array $item
	 * @return float
	 */
	private static function sanitizePrice(array $item) : float
	{
		$listing = $item['Offers']['Listings'][0] ?? [];
		return $listing['Price']['Amount'] ?? 0;
	}

	/**
	 * Sanitize item currency.
	 * 
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function sanitizeCurrency(array $item) : string
	{
		$listing = $item['Offers']['Listings'][0] ?? [];
		return $listing['Price']['Currency'] ?? '';
	}

	/**
	 * Sanitize item discount.
	 *
	 * @access private
	 * @param array $item
	 * @return float
	 */
	private static function sanitizeDiscount(array $item) : float
	{
		$listing = $item['Offers']['Listings'][0] ?? [];
		return $listing['Price']['Savings']['Amount'] ?? 0;
	}

	/**
	 * Sanitize item discount percent.
	 *
	 * @access private
	 * @param array $item
	 * @return float
	 */
	private static function sanitizePercent(array $item) : float
	{
		$listing = $item['Offers']['Listings'][0] ?? [];
		return $listing['Price']['Savings']['Percentage'] ?? 0;
	}

	/**
	 * Sanitize item discounted.
	 *
	 * @access private
	 * @param array $item
	 * @return float
	 */
	private static function sanitizeDiscounted(array $item) : float
	{
		return (self::sanitizePrice($item) + self::sanitizeDiscount($item));
	}

	/**
	 * Sanitize item shipping.
	 * 
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private static function sanitizeShipping(array $item) : array
	{
		$listing = $item['Offers']['Listings'][0] ?? [];
		return [
			'fulfilled' => $listing['DeliveryInfo']['IsAmazonFulfilled'] ?? false,
			'free'      => $listing['DeliveryInfo']['IsFreeShippingEligible'] ?? false,
			'prime'     => $listing['DeliveryInfo']['IsPrimeEligible'] ?? false
		];
	}

	/**
	 * Sanitize item availability.
	 * 
	 * @access private
	 * @param array $item
	 * @return string
	 */
	private static function sanitizeAvailability(array $item) : string
	{
		$listing = $item['Offers']['Listings'][0] ?? [];
		return $listing['Availability']['Message'] ?? '';
	}

	/**
	 * Sanitize item features.
	 * 
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private static function sanitizeFeatures(array $item) : array
	{
		$features = $item['ItemInfo']['Features']['DisplayValues'] ?? [];
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
	 * Format string.
	 *
	 * @access private
	 * @param string $string
	 * @return string
	 */
	private static function formatString(string $string) : string
	{
		if ( !self::$format ) {
			$string = self::replaceRegex('/[^\x{0000}-\x{FFFF}]/u', ' ', $string);
			$string = self::replaceString(['【', ], ' [', $string);
			$string = self::replaceString(['】', ], '] ', $string);
		}
		
		$string = trim($string);
		$string = self::replaceString("\r", "\n", $string);
		$string = self::replaceRegex(['/\n+/', '/[ \t]+/'], ["\n", ' '], $string);

		return $string;
	}

	/**
	 * Replace string.
	 *
	 * @access private
	 * @param mixed $search
	 * @param mixed $replace
	 * @param mixed $string
	 * @return string
	 */
	private static function replaceString($search, $replace, $string) : string
	{
		return (string)str_replace($search, $replace, $string);
	}

	/**
	 * Remove string.
	 *
	 * @access private
	 * @param string $search
	 * @param string $string
	 * @return string
	 */
	private static function removeString($search, $string) : string
	{
		return self::replaceString($search, '', $string);
	}

	/**
	 * Strip space.
	 *
	 * @access private
	 * @param string $string
	 * @param string $replace
	 * @return string
	 */
	private static function stripSpace(string $string, string $replace = '') : string
	{
		$string = trim($string);
		$string = self::replaceRegex('/\s+/', $replace, $string);
		return $string;
	}

	/**
	 * Strip tags.
	 *
	 * @access private
	 * @param string $string
	 * @return string
	 */
	private static function stripTags(string $string) : string
	{
		return strip_tags($string);
	}

	/**
	 * Replace string using regex.
	 *
	 * @access private
	 * @param mixed $regex
	 * @param mixed $replace
	 * @param mixed $subject
	 * @return string
	 */
	private static function replaceRegex($regex, $replace, $subject) : string
	{
		return (string)preg_replace($regex, $replace, $subject);
	}

	/**
	 * Remove string using regex.
	 *
	 * @access private
	 * @param mixed $regex
	 * @param mixed $subject
	 * @return string
	 */
	private static function removeRegex($regex, $subject) : string
	{
		return (string)self::replaceRegex($regex, '', $subject);
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
