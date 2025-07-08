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

namespace Apaapi\operations;

use Apaapi\lib\ItemOperation;
use Apaapi\resources\{
    BrowseNodeInfo,
    Images,
    ItemInfo,
    OffersV2,
    RentalOffers,
    SearchRefinements,
    ParentASIN
};
use Apaapi\includes\Parser;

/**
 * Apaapi <SearchItems> operation.
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
    public $searchIndex = 'All';
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
     * Set resources.
     */
    public function __construct()
    {
        $this->resources = Parser::convert([
            new BrowseNodeInfo,
            new Images,
            new ItemInfo,
            new OffersV2,
            new RentalOffers,
            new ParentASIN,
            new SearchRefinements
        ]);
    }

    /**
     * Set keywords.
     *
     * @access public
     * @param string $keywords
     * @return object
     */
    public function setKeywords(string $keywords) : object
    {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * Set actor.
     *
     * @access public
     * @param string $actor
     * @return object
     */
    public function setActor(string $actor) : object
    {
        $this->actor = $actor;
        return $this;
    }

    /**
     * Set artist.
     *
     * @access public
     * @param string $artist
     * @return object
     */
    public function setArtist(string $artist) : object
    {
        $this->artist = $artist;
        return $this;
    }

    /**
     * Set author.
     *
     * @access public
     * @param string $author
     * @return object
     */
    public function setAuthor(string $author) : object
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Set availability.
     *
     * @access public
     * @param string $availability
     * @return object
     */
    public function setAvailability(string $availability) : object
    {
        $this->availability = $availability;
        return $this;
    }

    /**
     * Set brand.
     *
     * @access public
     * @param string $brand
     * @return object
     */
    public function setBrand(string $brand) : object
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * Set browse node id.
     *
     * @access public
     * @param string $id
     * @return object
     */
    public function setBrowseNodeId(string $id) : object
    {
        $this->browseNodeId = $id;
        return $this;
    }

    /**
     * Set delivery flags.
     *
     * @access public
     * @param array $flags
     * @return object
     */
    public function setDeliveryFlags(array $flags) : object
    {
        $this->deliveryFlags = $flags;
        return $this;
    }

    /**
     * Set item count.
     *
     * @access public
     * @param int $count
     * @return object
     */
    public function setItemCount(int $count) : object
    {
        $this->itemCount = $count;
        return $this;
    }

    /**
     * Set item page.
     *
     * @access public
     * @param int $page
     * @return object
     */
    public function setItemPage(int $page) : object
    {
        $this->itemPage = $page;
        return $this;
    }

    /**
     * Set max price.
     *
     * @access public
     * @param int $price
     * @return object
     */
    public function setMaxPrice(int $price) : object
    {
        $this->maxPrice = $price;
        return $this;
    }

    /**
     * Set min price.
     *
     * @access public
     * @param int $price
     * @return object
     */
    public function setMinPrice(int $price) : object
    {
        $this->minPrice = $price;
        return $this;
    }

    /**
     * Set min reviews rating.
     *
     * @access public
     * @param int $rating
     * @return object
     */
    public function setMinReviewsRating(int $rating) : object
    {
        $this->minReviewsRating = $rating;
        return $this;
    }

    /**
     * Set min saving percent.
     *
     * @access public
     * @param int $percent
     * @return object
     */
    public function setMinSavingPercent(int $percent) : object
    {
        $this->minSavingPercent = $percent;
        return $this;
    }

    /**
     * Set sort by.
     *
     * @access public
     * @param string $sortBy
     * @return object
     */
    public function setSortBy(string $sortBy) : object
    {
        $this->sortBy = $sortBy;
        return $this;
    }

    /**
     * Set title.
     *
     * @access public
     * @param string $title
     * @return object
     */
    public function setTitle(string $title) : object
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set search index.
     *
     * @access public
     * @param string $index
     * @return object
     */
    public function setSearchIndex(string $index) : object
    {
        $this->searchIndex = $index;
        return $this;
    }
}
