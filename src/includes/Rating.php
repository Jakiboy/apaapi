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

final class Rating
{
	/**
	 * @access private
	 * @var string $keyword
	 * @var string $locale
	 * @var string $tag
	 */
	private $keyword;
	private $locale;
	private $tag;

	private const ACTION     = '/dp/';
	private const VALUExPATH = "//div[contains(@class,'AverageCustomerReviews')]";
	private const COUNTxPATH = "//div[contains(@class,'averageStarRatingNumerical')]";

	/**
	 * Init rating.
	 *
	 * @param string $keyword
	 * @param string $locale
	 * @param string $tag
	 */
	public function __construct(string $keyword, string $locale = 'com', ?string $tag = null)
	{
		$this->keyword = Normalizer::formatId($keyword);
		$this->locale = Normalizer::formatTLD($locale);
		$this->tag = $tag;
	}

	/**
	 * Get rating data.
	 *
	 * @access public
	 * @return array
	 */
	public function get() : array
	{
		$default = $this->getDefault();

		if ( !Keyword::isASIN($this->keyword) && !Keyword::isISBN($this->keyword) ) {
			return $default;
		}

		$key = "rating-{$this->locale}-{$this->keyword}";
		$key = Cache::generateKey($key);

		if ( !($rating = Cache::get($key)) ) {

			$host = Provider::HOST;
			$url = str_replace('{locale}', $this->locale, $host);
			$url .= self::ACTION;
			$url .= $this->keyword;

			$client = new Client($url, [
				'header' => Provider::generateHeader()
			]);

			$client->setMethod('GET')
				->setRedirect(1)
				->setEncoding('')
				->setTimeout(0);

			$response = $client->getResponse();
			$client->close();

			if ( $client->getCode() == 200 ) {
				$rating = $this->extract($response);
				$rating['url'] = "{$url}?tag={$this->tag}&linkCode=ll2";
				$rating = array_merge($default, $rating);
				Cache::set($key, $rating);
			}

		}

		return $rating ?: $default;
	}

	/**
	 * Extract rating data from HTML.
	 *
	 * @access private
	 * @param string $html
	 * @return array
	 */
	private function extract(string $html) : array
	{
		$rating = [];

		if ( class_exists('DomDocument') ) {

			// Ignore XML errors
			libxml_use_internal_errors(true);

			// Init DOM document
			$dom = new DOMDocument();
			$dom->loadHTML($html);
			$xPath = new DOMXPath($dom);

			// Extract rating value
			$nodes = $xPath->query(self::VALUExPATH);
			if ( $nodes instanceof DOMNodeList && ($nodes->length > 0) ) {
				$node = $nodes->item(0);
				$data = explode(' ', (string)$node->nodeValue);
				$value = $data[0];
				$value = str_replace(',', '.', $value);
				$rating['value'] = (float)$value;
			}

			// Extract rating count
			$nodes = $xPath->query(self::COUNTxPATH);
			if ( $nodes instanceof DOMNodeList && ($nodes->length > 0) ) {
				$node = $nodes->item(0);
				$count = (string)$node->nodeValue;
				$count = preg_replace('/\D/', '', $count);
				$rating['count'] = (int)$count;
			}

			// Clear XML errors
			libxml_clear_errors();

		}

		return $rating;
	}

	/**
	 * Get default rating.
	 *
	 * @access private
	 * @return array
	 */
	private function getDefault() : array
	{
		return [
			'value' => null,
			'count' => null,
			'url'   => null
		];
	}
}
