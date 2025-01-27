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

/**
 * Apaapi built-in geotargeting helper.
 * For high-performance applications use MaxMind GeoIP2 database.
 * @see https://dev.maxmind.com/geoip
 */
final class Geotargeting
{
	/**
	 * @access private
	 * @var string $visitorKey, Cache key
	 * @var array $exception, Exception IP addresses
	 * @var bool $redirectNotFound, Redirect 404 result
	 */
	private static $visitorKey = '--visitor-id';
	private static $exception = ['::1', '127.0.0.1', '0.0.0.0'];
	private static $redirectNotFound = false;

	/**
	 * @access private
	 * @var bool $isDetected, Geotargeting status
	 * @var array $api, Geotargeting APIs
	 * @var array $target, Geotargeting partner tags
	 * @var mixed $code, External country code
	 * @var string $tag, Current partner tag
	 * @var string $locale, Current locale
	 * @var array $redirect, Redirection target
	 */
	private $isDetected = false;
	private $api = [];
	private $target = [];
	private $code = false;
	private $tag;
	private $locale;
	private $redirect = [];

	/**
	 * Set redirection args.
	 *
	 * @param array $args
	 */
	public function __construct(array $args)
	{
		$this->api = $args['api'] ?? [];
		$this->target = $args['target'] ?? [];
		$this->tag = $args['tag'] ?? '';
		$this->locale = $args['locale'] ?? '';
		$this->code = $args['code'] ?? '';

		$this->api = array_merge([
			'ip'  => ['address' => null, 'param' => null],
			'geo' => ['address' => null, 'param' => null]
		], $this->api);

		$this->detect();
	}

	/**
	 * Get redirected response data.
	 *
	 * @access private
	 * @param array $data
	 * @return array
	 */
	public function get(array $data) : array
	{
		if ( $this->isDetected ) {
			$data = array_map(function ($item) {
				return self::redirect($item);
			}, $data);
		}
		return $data;
	}

	/**
	 * Set visitor key.
	 *
	 * @access public
	 * @param string $visitorKey
	 * @return void
	 */
	public static function setVisitorKey(string $visitorKey) : void
	{
		self::$visitorKey = $visitorKey;
	}

	/**
	 * Set exception IP addresses.
	 *
	 * @access public
	 * @param array $exception
	 * @return void
	 */
	public static function setException(array $exception) : void
	{
		self::$exception = $exception;
	}

	/**
	 * Redirect 404 result.
	 *
	 * @access public
	 * @return void
	 */
	public static function redirectNotFound() : void
	{
		self::$redirectNotFound = true;
	}

	/**
	 * Redirect item.
	 *
	 * @access private
	 * @param array $item
	 * @return array
	 */
	private function redirect(array $item) : array
	{
		if ( isset($item['url']) ) {

			$host = Provider::HOST;
			$from = str_replace('{locale}', $this->locale, $host);
			$to = str_replace('{locale}', $this->redirect['tld'], $host);

			$item['url'] = str_replace([
				$from,
				"?tag={$this->tag}"
			], [
				$to,
				"?tag={$this->redirect['tag']}"
			], $item['url']);

			if ( self::$redirectNotFound ) {

				$client = new Client($item['url'], [
					'header' => Provider::generateHeader(
						$this->redirect['tld']
					)
				]);

				$client->setMethod('GET')
					->setRedirect(2)
					->setTimeout(0);

				$client->getResponse();
				$client->close();

				if ( $client->getCode() == 404 ) {

					$title = $item['title'] ?? '';
					$keyword = substr($title, 0, 30);
					$keyword = preg_replace('/[^a-zA-Z0-9\s]/', '', $keyword);
					$keyword = urlencode($keyword);

					$url = "{$to}/s?k={$keyword}&tag={$this->redirect['tag']}";
					$url = "{$url}&linkCode=ll2";
					$item['url'] = $url;

				}
			}
		}

		return $item;
	}

	/**
	 * Detect redirection target.
	 *
	 * @access private
	 * @return void
	 */
	private function detect() : void
	{
		$code = $this->getCountryCode();
		if ( $this->target ) {
			if ( isset($this->target[$code]) && !empty($this->target[$code]) ) {
				$this->isDetected = true;
				$this->redirect = [
					'tld' => Normalizer::formatTLD($code),
					'tag' => $this->target[$code]
				];
			}

		} else {
			$this->isDetected = false;
		}
	}

	/**
	 * Get current country code.
	 *
	 * @access private
	 * @return string
	 */
	private function getCountryCode() : string
	{
		if ( $this->code ) {
			return $this->code;
		}

		// Get visitor IP
		if ( !($ip = $this->getIp()) ) {
			$ip = '0.0.0.0';
		}

		// Get IP from external API
		if ( in_array($ip, self::$exception) ) {
			$ip = $this->getApiAddressIp();
		}

		// Get code from external API
		return $this->getApiCountryCode($ip);
	}

	/**
	 * Get current country code using API.
	 *
	 * @access private
	 * @param string $ip
	 * @return string
	 */
	private function getApiCountryCode(string $ip = '0.0.0.0') : string
	{
		$code = 'us';
		$address = $this->api['geo']['address'] ?? '';
		$param = $this->api['geo']['param'] ?? '';

		if ( !$address || !$param ) {
			return $code;
		}

		$key = Cache::generateKey("geo-{$this->getVisitorId()}");
		if ( !($code = Cache::get($key)) ) {

			$address = str_replace('{ip}', $ip, $address);
			$client = new Client($address);
			$client->setMethod('GET')->setTimeout(5);
			$response = $client->getResponse();
			$client->close();

			if ( $client->getCode() == 200 ) {
				$response = Normalizer::decode($response);
				$code = $response[$param] ?? '';
			}

			Cache::set($key, $code);

		}

		$code = (string)$code;
		return Normalizer::formatCountryCode($code);
	}

	/**
	 * Get current IP address using API.
	 *
	 * @access private
	 * @return string
	 */
	private function getApiAddressIp() : string
	{
		$ip = '0.0.0.0';
		$address = $this->api['ip']['address'] ?? '';
		$param = $this->api['ip']['param'] ?? '';

		if ( !$address || !$param ) {
			return $ip;
		}

		$key = Cache::generateKey("ip-{$this->getVisitorId()}");
		if ( !($ip = Cache::get($key)) ) {

			$client = new Client($address);
			$client->setMethod('GET')->setTimeout(5);
			$response = $client->getResponse();
			$client->close();

			if ( $client->getCode() == 200 ) {
				$response = Normalizer::decode($response);
				$ip = $response[$param] ?? '';
			}

			Cache::set($key, $ip);

		}

		return (string)$ip;
	}

	/**
	 * Get remote IP address.
	 *
	 * @access private
	 * @return string
	 */
	private function getIp() : string
	{
		if ( isset($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
			return $this->parseIp($_SERVER['HTTP_X_FORWARDED_FOR']);

		} elseif ( isset($_SERVER['HTTP_X_REAL_IP']) ) {
			return $this->parseIp($_SERVER['HTTP_X_REAL_IP']);

		} elseif ( isset($_SERVER['HTTP_CF_CONNECTING_IP']) ) {
			return $this->parseIp($_SERVER['HTTP_CF_CONNECTING_IP']);
		}

		$ip = $_SERVER['REMOTE_ADDR'] ?? '';
		return $this->parseIp($ip);
	}

	/**
	 * Parse remote IP address.
	 *
	 * @access private
	 * @param string $ip
	 * @return string
	 */
	private function parseIp(string $ip) : string
	{
		$ip = stripslashes($ip);
		$ip = preg_split('/,/', $ip, -1, 0);
		$ip = (string)current($ip);
		return trim($ip);
	}

	/**
	 * Get visitor ID.
	 *
	 * @access private
	 * @return string
	 */
	private function getVisitorId() : string
	{
		if ( isset($_COOKIE[self::$visitorKey]) ) {
			return $_COOKIE[self::$visitorKey];
		}

		$visitorId = uniqid();
		setcookie(self::$visitorKey, $visitorId, time() + (86400 * 30), '/');

		return $visitorId;
	}
}
