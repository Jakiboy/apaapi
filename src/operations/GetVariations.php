<?php
/**
 * @author    : Jakiboy
 * @package   : Amazon Creators API Library
 * @version   : 2.0.x
 * @copyright : (c) 2019 - 2026 Jihad Sinnaour <me@jihadsinnaour.com>
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
	VariationSummary,
	ParentASIN
};
use Apaapi\includes\Parser;

/**
 * Apaapi <GetVariations> operation.
 * @see https://affiliate-program.amazon.com/creatorsapi/docs/en-us/get-variations.html
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
	public function __construct()
	{
		$this->resources = Parser::convert([
			new BrowseNodeInfo,
			new Images,
			new ItemInfo,
			new OffersV2,
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
