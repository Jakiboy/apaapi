<?php
/**
 * @package Amazon Product Advertising API v5
 * @copyright Copyright (c) 2019 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\lib;

/**
 * Basic Paapi5 Operation Wrapper Class
 */
class Operation
{
	/**
	 * @access public
     *
	 * @var string $partnerType
	 * @var string $partnerTag
	 * @var string $keywords
	 * @var string $searchIndex
	 * @var array $resources
	 */
    public $partnerType = 'Associates';
    public $partnerTag;
    public $keywords;
    public $searchIndex = 'All';
    public $resources = [];

	/**
	 * @param string $type
	 * @return object
	 */
    public function setPartnerType($type)
    {
    	$this->partnerType = $type;
    	return $this;
    }

	/**
	 * @param string $tag
	 * @return object
	 */
    public function setPartnerTag($tag)
    {
    	$this->partnerTag = $tag;
    	return $this;
    }

	/**
	 * @param string $keywords
	 * @return object
	 */
    public function setKeywords($keywords)
    {
    	$this->keywords = $keywords;
    	return $this;
    }

	/**
	 * @param string $index
	 * @return object
	 */
    public function setSearchIndex($index)
    {
    	$this->searchIndex = $index;
    	return $this;
    }

	/**
	 * @param array $resources
	 * @return object
     * @todo Parse & Validate Ressources
     * @todo Set Default Ressources
	 */
    public function setResources($resources)
    {
        $this->resources = $resources;
    }
}
