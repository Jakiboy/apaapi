<?php
/**
 * @package Amazon Product Advertising API
 * @version 1.0.7
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\operations;

use Apaapi\lib\ItemOperation;
use Apaapi\interfaces\OperationInterface;
use Apaapi\resources\BrowseNodeInfo;
use Apaapi\resources\Images;
use Apaapi\resources\ItemInfo;
use Apaapi\resources\Offers;
use Apaapi\resources\VariationSummary;
use Apaapi\resources\ParentASIN;

/**
 * Basic Apaapi GetVariations Operation
 * @see https://webservices.amazon.com/paapi5/documentation/get-variations.html
 */
class GetVariations extends ItemOperation 
implements OperationInterface
{
	/**
	 * @access public
     *
	 * @var null|string $ASIN
	 * @var int $variationCount
	 * @var int $variationPage
	 */
	public $ASIN = null;
	public $variationCount = 10;
	public $variationPage = 1;

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
			new VariationSummary,
			new ParentASIN
		];
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
