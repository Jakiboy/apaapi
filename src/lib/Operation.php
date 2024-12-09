<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.3.x
 * @copyright : (c) 2019 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\lib;

use Apaapi\interfaces\OperationInterface;
use Apaapi\exceptions\OperationException;

/**
 * Apaapi operation wrapper class.
 */
class Operation implements OperationInterface
{
    /**
     * @access public
     * @var array $resources
     * @var string $partnerType
     * @var string $partnerTag
     * @var string $marketplace
     * @var array $languagesOfPreference
     */
    public $resources = [];
    public $partnerType = 'Associates';
    public $partnerTag = null;
    public $marketplace = null;
    public $languagesOfPreference = [];

    /**
     * @inheritdoc
     */
    public function setPartnerType(string $type) : object
    {
        $this->partnerType = $type;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setPartnerTag(string $tag) : object
    {
        $this->partnerTag = $tag;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setResources(array $resources, bool $throwable = true) : object
    {
        if ( $throwable ) {
            if ( ($ressource = $this->isValidResources($resources)) !== true ) {
                throw new OperationException(
                    OperationException::invalidRessources($ressource)
                );
            }
        }

        if ( !empty($resources) ) {
            $this->resources = $resources;
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setLanguages(array $languages) : object
    {
        $this->languagesOfPreference = $languages;
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setMarketplace(string $marketplace) : object
    {
        $this->marketplace = $marketplace;
        return $this;
    }

    /**
     * Check valid resources.
     *
     * @access private
     * @param array $resources
     * @return mixed
     */
    private function isValidResources(array $resources) : mixed
    {
        foreach ($resources as $resource) {
            if ( !in_array($resource, $this->resources) ) {
                return $resource;
            }
        }
        return true;
    }
}
