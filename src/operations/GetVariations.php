<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : Apaapi | Amazon Product Advertising API Library (v5)
 * @version   : 1.1.7
 * @copyright : (c) 2019 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
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
use Apaapi\resources\RentalOffers;
use Apaapi\resources\VariationSummary;
use Apaapi\resources\ParentASIN;
use Apaapi\includes\ResourceParser;

/**
 * Basic Apaapi GetVariations Operation.
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
	 * @param void
	 */
	public function __construct()
	{
		$this->resources = ResourceParser::toString([
			new BrowseNodeInfo,
			new Images,
			new ItemInfo,
			new Offers,
			new RentalOffers,
			new VariationSummary,
			new ParentASIN
		]);
	}

	/**
	 * @access public
	 * @param string $ASIN
	 * @return object
	 */
    public function setASIN($ASIN)
    {
    	$this->ASIN = $ASIN;
    	return $this;
    }

	/**
	 * @access public
	 * @param int $variationCount
	 * @return object
	 */
    public function setVariationCount($variationCount)
    {
    	$this->variationCount = $variationCount;
    	return $this;
    }

	/**
	 * @access public
	 * @param int $variationPage
	 * @return object
	 */
    public function setVariationPage($variationPage)
    {
    	$this->variationPage = $variationPage;
    	return $this;
    }
}
