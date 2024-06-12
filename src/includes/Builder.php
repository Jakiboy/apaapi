<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.2.0
 * @copyright : (c) 2019 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/apaapi/
 * @license   : MIT
 *
 * This file if a part of Apaapi Lib.
 */

declare(strict_types=1);

namespace Apaapi\includes;

use Apaapi\lib\{
    Request,
    Response,
    Cart
};
use Apaapi\operations\{
    GetItems,
    SearchItems,
    GetVariations,
    GetBrowseNodes
};

/**
 * Apaapi request builder.
 */
final class Builder
{
    /**
     * @access private
     * @var string $key, API key
     * @var string $secret, API secret
     * @var string $tag, API tag
     * @var string $locale, API locale|region
     * @var object $operation, API operation
     * @var object $request, API request
     * @var string $error, API response error
     * @var bool $cache, API response cache
     * @var array $redirect, Geotargeting args
     * @var mixed $order, Response order
     */
    private $key;
    private $secret;
    private $tag;
    private $locale;
    private $operation;
    private $request;
    private $error = false;
    private $cache = true;
    private $redirect;
    private $order = ['title', 'price'];

    public static $isCategory = false;

    /**
     * Set request authentication.
     *
     * @param string $key
     * @param string $secret
     * @param string $tag
     * @param string $locale
     */
    public function __construct(string $key, string $secret, string $tag, string $locale)
    {
        $this->key    = $key;
        $this->secret = $secret;
        $this->tag    = $tag;
        $this->locale = $locale;
    }

    /**
     * Check authorization.
     *
     * @access public
     * @return bool
     */
    public function isAuthorized() : bool
    {
        $this->setup('search');

        $this->operation->setResources(['ItemInfo.Title']);
        $this->operation->setItemCount(1)->setKeywords('amazon');

        $this->prepare();
        $response = new Response($this->request, false, false);
        $response->get();
        
        if ( $response->hasError() ) {
            $this->error = $response->getError();
            return false;
        }

        return true;
    }

    /**
     * Get items.
     *
     * @access public
     * @param mixed $ids
     * @param array $filter
     * @return array
     */
    public function get($ids, array $filter = []) : array
    {
        $this->setup('get')->filter($filter);

        $this->operation->setResources(
            $this->getDefaultResources(
                $this->getFilterResources($filter)
            )
        );

        $this->operation->setItemIds(
            Normalizer::formatIds($ids)
        );

        $items = $this->prepare()->fetch();
        return Normalizer::order($items, $this->order);
    }

    /**
     * Get single item.
     *
     * @access public
     * @param string $id
     * @param array $filter
     * @return array
     */
    public function getOne(string $id, array $filter = []) : array
    {
        $this->setup('get')->filter($filter);

        $this->operation->setResources(
            $this->getDefaultResources(
                $this->getFilterResources($filter)
            )
        );

        $this->operation->setItemIds([
            Normalizer::formatId($id)
        ]);

        $item = $this->prepare()->fetch();
        return $item[0] ?? [];
    }

    /**
     * Search items.
     *
     * @access public
     * @param mixed $keywords
     * @param int $count
     * @param int $page
     * @param array $filter
     * @return array
     */
    public function search($keywords, int $count = 5, int $page = 1, array $filter = []) : array
    {
        $this->setup('search')->filter($filter);

        $this->operation->setResources(
            $this->getDefaultResources(
                $this->getFilterResources($filter)
            )
        );

        $this->operation->setItemCount($count)->setItemPage($page);
        $this->operation->setKeywords(
            Normalizer::formatKeywords($keywords)
        );

        $items = $this->prepare()->fetch();
        return Normalizer::order($items, $this->order);
    }

    /**
     * Search single item.
     * 
     * @access public
     * @param string $keyword
     * @param array $filter
     * @return array
     */
    public function searchOne(string $keyword, array $filter = []) : array
    {
        $this->setup('search')->filter($filter);

        $this->operation->setResources(
            $this->getDefaultResources(
                $this->getFilterResources($filter)
            )
        );

        $this->operation->setItemCount(1);
        $this->operation->setKeywords(
            Normalizer::formatKeyword($keyword)
        );

        $item = $this->prepare()->fetch();
        return $item[0] ?? [];
    }

    /**
     * Get item variations.
     *
     * @access public
     * @param string $id
     * @param int $count
     * @param int $page
     * @param array $filter
     * @return array
     */
    public function getVariation(string $id, int $count = 5, int $page = 1, array $filter = []) : array
    {
        $this->setup('variation')->filter($filter);

        $this->operation->setResources(
            $this->getDefaultResources(
                $this->getFilterResources($filter)
            )
        );

        $this->operation->setVariationCount($count)->setVariationPage($page);
        $this->operation->setASIN(
            Normalizer::formatId($id)
        );

        $items = $this->prepare()->fetch();
        return Normalizer::order($items, $this->order);
    }

    /**
     * Seatch item variations.
     *
     * @access public
     * @param string $keyword
     * @param int $count
     * @param int $page
     * @param array $filter
     * @return array
     */
    public function searchVariation(string $keyword, int $count = 5, int $page = 1, array $filter = []) : array
    {
        $id = Normalizer::formatId($keyword);
        if ( !Keyword::isASIN($id) ) {
            $keyword = $this->toASIN($keyword, $filter);
        }
        return $this->getVariation($keyword, $count, $page, $filter);
    }

    /**
     * Convert keyword to ASIN (ISBN).
     *
     * @access public
     * @param string $keyword
     * @param array $filter
     * @return string
     */
    public function toASIN(string $keyword, array $filter = []) : string
    {
        $id = Normalizer::formatId($keyword);
        if ( Keyword::isASIN($id) ) {
            return $id;
        }

        $this->setup('search')->filter($filter);

        $this->operation->setResources(['ItemInfo.Title']);
        $this->operation->setItemCount(1);
        $this->operation->setKeywords(
            Normalizer::formatKeyword($keyword)
        );

        $item = $this->prepare()->fetch();
        $item = $item[0] ?? [];
        return $item['asin'] ?? '';
    }

    /**
     * Convert keyword to EAN.
     *
     * @access public
     * @param string $keyword
     * @param array $filter
     * @return string
     */
    public function toEAN(string $keyword, array $filter = []) : string
    {
        $this->setup('search')->filter($filter);

        $this->operation->setResources(['ItemInfo.ExternalIds']);
        $this->operation->setItemCount(1);
        $this->operation->setKeywords(
            Normalizer::formatKeyword($keyword)
        );

        $item = $this->prepare()->fetch();
        $item = $item[0] ?? [];
        return $item['ean'] ?? '';
    }

    /**
     * Convert keyword to NodeId.
     *
     * @access public
     * @param string $keyword
     * @param array $filter
     * @param bool $root
     * @return string
     */
    public function toNodeId(string $keyword, array $filter = [], bool $root = false) : string
    {
        $this->setup('search')->filter($filter);

        $this->operation->setResources([
            'BrowseNodeInfo.BrowseNodes.Ancestor'
        ]);

        $this->operation->setItemCount(1);
        $this->operation->setKeywords(
            Normalizer::formatKeyword($keyword)
        );

        $item = $this->prepare()->fetch();
        $item = $item[0] ?? [];

        if ( $root ) {
            return $item['root'] ?? '';
        }
        return $item['node'] ?? '';
    }

    /**
     * Convert keyword to NodeId (root).
     *
     * @access public
     * @param string $keyword
     * @param array $filter
     * @return string
     */
    public function toRootId(string $keyword, array $filter = []) : string
    {
        return $this->toNodeId($keyword, $filter, true);
    }

    /**
     * Get node (Category).
     *
     * @access public
     * @param string $id
     * @return array
     */
    public function getNode(string $id) : array
    {
        $this->setup('node');

        $this->operation->setBrowseNodeIds([
            Normalizer::formatId($id)
        ]);

        $node = $this->prepare()->fetch();
        $node = Normalizer::applyArgs($node, [
            '{locale}' => $this->locale,
            '{tag}'    => $this->tag
        ]);
        
        return (array)$node;
    }

    /**
     * Search node (Category).
     *
     * @access public
     * @param string $keyword
     * @param array $filter
     * @return array
     * @todo Add ISBN & EAN compatibility
     */
    public function searchNode(string $keyword, array $filter = []) : array
    {
        $id = Normalizer::formatId($keyword);
        if ( Keyword::isASIN($id) || !Keyword::isBarcode($id) ) {
            $id = $this->toNodeId($id, $filter);
        }
        return $this->getNode($id);
    }

    /**
     * Get item root category Id (Search Index).
     *
     * @access public
     * @param string $keyword
     * @param array $filter
     * @return string
     */
    public function getCategory(string $keyword, array $filter = []) : string
    {
        unset($filter['category']);
        $this->setup('search')->filter($filter);

        $this->operation->setResources([
            'BrowseNodeInfo.BrowseNodes.Ancestor'
        ]);

        $this->operation->setItemCount(1);
        $this->operation->setKeywords(
            Normalizer::formatKeyword($keyword)
        );

        $item = $this->prepare()->fetch();
        $item = $item[0] ?? [];

        $category = $item['category'] ?? '';
        return Provider::getCategoryId($category, $this->locale);
    }

    /**
     * Search item category (Search Index).
     *
     * @access public
     * @param string $keyword
     * @param array $filter
     * @return array
     */
    public function searchCategory(string $keyword, array $filter = []) : array
    {
        self::$isCategory = true;
        $this->setup('search')->filter($filter);

        $this->operation->setResources([
            'SearchRefinements'
        ]);

        $this->operation->setItemCount(1);
        $this->operation->setKeywords(
            Normalizer::formatKeyword($keyword)
        );

        $item = $this->prepare()->fetch();
        return $item[0] ?? [];
    }

    /**
     * Get bestseller items (Id).
     *
     * @access public
     * @param string $id
     * @param int $count
     * @param int $page
     * @param array $filter
     * @return array
     * @todo Improve
     */
    public function getBestseller(string $id, int $count = 5, int $page = 1, array $filter = []) : array
    {
        $id = Normalizer::formatId($id);
        if ( !Keyword::isBarcode($id) ) {
            return [];
        }

        unset($filter['node']);
        $filter['node'] = $id;

        $node = $this->searchNode($id);
        if ( !$this->hasError() ) {
            $id = $node['name'];
        }

        $this->setup('search')->filter($filter);

        $this->operation->setResources(
            $this->getDefaultResources([
                'BrowseNodeInfo.BrowseNodes.SalesRank'
            ])
        );

        $this->operation->setItemCount($count)->setItemPage($page);
        $this->operation->setKeywords($id);

        $items = $this->prepare()->fetch();
        return Normalizer::order($items, ['rank', 'title']);
    }

    /**
     * Search bestseller items (Keyword).
     *
     * @access public
     * @param string $keyword
     * @param int $count
     * @param int $page
     * @param array $filter
     * @return array
     * @todo Improve
     */
    public function searchBestseller(string $keyword, int $count = 5, int $page = 1, array $filter = []) : array
    {
        $keyword = Normalizer::formatKeyword($keyword);
        $id = Normalizer::formatId($keyword);

        if ( Keyword::isBarcode($id) ) {
            $item = $this->searchOne($id, $filter);
            if ( $this->hasError() ) {
                return [];
            }
            $keyword = Normalizer::parseKeyword($item['title']);
        }

        if ( !isset($filter['title']) ) {
            $filter['title'] = $keyword;
        }
        
        $this->setup('search')->filter($filter);

        $this->operation->setResources(
            $this->getDefaultResources([
                'BrowseNodeInfo.BrowseNodes.SalesRank'
            ])
        );

        $this->operation->setItemCount($count)->setItemPage($page);
        $this->operation->setKeywords($keyword);

        $items = $this->prepare()->fetch();
        return Normalizer::order($items, ['rank', 'title']);
    }

    /**
     * Get newest items.
     *
     * @access public
     * @param string $keyword
     * @param int $count
     * @param int $page
     * @param array $filter
     * @return array
     * @todo Improve
     */
    public function getNewest(string $keyword, int $count = 5, int $page = 1, array $filter = []) : array
    {
        $keyword = Normalizer::formatKeyword($keyword);
        $filter['sort']  = 'newest';
        $filter['title'] = $keyword;
        return $this->search($keyword, $count, $page, $filter);
    }

	/**
	 * Get cart (URL).
	 *
	 * @access public
	 * @param mixed $items
	 * @return string
	 */
	public function getCart($items) : string
	{
		$cart = new Cart();
		$cart->setLocale($this->locale);
		$cart->setPartnerTag($this->tag);

		return $cart->set(
            Normalizer::formatCart($items)
        );
	}

	/**
	 * Get rating.
	 *
	 * @access public
	 * @param string $keyword
	 * @return array
	 */
	public function getRating(string $keyword) : array
	{
		$rating = new Rating($keyword, $this->locale, $this->tag);
        return $rating->get();
	}

	/**
	 * Search rating.
	 *
	 * @access public
	 * @param string $keyword
	 * @param array $filter
	 * @return array
	 */
	public function searchRating(string $keyword, array $filter = []) : array
	{
        $id = Normalizer::formatId($keyword);
        if ( !Keyword::isASIN($id) ) {
            $keyword = $this->toASIN($keyword, $filter);
        }
        return $this->getRating($keyword);
	}
    
    /**
     * Set order.
     *
     * @access public
     * @param mixed $order
     * @return self
     */
    public function order($order) : self
    {
        $this->order = $order;
        return $this;
    }
    
    /**
     * Disable cache.
     *
     * @access public
     * @return self
     */
    public function noCache() : self
    {
        $this->cache = false;
        return $this;
    }

    /**
     * Enable geotargeting.
     *
     * @access public
     * @param array $redirect
     * @return object
     */
    public function redirect(array $redirect) : self
    {
        $this->redirect = array_merge([
            'tag'    => $this->tag,
            'locale' => $this->locale
        ], $redirect);
        return $this;
    }

    /**
     * Get response error.
     *
     * @access public
     * @return string
     */
    public function getError() : string
    {
        return (string)$this->error;
    }

    /**
     * Check response error.
     *
     * @access public
     * @return bool
     */
    public function hasError() : bool
    {
        return (bool)$this->error;
    }

    /**
     * Setup operation.
     *
     * @access private
     * @param string $operation
     * @return object
     */
    private function setup(string $operation) : self
    {
        switch ($operation) {
            case 'get':
                $this->operation = new GetItems();
                break;

            case 'search':
                $this->operation = new SearchItems();
                break;

            case 'variation':
                $this->operation = new GetVariations();
                break;

            case 'node':
                $this->operation = new GetBrowseNodes();
                break;
        }

        $this->operation->setPartnerTag($this->tag);

        return $this;
    }

	/**
     * Get default resources.
     *
	 * @access private
	 * @param array $resources
	 * @return array
	 */
	private function getDefaultResources(array $resources = []) : array
	{
		return array_merge([
			'BrowseNodeInfo.BrowseNodes.Ancestor',
			'Images.Primary.Large',
			'Images.Variants.Large',
			'Offers.Listings.Price',
			'Offers.Listings.Condition',
			'Offers.Listings.Promotions',
			'Offers.Listings.SavingBasis',
			'Offers.Listings.DeliveryInfo.IsAmazonFulfilled',
			'Offers.Listings.DeliveryInfo.IsFreeShippingEligible',
			'Offers.Listings.DeliveryInfo.IsPrimeEligible',
            'Offers.Listings.Availability.Message',
			'ItemInfo.ExternalIds',
			'ItemInfo.Features',
			'ItemInfo.Title'
		], $resources);
	}

	/**
     * Get filter ressources.
     *
	 * @access private
	 * @param array $resources
	 * @return array
	 */
	private function getFilterResources(array $filter = []) : array
	{
        $resources = [];
        if ( isset($filter['rank']) && $filter['rank'] === true ) {
            $resources[] = 'BrowseNodeInfo.BrowseNodes.SalesRank';
        }
        return $resources;
	}

    /**
     * Filter request.
     *
     * @access private
     * @param array $filter
     * @return object
     */
    private function filter(array $filter) : self
    {
        if ( $this->isSearch() ) {

            $min = $filter['min'] ?? null;
            if ( $min ) {
                $this->operation->setMinPrice(
                    Normalizer::formatPrice($min)
                );
            }
    
            $max = $filter['max'] ?? null;
            if ( $max ) {
                $this->operation->setMaxPrice(
                    Normalizer::formatPrice($max)
                );
            }

            $available = $filter['available'] ?? null;
            if ( $available === false ) {
                $this->operation->setAvailability('IncludeOutOfStock');
            }

            $delivery = $filter['delivery'] ?? null;
            if ( $delivery ) {
                $this->operation->setDeliveryFlags(
                    Normalizer::formatDelivery($delivery)
                );
            }

            $reviews = $filter['reviews'] ?? null;
            $reviews = (int)$reviews;
            if ( $reviews ) {
                $this->operation->setMinReviewsRating($reviews);
            }

            $saving = $filter['saving'] ?? null;
            $saving = (int)$saving;
            if ( $saving ) {
                $this->operation->setMinSavingPercent($saving);
            }

            $sort = $filter['sort'] ?? null;
            $sort = (string)$sort;
            if ( $sort ) {
                $this->operation->setSortBy(
                    Normalizer::formatSort($sort)
                );
            }

            $category = $filter['category'] ?? null;
            $category = (string)$category;
            if ( $category ) {
                $this->operation->setSearchIndex(
                    Normalizer::formatCategory($category)
                );
            }

            $brand = $filter['brand'] ?? null;
            $brand = (string)$brand;
            if ( $brand ) {
                $this->operation->setBrand($brand);
            }

            $node = $filter['node'] ?? null;
            $node = (string)$node;
            if ( $node ) {
                $this->operation->setBrowseNodeId($node);
            }

            $title = $filter['title'] ?? null;
            $title = (string)$title;
            if ( $title ) {
                $this->operation->setTitle($title);
            }

            $actor = $filter['actor'] ?? null;
            $actor = (string)$actor;
            if ( $actor ) {
                $this->operation->setActor($actor);
            }

            $artist = $filter['artist'] ?? null;
            $artist = (string)$artist;
            if ( $artist ) {
                $this->operation->setArtist($artist);
            }
            
            $author = $filter['author'] ?? null;
            $author = (string)$author;
            if ( $author ) {
                $this->operation->setAuthor($author);
            }

        }
        
        $condition = $filter['condition'] ?? null;
        $condition = (string)$condition;
        if ( $condition ) {
            $this->operation->setCondition(
                Normalizer::formatCondition($condition)
            );
        }
        
        $merchant = $filter['merchant'] ?? null;
        $merchant = (string)$merchant;
        if ( $merchant ) {
            $this->operation->setMerchant($merchant);
        }

        $language = $filter['lang'] ?? null;
        if ( $language ) {
            $this->operation->setLanguages(
                Normalizer::formatLanguage($language)
            );
        }

        $currency = $filter['currency'] ?? null;
        $currency = (string)$currency;
        if ( $currency ) {
            $this->operation->setCurrency(
                Normalizer::formatCurrency($currency)
            );
        }

        $marketplace = $filter['marketplace'] ?? null;
        $marketplace = (string)$marketplace;
        if ( $marketplace ) {
            $this->operation->setMarketplace(
                Normalizer::formatMarketplace($marketplace)
            );
        }
        
        return $this;
    }

    /**
     * Check search operation.
     *
     * @access private
     * @return bool
     */
    private function isSearch() : bool
    {
        $class = 'Apaapi\operations\SearchItems';
        return is_a($this->operation, $class, true);
    }

    /**
     * Prepare request.
     *
     * @access private
     * @return object
     */
    private function prepare() : self
    {
        $this->request = new Request($this->key, $this->secret);
        $this->request->setLocale($this->locale)->setPayload($this->operation);
        return $this;
    }

    /**
     * Fetch response.
     *
     * @access private
     * @return array
     */
    private function fetch() : array
    {
        $response = new Response($this->request, Response::NORMALIZE, $this->cache);

        $this->error = false;
        if ( $response->hasError() ) {
            $this->error = $response->getError();
        }

        return $response->get(
            $this->redirect
        );
    }
}
