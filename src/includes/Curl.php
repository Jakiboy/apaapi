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

use Apaapi\interfaces\GatewayInterface;
use \CurlHandle;

/**
 * Apaapi cURL manipulation.
 */
final class Curl implements GatewayInterface
{
    /**
     * @access public
     * @var int cURL option
     */
    public const URL            = CURLOPT_URL;
    public const HEADER         = CURLOPT_HEADER;
    public const NOBODY         = CURLOPT_NOBODY;
    public const HTTPHEADER     = CURLOPT_HTTPHEADER;
    public const HEADERFUNC     = CURLOPT_HEADERFUNCTION;
    public const WRITEFUNC      = CURLOPT_WRITEFUNCTION;
    public const TIMEOUT        = CURLOPT_TIMEOUT;
    public const CONNECTTIMEOUT = CURLOPT_CONNECTTIMEOUT;
    public const POST           = CURLOPT_POST;
    public const POSTFIELDS     = CURLOPT_POSTFIELDS;
    public const CUSTOMREQUEST  = CURLOPT_CUSTOMREQUEST;
    public const VERIFYHOST     = CURLOPT_SSL_VERIFYHOST;
    public const VERIFYPEER     = CURLOPT_SSL_VERIFYPEER;
    public const RETURNTRANSFER = CURLOPT_RETURNTRANSFER;
    public const FOLLOWLOCATION = CURLOPT_FOLLOWLOCATION;
    public const MAXREDIRS      = CURLOPT_MAXREDIRS;
    public const ENCODING       = CURLOPT_ENCODING;
    public const USERAGENT      = CURLOPT_USERAGENT;
    // public const HTTP_VERSION   = CURLOPT_HTTP_VERSION;

    /**
     * @access private
     * @var array $responseStatus
     * @var array $responseHeader
     * @var string $responseBody
     */
    private static $responseStatus = [];
    private static $responseHeader = [];
    private static $responseBody = '';

    /**
     * Init cURL handle.
     *
     * @access public
     * @param string $url
     * @return CurlHandle|false
     */
    public static function init(?string $url = null) : CurlHandle|false
    {
        return curl_init($url);
    }

    /**
     * Execute cURL handle.
     *
     * @access public
     * @param CurlHandle $handle
     * @return string|bool
     */
    public static function exec(CurlHandle $handle) : string|bool
    {
        return curl_exec($handle);
    }

    /**
     * Execute cURL handle (Alias with response and close).
     *
     * @access public
     * @param CurlHandle $handle
     * @return string
     */
    public static function execute(CurlHandle $handle) : string
    {
        self::return($handle); // Force return
        $response = (string)self::exec($handle);
        self::close($handle);
        return $response;
    }

    /**
     * Set cURL single option.
     *
     * @access public
     * @param CurlHandle $handle
     * @param int $option
     * @param mixed $value
     * @return bool
     */
    public static function setOpt(CurlHandle $handle, int $option, mixed $value) : bool
    {
        return curl_setopt($handle, $option, $value);
    }

    /**
     * Set cURL URL.
     *
     * @access public
     * @param CurlHandle $handle
     * @param string $url
     * @return bool
     */
    public static function setUrl(CurlHandle $handle, string $url) : bool
    {
        return self::setOpt($handle, self::URL, $url);
    }

    /**
     * Set cURL timeoout.
     *
     * @access public
     * @param CurlHandle $handle
     * @param int $timeoout
     * @return bool
     */
    public static function setTimeout(CurlHandle $handle, int $timeoout) : bool
    {
        return self::setOpt($handle, self::TIMEOUT, $timeoout);
    }

    /**
     * Set cURL max redirections.
     *
     * @access public
     * @param CurlHandle $handle
     * @param int $max
     * @return bool
     */
    public static function setRedirect(CurlHandle $handle, int $max) : bool
    {
        return self::setOpt($handle, self::MAXREDIRS, $max);
    }

    /**
     * Set cURL encoding.
     *
     * @access public
     * @param CurlHandle $handle
     * @param string $encoding
     * @return bool
     */
    public static function setEncoding(CurlHandle $handle, string $encoding) : bool
    {
        return self::setOpt($handle, self::ENCODING, $encoding);
    }

    /**
     * Set User-Agent.
     *
     * @access public
     * @param CurlHandle $handle
     * @param string $ua
     * @return bool
     */
    public static function setUserAgent(CurlHandle $handle, string $ua) : bool
    {
        return self::setOpt($handle, self::USERAGENT, $ua);
    }

    /**
     * Set cURL HTTP method.
     *
     * @access public
     * @param CurlHandle $handle
     * @param string $method
     * @return bool
     */
    public static function setMethod(CurlHandle $handle, string $method) : bool
    {
        return self::setOpt($handle, self::CUSTOMREQUEST, $method);
    }

    /**
     * Set cURL POST method.
     *
     * @access public
     * @param CurlHandle $handle
     * @return bool
     */
    public static function setPost(CurlHandle $handle) : bool
    {
        return self::setOpt($handle, self::POST, true);
    }

    /**
     * Set cURL POST data.
     *
     * @access public
     * @param CurlHandle $handle
     * @param mixed $data
     * @return bool
     */
    public static function setPostData(CurlHandle $handle, mixed $data) : bool
    {
        return self::setOpt($handle, self::POSTFIELDS, $data);
    }

    /**
     * Set cURL body return.
     *
     * @access public
     * @param CurlHandle $handle
     * @param bool $status
     * @return bool
     */
    public static function return(CurlHandle $handle, bool $status = true) : bool
    {
        return self::setOpt($handle, self::RETURNTRANSFER, $status);
    }

    /**
     * Set cURL redirection follow.
     *
     * @access public
     * @param CurlHandle $handle
     * @param bool $status
     * @return bool
     */
    public static function follow(CurlHandle $handle, bool $status = true) : bool
    {
        return self::setOpt($handle, self::FOLLOWLOCATION, $status);
    }

    /**
     * Verify cURL host.
     *
     * @access public
     * @param CurlHandle $handle
     * @param bool $status
     * @return bool
     */
    public static function verifyHost(CurlHandle $handle, bool $status = true) : bool
    {
        $status = $status == true ? 2 : false;
        return self::setOpt($handle, self::VERIFYHOST, $status);
    }

    /**
     * Verify cURL peer.
     *
     * @access public
     * @param CurlHandle $handle
     * @param bool $status
     * @return bool
     */
    public static function verifyPeer(CurlHandle $handle, bool $status = true) : bool
    {
        return self::setOpt($handle, self::VERIFYPEER, $status);
    }

    /**
     * Set cURL header in reponse.
     *
     * @access public
     * @param CurlHandle $handle
     * @param bool $status
     * @return bool
     */
    public static function headerIn(CurlHandle $handle, bool $status = true) : bool
    {
        return self::setOpt($handle, self::HEADER, $status);
    }

    /**
     * Set cURL request header.
     *
     * @access public
     * @param CurlHandle $handle
     * @param array $header
     * @return bool
     */
    public static function setHeader(CurlHandle $handle, array $header) : bool
    {
        return self::setOpt($handle, self::HTTPHEADER, $header);
    }

    /**
     * Set cURL response header callback.
     * Force including header response.
     *
     * @access public
     * @param CurlHandle $handle
     * @param mixed $callback
     * @return bool
     */
    public static function setHeaderCallback(CurlHandle $handle, $callback = null) : bool
    {
        if ( !$callback ) {
            $callback = [self::class, 'parseHeader'];
        }
        return self::setOpt($handle, self::HEADERFUNC, $callback);
    }

    /**
     * Set cURL response body callback.
     *
     * @access public
     * @param CurlHandle $handle
     * @param mixed $callback
     * @return bool
     */
    public static function setBodyCallback(CurlHandle $handle, $callback = null) : bool
    {
        if ( !$callback ) {
            $callback = [self::class, 'parseBody'];
        }
        return self::setOpt($handle, self::WRITEFUNC, $callback);
    }

    /**
     * Set cURL array of options.
     *
     * @access public
     * @param CurlHandle $handle
     * @param array $options
     * @return bool
     */
    public static function setOptions(CurlHandle $handle, array $options) : bool
    {
        return curl_setopt_array($handle, $options);
    }

    /**
     * Get cURL handle last info.
     *
     * @access public
     * @param CurlHandle $handle
     * @param ?int $option
     * @return mixed
     */
    public static function getInfo(CurlHandle $handle, ?int $option = null) : mixed
    {
        return curl_getinfo($handle, $option);
    }

    /**
     * Get cURL error code.
     *
     * @access public
     * @param CurlHandle $handle
     * @return int
     */
    public static function getErrorCode(CurlHandle $handle) : int
    {
        return curl_errno($handle);
    }

    /**
     * Get cURL error message.
     *
     * @access public
     * @param CurlHandle $handle
     * @return string
     */
    public static function getErrorMessage(CurlHandle $handle) : string
    {
        return curl_error($handle);
    }

    /**
     * Get cURL error (Normalized).
     *
     * @access public
     * @param CurlHandle $handle
     * @return array
     */
    public static function getError(CurlHandle $handle) : array
    {
        return [
            'code'    => self::getErrorCode($handle),
            'message' => self::getErrorMessage($handle)
        ];
    }

    /**
     * Close cURL handle.
     *
     * @access public
     * @param CurlHandle $handle
     * @return void
     */
    public static function close(CurlHandle $handle) : void
    {
        curl_close($handle);
    }

    /**
     * Advanced cURL HTTP request.
     *
     * @inheritdoc
     */
    public static function request(string $url, array $params = []) : array
    {
        // Rest cUrl
        self::reset();

        // Extract params
        $params = Client::getParams($params);
        extract($params);

        $scraper = (bool)($params['scraper'] ?? false);
        $config = $scraper ? ScraperConfig::all() : [];
        $maxRetries = $scraper
            ? max(1, (int)($params['retries'] ?? ($config['maxRetries'] ?? 3)))
            : 1;
        $retryDelay = max(0, (int)($params['retryDelay'] ?? ($config['retryDelay'] ?? 2)));
        $minDelay = (float)($config['minDelay'] ?? 0.0);
        $maxDelay = (float)($config['maxDelay'] ?? 0.0);

        $result = [
            'error'  => true,
            'status' => ['code' => 500, 'message' => Status::getMessage(500)],
            'header' => [],
            'body'   => Status::getMessage(500)
        ];

        $cookieJar = null;
        $preflightUrl = null;

        if ( $scraper ) {
            $cookieJar = tempnam(sys_get_temp_dir(), 'apaapi-scraper-') ?: null;
            $preflightUrl = self::buildScraperWarmupUrl($url);
        }

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {

            self::reset();

            // Set body
            $requestUrl = $url;
            if ( $body && $method !== Client::POST ) {
                if ( is_array($body) ) {
                    $requestUrl = Client::getQuery($body, $requestUrl);
                }
            }

            // Init cURL
            $handle = self::init($requestUrl);

            if ( $scraper && $cookieJar && $attempt === 1 && !empty($preflightUrl) ) {
                $warmup = self::init($preflightUrl);
                if ( $warmup ) {
                    self::setHeader($warmup, $header);
                    self::setTimeout($warmup, max(10, (int)$timeout));

                    if ( $encoding !== null ) {
                        self::setEncoding($warmup, $encoding);
                    }

                    if ( $ua ) {
                        self::setUserAgent($warmup, $ua);
                    }

                    self::setOpt($warmup, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                    self::setOpt($warmup, CURLOPT_AUTOREFERER, true);
                    self::setOpt($warmup, CURLOPT_COOKIEJAR, $cookieJar);
                    self::setOpt($warmup, CURLOPT_COOKIEFILE, $cookieJar);

                    self::follow($warmup);
                    self::setRedirect($warmup, 3);
                    self::return($warmup);
                    self::exec($warmup);
                    self::close($warmup);

                    usleep(mt_rand(1200000, 3000000));
                }
            }

            $requestHeader = $header;
            if ( $scraper && !empty($preflightUrl) ) {
                $requestHeader = self::upsertHeader($requestHeader, 'Referer', (string)$preflightUrl);
                $requestHeader = self::upsertHeader($requestHeader, 'Sec-Fetch-Site', 'same-origin');
            }

            // Set options
            self::setHeader($handle, $requestHeader);
            self::setTimeout($handle, $timeout);

            if ( $scraper && $cookieJar ) {
                self::setOpt($handle, CURLOPT_COOKIEJAR, $cookieJar);
                self::setOpt($handle, CURLOPT_COOKIEFILE, $cookieJar);
            }

            if ( $encoding !== null ) {
                self::setEncoding($handle, $encoding);
            }

            if ( $ua ) {
                self::setUserAgent($handle, $ua);
            }

            if ( $ssl === false ) {
                self::verifyHost($handle, false);
                self::verifyPeer($handle, false);
            }

            // Force HTTP/1.1 to avoid HTTP/2 stream protocol errors
            self::setOpt($handle, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

            if ( $scraper ) {
                self::setOpt($handle, CURLOPT_AUTOREFERER, true);
                if ( defined('CURLOPT_TCP_KEEPALIVE') ) {
                    self::setOpt($handle, CURLOPT_TCP_KEEPALIVE, 1);
                }
            }

            if ( $method == Client::POST ) {
                self::setPost($handle);
                self::setPostData($handle, $body);

            } else {
                self::setMethod($handle, $method);
            }

            // Allow redirection follow
            if ( $follow === true ) {
                self::follow($handle);
                self::setRedirect($handle, $redirect);
            }

            // Allow body return
            if ( $return === true ) {
                self::return($handle);

            } else {
                self::setBodyCallback($handle);
            }

            // Include header response
            if ( $headerIn === true ) {
                self::headerIn($handle);

            } else {
                self::setHeaderCallback($handle);
            }

            // Get response
            $error = false;
            $response = self::exec($handle);

            if ( $response === false ) {
                $responseHeader = [];
                $status = self::getError($handle);
                $responseBody = Status::getMessage(500);
                $error = true;

            } else {
                $responseHeader = self::getResponseHeader();
                $status = self::getResponseStatus();
                $responseBody = $return ? (string)$response : self::getResponseBody();

                if ( empty($status) ) {
                    $httpCode = (int)self::getInfo($handle, CURLINFO_HTTP_CODE);
                    $status = [
                        'code'    => $httpCode,
                        'message' => Status::getMessage($httpCode ?: 200)
                    ];
                }
            }

            // Close handle
            self::close($handle);

            $result = [
                'error'  => $error,
                'status' => $status,
                'header' => $responseHeader,
                'body'   => $responseBody
            ];

            if ( !$scraper ) {
                break;
            }

            if ( !$error && !self::isBlockedScraperResponse((string)$responseBody) ) {
                break;
            }

            if ( $attempt < $maxRetries ) {
                $backoff = $retryDelay > 0 ? ($retryDelay * $attempt) : 0;

                if ( $maxDelay > 0 && $maxDelay >= $minDelay ) {
                    $jitter = mt_rand((int)($minDelay * 1000000), (int)($maxDelay * 1000000)) / 1000000;
                    $backoff += $jitter;
                }

                if ( $backoff > 0 ) {
                    usleep((int)($backoff * 1000000));
                }
            }
        }

        if ( $cookieJar && file_exists($cookieJar) ) {
            @unlink($cookieJar);
        }

        // Return response data
        return $result;
    }

    /**
     * Get response header.
     *
     * @access public
     * @return array
     */
    public static function getResponseHeader() : array
    {
        return self::$responseHeader;
    }

    /**
     * Get response status.
     *
     * @access public
     * @return array
     */
    public static function getResponseStatus() : array
    {
        return self::$responseStatus;
    }

    /**
     * Get response status code.
     *
     * @access public
     * @return int
     */
    public static function getStatusCode() : int
    {
        $code = self::$responseStatus['code'] ?? -1;
        return (int)$code;
    }

    /**
     * Get response status message.
     *
     * @access public
     * @return string
     */
    public static function getStatusMessage() : string
    {
        $code = self::$responseStatus['message'] ?? '';
        return (string)$code;
    }

    /**
     * Get response body.
     *
     * @access public
     * @return string
     */
    public static function getResponseBody() : string
    {
        return self::$responseBody;
    }

    /**
     * Parse response header.
     *
     * @access public
     * @param CurlHandle $handle
     * @param string $header
     * @return int
     */
    public static function parseHeader(CurlHandle $handle, string $header) : int
    {
        // Parse status
        $regex = Client::getPattern('status');
        if ( (bool)preg_match($regex, $header, $matches) ) {
            $code = $matches['code'] ?? -1;
            $code = (int)$code;
            $message = $matches['message'] ?? '';
            if ( empty($message) ) {
                $message = Status::getMessage($code);
            }
            self::$responseStatus = ['code' => $code, 'message' => $message];
        }

        // Get header attributes (multi-line)
        $regex = Client::getPattern('attribute');
        if ( (bool)preg_match($regex, $header, $matches) ) {
            if ( isset($matches['name']) && isset($matches['value']) ) {
                $name = $matches['name'];
                $value = trim($matches['value']);
                if ( isset(self::$responseHeader[$name]) ) {
                    self::$responseHeader[$name] .= "\n{$value}";

                } else {
                    self::$responseHeader[$name] = $value;
                }
            }
        }

        return strlen($header);
    }

    /**
     * Parse response body.
     *
     * @access public
     * @param CurlHandle $handle
     * @param string $body
     * @return int
     */
    public static function parseBody(CurlHandle $handle, string $body) : int
    {
        self::$responseBody .= $body;
        return strlen($body);
    }

    /**
     * Reset cURL.
     *
     * @access private
     * @return void
     */
    private static function reset() : void
    {
        self::$responseBody = '';
        self::$responseHeader = [];
        self::$responseStatus = [];
    }

    /**
     * Detect known anti-bot responses in credential-less scraping.
     *
     * @access private
     * @param string $html
     * @return bool
     */
    private static function isBlockedScraperResponse(string $html) : bool
    {
        if ( strlen($html) < 1000 ) {
            return true;
        }

        $patterns = [
            'validatecaptcha',
            'robot check',
            'enter the characters you see',
            'api-services-support@amazon',
            'automated access to amazon data',
            'captchacharacters',
            'unusual traffic',
            'automated requests',
            'one more step'
        ];

        $source = strtolower($html);
        foreach ($patterns as $pattern) {
            if ( strpos($source, $pattern) !== false ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Build a warm-up URL on the same host to establish a browser-like session.
     *
     * @access private
     * @param string $url
     * @return string
     */
    private static function buildScraperWarmupUrl(string $url) : string
    {
        $parts = parse_url($url);
        $scheme = (string)($parts['scheme'] ?? 'https');
        $host = (string)($parts['host'] ?? 'www.amazon.com');
        $base = "{$scheme}://{$host}";

        $paths = [
            '/',
            '/gp/bestsellers',
            '/gp/new-releases',
            '/deals'
        ];

        return $base . $paths[array_rand($paths)];
    }

    /**
     * Insert or replace an HTTP header line by name.
     *
     * @access private
     * @param array $headers
     * @param string $name
     * @param string $value
     * @return array
     */
    private static function upsertHeader(array $headers, string $name, string $value) : array
    {
        $needle = strtolower($name) . ':';
        $line = "{$name}: {$value}";

        foreach ($headers as $index => $header) {
            if ( strpos(strtolower((string)$header), $needle) === 0 ) {
                $headers[$index] = $line;
                return $headers;
            }
        }

        $headers[] = $line;
        return $headers;
    }
}
