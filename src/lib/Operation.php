<?php
/**
 * @package Amazon Product Advertising API
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\lib;

/**
 * Basic Apaapi All Operation Wrapper Class
 */
class Operation
{
	/**
	 * @access public
     *
	 * @var string $partnerType
	 * @var array $resources
	 * @var null|string $partnerTag
     * @var null|array $marketplace
     * @var null|array $languagesOfPreference
	 */
    public $partnerType = 'Associates';
    public $resources = [];
    public $partnerTag = null;
    public $marketplace = null;
    public $languagesOfPreference = [];

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
	 * @param array $resources
	 * @return object
     * @todo Parse & Validate Ressources
     * @todo Set Default Ressources
	 */
    public function setResources($resources)
    {
        $this->resources = $resources;
        return $this;
    }

    /**
     * @param array $languagesOfPreference
     * @return object
     */
    public function setLanguages($languagesOfPreference)
    {
        $this->languagesOfPreference = $languagesOfPreference;
        return $this;
    }

    /**
     * @param string $marketplace
     * @return object
     */
    public function setMarketplace($marketplace)
    {
        $this->marketplace = $marketplace;
        return $this;
    }
}
