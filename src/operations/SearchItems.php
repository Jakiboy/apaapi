<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.5
 * @copyright : (c) 2019 - 2022 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

namespace Apaapi\operations;

use Apaapi\lib\ItemOperation;
use Apaapi\resources\BrowseNodeInfo;
use Apaapi\resources\Images;
use Apaapi\resources\ItemInfo;
use Apaapi\resources\Offers;
use Apaapi\resources\SearchRefinements;
use Apaapi\resources\ParentASIN;
use Apaapi\includes\ResourceParser;

/**
 * Basic Apaapi SearchItems Operation.
 * @see https://webservices.amazon.com/paapi5/documentation/search-items.html
 */
final class SearchItems extends ItemOperation
{
    /**
     * @access public
     * @var string $searchIndex
     * @var string $availability
     * @var int $itemCount
     * @var int $itemPage
     * @var array $deliveryFlags
     * @var string $keywords
     * @var string $browseNodeId
     * @var string $actor
     * @var string $artist
     * @var string $author
     * @var string $brand
     * @var int $maxPrice
     * @var int $minPrice
     * @var int $minReviewsRating
     * @var int $minSavingPercent
     * @var string $sortBy
     * @var string $title
     */
    public $searchIndex  = 'All';
    public $availability = 'Available';
    public $itemCount = 10;
    public $itemPage = 1;
    public $deliveryFlags = null;
    public $keywords = null;
    public $browseNodeId = null;
    public $actor = null;
    public $artist = null;
    public $author = null;
    public $brand = null;
    public $maxPrice = null;
    public $minPrice = null;
    public $minReviewsRating = null;
    public $minSavingPercent = null;
    public $sortBy = null;
    public $title = null;

    /**
     * @param void
     */
	public function __construct()
	{
		$this->resources = ResourceParser::toString([
			new BrowseNodeInfo,
			new Images,
			new ItemInfo,
			new Offers,
			new ParentASIN,
			new SearchRefinements
		]);
	}

    /**
     * @access public
     * @param string|array $keywords
     * @return object
     */
    public function setKeywords($keywords)
    {
        if ( is_array($keywords) ) {
            $keywords = implode(',', $keywords);
        }
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * @access public
     * @param string $actor
     * @return object
     */
    public function setActor($actor)
    {
        $this->actor = $actor;
        return $this;
    }

    /**
     * @access public
     * @param string $artist
     * @return object
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
        return $this;
    }

    /**
     * @access public
     * @param string $author
     * @return object
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @access public
     * @param string $availability
     * @return object
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;
        return $this;
    }

    /**
     * @access public
     * @param string $brand
     * @return object
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * @access public
     * @param string $browseNodeId
     * @return object
     */
    public function setBrowseNodeId($browseNodeId)
    {
        $this->browseNodeId = $browseNodeId;
        return $this;
    }

    /**
     * @access public
     * @param array $deliveryFlags
     * @return object
     */
    public function setDeliveryFlags($deliveryFlags)
    {
        $this->deliveryFlags = (array)$deliveryFlags;
        return $this;
    }

    /**
     * @access public
     * @param int $itemCount
     * @return object
     */
    public function setItemCount($itemCount)
    {
        $this->itemCount = (int)$itemCount;
        return $this;
    }

    /**
     * @access public
     * @param int $itemPage
     * @return object
     */
    public function setItemPage($itemPage)
    {
        $this->itemPage = (int)$itemPage;
        return $this;
    }

    /**
     * @access public
     * @param int $maxPrice
     * @return object
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = (int)$maxPrice;
        return $this;
    }

    /**
     * @access public
     * @param int $minPrice
     * @return object
     */
    public function setMinPrice($minPrice)
    {
        $this->minPrice = (int)$minPrice;
        return $this;
    }

    /**
     * @access public
     * @param int $minReviewsRating
     * @return object
     */
    public function setMinReviewsRating($minReviewsRating)
    {
        $this->minReviewsRating = (int)$minReviewsRating;
        return $this;
    }

    /**
     * @access public
     * @param int $minSavingPercent
     * @return object
     */
    public function setMinSavingPercent($minSavingPercent)
    {
        $this->minSavingPercent = (int)$minSavingPercent;
        return $this;
    }

    /**
     * @access public
     * @param string $sortBy
     * @return object
     */
    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
        return $this;
    }

    /**
     * @access public
     * @param string $title
     * @return object
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @access public
     * @param string $index
     * @return object
     */
    public function setSearchIndex($index)
    {
        $this->searchIndex = $index;
        return $this;
    }
}
