# APAAPI

<img src="https://raw.githubusercontent.com/Jakiboy/apaapi/master/amazon.svg" width="100" alt="Amazon Product Advertising API PHP">

Amazon Product Advertising API V5.0 (**Without Amazon SDK**).  
This repository contains a PHP Lightweight (155 Ko) Wrapper Library,  
Easily access the [Amazon Product Advertising API V5.0](https://webservices.amazon.com/paapi5/documentation/index.html) from your PHP app.

-- Become an Amazon Affiliate With PHP --

## 🔧 Installing:

#### Using Composer:

```
composer require jakiboy/apaapi
```

#### Without Composer?


* **1** - [Download repository ZIP](https://github.com/Jakiboy/apaapi/archive/refs/heads/master.zip) (*Latest version*).
* **2** - Extract ZIP (*apaapi-master*).
* **3** - Include this lines beelow (*apaapi self-autoloader*).


```
include('apaapi-master/src/Autoloader.php');
\apaapi\Autoloader::init();
```

* **4** - You can now use the [Quickstart examples](#quickstart).

## 💡 Upgrade :

**See changes before migrate**: 

This version includes:  

* Basic built-in **Caching System**.
* **Request Builder** (Easier way to fetch data).
* **Response Normalizer** (Normalize response items).
* **Search Filters** (Using builder).
* **Geotargeting** (Automatically redirect links based on the visitor's region).
* **Rating** (Lagacy).
* **Keyword Converter** (ASIN, ISBN, EAN, Node, Root).

[Full Changelog](#).


## ⚡ Getting Started:

### Variables (Basics):

* "_TAG_" : From your Amazon Associates (*your locale*), [More](https://webservices.amazon.com/paapi5/documentation/troubleshooting/sign-up-as-an-associate.html). 
* "_SECRET_" : From your Amazon Associates (*your locale*), [More](https://affiliate-program.amazon.com/help/node/topic/GTPNVFFUV2GQ8AZV). 
* "_KEY_" : From your Amazon Associates (*your locale*), [More](https://affiliate-program.amazon.com/help/node/topic/GTPNVFFUV2GQ8AZV). 
* "_KEYWORDS_" : What you are looking for (*Products*), [More](https://webservices.amazon.com/paapi5/documentation/search-items.html). 
* "_REGION_" : **TLD** of the target to which you are sending requests (*com/fr/com.be/de*), [Get TLD](https://webservices.amazon.com/paapi5/documentation/common-request-parameters.html#host-and-region). 
* "_ASIN_" : Amazon Standard Identification Number (*your locale*), [More](https://webservices.amazon.com/paapi5/documentation/get-items.html#ItemLookup-rp). 


### Quickstart:

```php

/**
 * @see Use Composer, 
 * Or include Apaapi Autoloader Here.
 */

use Apaapi\includes\Builder;

// Init request builder
$builder = new Builder('_KEY_', '_SECRET_', '_TAG_', '_REGION_');

// Get response
$data = $builder->searchOne('Sony Xperia Pro-I') // Normalized array

```

### Quickstart (OLD):


```php


/**
 * @see You can use Composer, 
 * Or include Apaapi Autoloader Here.
 */

use Apaapi\operations\SearchItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;

/**
 * @see With Three Easy Steps,
 * You can Achieve Quick Connection to Amazon Affiliate Program, 
 * Via Amazon Product Advertising API Library.
 */

// (1) Set Operation
$operation = new SearchItems();
$operation->setPartnerTag('_TAG_')->setKeywords('_KEYWORDS_');

// (2) Prapere Request
$request = new Request('_KEY_','_SECRET_');
$request->setLocale('_REGION_')->setPayload($operation);

// (3) Get Response
$response = new Response($request);
echo $response->get(); // JSON ready for parsing

```

* *See all available TLDs used by setLocale() at [/docs/tlds.md](https://github.com/Jakiboy/apaapi/tree/master/docs/tlds.md)*

### Operations:

```php

use Apaapi\operations\GetItems;
use Apaapi\operations\SearchItems;
use Apaapi\operations\GetVariations;
use Apaapi\operations\GetBrowseNodes;

/**
 * @see 4 Operations.
 * @see https://webservices.amazon.com/paapi5/documentation/operations.html
 */

// GetItems
$operation = new GetItems();
$operation->setPartnerTag('_TAG_')
->setItemIds(['_ASIN_']); // Array|String

// SearchItems
$operation = new SearchItems();
$operation->setPartnerTag('_TAG_')
->setKeywords('_KEYWORDS_'); // Array|String

// GetVariations
$operation = new GetVariations();
$operation->setPartnerTag('_TAG_')
->setASIN('_ASIN_'); // String

// GetBrowseNodes
$operation = new GetBrowseNodes();
$operation->setPartnerTag('_TAG_')
->setBrowseNodeIds(['{NodeId}']); // Array|String

```

### Advanced (Custom ressources):

```php

/**
 * @see Using setResources() method to set custom ressources,
 * Instead of default ressources,
 * This can improve response time.
 */

// Set Operation
$operation->setPartnerTag('_TAG_')->setKeywords('_KEYWORDS_')
->setResources(['Images.Primary.Small','ItemInfo.Title','Offers.Listings.Price']);

```

* *See all available ressources used by setResources() at [/docs/ressources.md](https://github.com/Jakiboy/apaapi/tree/master/docs/ressources.md)*

### Advanced (Custom HTTP Request Client):

```php

use Apaapi\operations\GetItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;
use Apaapi\includes\RequestClient;

/**
 * @see Extending RequestClient: Allows Overriding cURL|Stream Settings,
 * Or Using Other Stream Instead.
 */
class MyRequestClient extends RequestClient
{
	// ...
}

// Set Operation
$operation = new GetItems();
$operation->setPartnerTag('_TAG_')->setItemIds('_ASIN_');

// Prapere Request
$request = new Request('_KEY_','_SECRET_');
$request->setLocale('{your-region}')->setPayload($operation);

// Set Custom Client After Payload
$request->setClient(
	new MyRequestClient($request->getEndpoint(), $request->getParams())
);

// Get Response
$response = new Response($request);
echo $response->get(); // JSON ready for parsing

```
### Advanced (Response Type Helper):

```php

use Apaapi\includes\ResponseType;

/**
 * @see Helps generating quick decoded response.
 * @param object|array|serialized
 */

// Get Response
$response = new Response($request, new ResponseType('array'));
return $response->get(); // Array ready to be used

```

```php

use Apaapi\includes\ResponseType;

/**
 * @see Helps parsing response.
 * @param Response::PARSE
 */

// Get Response
$response = new Response($request, new ResponseType('object'), Response::PARSE);
return $response->get(); // Object ready to be used

```
### Advanced (Response Errors):

```php

/**
 * @see Error catching.
 */

// Get Response
$response = new Response($request);
$data = $response->get(); // JSON error ready for parsing
if ( $response->hasError() ) {
	/**
	 * @param bool $single error
	 * @return mixed
	 */
	echo $response->getError(true); // Parsed error
}

```

### Add to cart:

```php

// Set Cart
$cart = new Cart();
$cart->setLocale('{Your-locale}');
$cart->setPartnerTag('_TAG_');

// Set Items
$items = [
    '{ASIN1}' => '3', // (_ASIN_ => {Quantity})
    '{ASIN2}' => '5'
];

// Get Response
return $cart->add($items); // String URL

```

## Contributing:

Please read [CONTRIBUTING.md](https://github.com/Jakiboy/apaapi/blob/master/CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning:

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/Jakiboy/apaapi/tags). 

## Authors:

* **Jihad Sinnaour** - [Jakiboy](https://github.com/Jakiboy) (*Initial work*)

See also the full list of [contributors](https://github.com/Jakiboy/apaapi/contributors) who participated in this project. Any suggestions (Pull requests) are welcome!

## License:

This project is licensed under the MIT License - see the [LICENSE](https://github.com/Jakiboy/apaapi/blob/master/LICENSE) file for details.

## ⭐ Support:

Please give it a Star if you like the project.

## 💡 Notice:

* *The Amazon logo included in top of this page refers only to the [Amazon Product Advertising API V5](https://webservices.amazon.com/paapi5/documentation/index.html)*.
* *All available use case examples located in [/examples](https://github.com/Jakiboy/apaapi/tree/master/examples)*.
