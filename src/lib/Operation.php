<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.3
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\lib;

use Apaapi\exceptions\OperationException;
use Apaapi\interfaces\OperationInterface;

/**
 * Basic Apaapi All Operation Wrapper Class.
 */
class Operation implements OperationInterface
{
	/**
     * @access public
     * @var string $partnerType
     * @var array $resources
     * @var string $partnerTag
     * @var array $marketplace
     * @var array $languagesOfPreference
     */
    public $partnerType = 'Associates';
    public $resources = [];
    public $partnerTag = null;
    public $marketplace = null;
    public $languagesOfPreference = [];

	/**
     * @access public
	 * @param string $type
	 * @return object
	 */
    public function setPartnerType($type)
    {
    	$this->partnerType = $type;
    	return $this;
    }

    /**
     * @access public
     * @param string $tag
     * @return object
     */
    public function setPartnerTag($tag)
    {
        $this->partnerTag = $tag;
        return $this;
    }

	/**
     * @access public
	 * @param array $resources
	 * @return object
     * @throws OperationException
	 */
    public function setResources($resources = [])
    {
        if ( ($ressource = $this->isValidResources($resources)) !== true ) {
            throw new OperationException(
                OperationException::invalidOperationRessource($ressource)
            );
        }
        $this->resources = !empty($resources) 
        ? $resources : $this->resources;
        return $this;
    }

    /**
     * @access public
     * @param array $languagesOfPreference
     * @return object
     */
    public function setLanguages($languagesOfPreference)
    {
        $this->languagesOfPreference = (array)$languagesOfPreference;
        return $this;
    }

    /**
     * @access public
     * @param string $marketplace
     * @return object
     */
    public function setMarketplace($marketplace)
    {
        $this->marketplace = $marketplace;
        return $this;
    }

    /**
     * @access private
     * @param array $resources
     * @return mixed
     */
    private function isValidResources($resources = [])
    {
        foreach ((array)$resources as $resource) {
            if ( !in_array($resource,$this->resources) ) {
                return $resource;
            }
        }
        return true;
    }
}
