<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Product Advertising API Library (v5)
 * @version   : 1.5.x
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
	Offers,
	OffersV2Full,
	RentalOffers,
	VariationSummary,
	ParentASIN
};
use Apaapi\includes\Parser;

/**
 * Apaapi <GetVariations> operation.
 * @see https://webservices.amazon.com/paapi5/documentation/get-variations.html
 */
final class GetVariations extends ItemOperation
{
	/**
	 * @access public
	 * @var string $ASIN
	 * @var int $variationCount
	 * @var int $variationPage
	 */
	public $ASIN = null;
	public $variationCount = 10;
	public $variationPage = 1;

	/**
	 * Set resources.
	 */
	public function __construct(bool $isOffersV2 = false)
	{
		$this->resources = Parser::convert([
			new BrowseNodeInfo,
			new Images,
			new ItemInfo,
			$isOffersV2 ? new OffersV2Full : new Offers,
			new RentalOffers,
			new VariationSummary,
			new ParentASIN
		]);
	}

	/**
	 * Set ASIN.
	 *
	 * @access public
	 * @param string $ASIN
	 * @return object
	 */
	public function setASIN(string $ASIN) : object
	{
		$this->ASIN = $ASIN;
		return $this;
	}

	/**
	 * Set variation count.
	 *
	 * @access public
	 * @param int $count
	 * @return object
	 */
	public function setVariationCount(int $count) : object
	{
		$this->variationCount = $count;
		return $this;
	}

	/**
	 * Set variation page.
	 *
	 * @access public
	 * @param int $page
	 * @return object
	 */
	public function setVariationPage(int $page) : object
	{
		$this->variationPage = $page;
		return $this;
	}
}
