<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi
 * @version   : 1.0.8
 * @copyright : (c) 2019 - 2021 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib
 */

namespace Apaapi\includes;

use Apaapi\interfaces\ResponseTypeInterface;
use Apaapi\exceptions\ResponseTypeException;

/**
 * Basic Apaapi Response Helper
 */
class ResponseType implements ResponseTypeInterface
{
    /**
     * @access private
     * @var string $type
     */
	private $type;

    /**
     * @param string $type
     * @return void
     */
	public function __construct($type = 'object')
	{
		$this->type = strtolower($type);
	}

    /**
     * @access public
     * @param string $response
     * @return mixed
     */
	public function format($response)
	{
        try {
            if ( $this->isValidFormat() !== true ) {
                throw new ResponseTypeException((string)$this->type);
            }
        } catch (ResponseTypeException $e) {
            die($e->get(1));
        }

        switch ($this->type) {
        	case 'array':
        		return self::decode($response,true);
        		break;
        	case 'object':
        		return self::decode($response);
        		break;
        	case 'serialized':
        		return serialize(self::decode($response));
        		break;
        }
	}

    /**
     * @access public
     * @param object $response
     * @param string $operation
     * @return string
     */
    public function parse($response, $operation)
    {
        if ( $operation == 'GetItems' ) {
            $response = isset($response->ItemsResult->Items)
            ? $response->ItemsResult->Items : [];

        } elseif ( $operation == 'SearchItems' ) {
            $response = isset($response->SearchResult->Items)
            ? $response->SearchResult->Items : [];

        } elseif ( $operation == 'GetVariations' ) {
            $response = isset($response->VariationsResult->Items)
            ? $response->VariationsResult->Items : [];

        } elseif ( $operation == 'GetBrowseNodes' ) {
            $response = isset($response->BrowseNodesResult->BrowseNodes)
            ? $response->BrowseNodesResult->BrowseNodes : [];
        }

        return self::encode($response);
    }

    /**
     * @access public
     * @param string $json
     * @param bool $object
     * @return array|object
     */
    public static function decode($json, $object = false)
    {
    	return json_decode((string)$json,$object);
    }

    /**
     * @access public
     * @param array|object $json
     * @return string
     */
    public static function encode($json)
    {
        return json_encode($json);
    }

    /**
     * @access private
     * @param void
     * @return bool
     */
    private function isValidFormat()
    {
        if ( !in_array($this->type,['object','array','serialized']) ) {
            return false;
        }
        return true;
    }
}
