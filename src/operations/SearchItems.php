<?php
/**
 * @package Amazon Product Advertising API
 * @copyright Copyright (c) 2019 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\operations;

use Apaapi\lib\ItemOperation;
use Apaapi\interfaces\ItemOperationInterface;
use Apaapi\resources\BrowseNodeInfo;
use Apaapi\resources\Images;
use Apaapi\resources\ItemInfo;
use Apaapi\resources\Offers;
use Apaapi\resources\SearchRefinements;
use Apaapi\resources\ParentASIN;

/**
 * Basic Apaapi SearchItems Operation
 * @see https://webservices.amazon.com/paapi5/documentation/search-items.html
 */
class SearchItems extends ItemOperation 
implements ItemOperationInterface
{
    /**
     * @access public
     *
     * @var string $searchIndex
     * @var string $availability
     * @var int $itemCount
     * @var int $itemPage
     * @var null|array $deliveryFlags
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
     * @var json $properties
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
    public $properties = null;
    public $sortBy = null;
    public $title = null;

    /**
     * @param void
     * @return void
     */
	public function __construct()
	{
		$this->resources = [
			new BrowseNodeInfo,
			new Images,
			new ItemInfo,
			new Offers,
			new SearchRefinements,
			new ParentASIN
		];
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
     * @param string $actor
     * @return object
     */
    public function setActor($actor)
    {
        $this->actor = $actor;
        return $this;
    }

    /**
     * @param string $artist
     * @return object
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
        return $this;
    }

    /**
     * @param string $author
     * @return object
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @param string $availability
     * @return object
     */
    public function setAvailability($availability)
    {
        $this->availability = $availability;
        return $this;
    }

    /**
     * @param string $brand
     * @return object
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * @param string $browseNodeId
     * @return object
     */
    public function setBrowseNodeId($browseNodeId)
    {
        $this->browseNodeId = $browseNodeId;
        return $this;
    }

    /**
     * @param array $deliveryFlags
     * @return object
     */
    public function setDeliveryFlags($deliveryFlags)
    {
        $this->deliveryFlags = $deliveryFlags;
        return $this;
    }

    /**
     * @param int $itemCount
     * @return object
     */
    public function setItemCount($itemCount)
    {
        $this->itemCount = $itemCount;
        return $this;
    }

    /**
     * @param int $itemPage
     * @return object
     */
    public function setItemPage($itemPage)
    {
        $this->itemPage = $itemPage;
        return $this;
    }

    /**
     * @param int $maxPrice
     * @return object
     */
    public function setMaxPrice($maxPrice)
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

    /**
     * @param int $minPrice
     * @return object
     */
    public function setMinPrice($minPrice)
    {
        $this->minPrice = $minPrice;
        return $this;
    }

    /**
     * @param int $minReviewsRating
     * @return object
     */
    public function setMinReviewsRating($minReviewsRating)
    {
        $this->minReviewsRating = $minReviewsRating;
        return $this;
    }

    /**
     * @param int $minSavingPercent
     * @return object
     */
    public function setMinSavingPercent($minSavingPercent)
    {
        $this->minSavingPercent = $minSavingPercent;
        return $this;
    }

    /**
     * @param json $properties
     * @return object
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;
        return $this;
    }

    /**
     * @param string $sortBy
     * @return object
     */
    public function setSortBy($sortBy)
    {
        $this->sortBy = $sortBy;
        return $this;
    }

    /**
     * @param string $title
     * @return object
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
}
