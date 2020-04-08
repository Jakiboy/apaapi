<?php
/**
 * @package Amazon Product Advertising API
 * @copyright (c) 2019 - 2020 Jakiboy
 * @author Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link https://jakiboy.github.io/apaapi/
 * @license MIT
 */

namespace Apaapi\resources;

use Apaapi\lib\Resource;

/**
 * ItemInfo : High Level Resource
 * @see https://webservices.amazon.com/paapi5/documentation/item-info.html
 */
class ItemInfo extends Resource
{
	/**
	 * @param void
	 * @return void
	 */
	public function __construct()
	{
		$this->child = [
			'ByLineInfo'      => ['Brand','Contributors','Manufacturer'],
			'Classifications' => ['Binding','ProductGroup'],
			'ContentInfo'     => ['Edition','Languages','PagesCount','PublicationDate'],
			'ContentRating'   => ['AudienceRating'],
			'ExternalIds'     => ['EANs','ISBNs','UPCs'],
			'ManufactureInfo' => ['ItemPartNumber','Model','Warranty'],
			'ProductInfo'     => ['Color','IsAdultProduct','ItemDimensions','ReleaseDate','Size','UnitCount'],
			'TechnicalInfo'   => ['Formats'],
			'TradeInInfo'     => ['IsEligibleForTradeIn','Price'],
			'Features',
			'Title'
		];
	}
}
