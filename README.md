# APAAPI

<img src="https://raw.githubusercontent.com/Jakiboy/apaapi/dev/banner.png" width="100%" alt="Amazon Product Advertising API PHP">

Amazon Product Advertising API V5.0 (**Without Amazon SDK**).  
This repository contains a lightweight PHP (155 KB) wrapper library,  
Easily access the [Amazon Product Advertising API V5.0](https://webservices.amazon.com/paapi5/documentation/index.html) from your app.

-- Become an Amazon Affiliate With PHP --

## 🔧 Installing:

#### Using Composer:

```
composer require jakiboy/apaapi
```

#### Without Composer?


* **1** - [Download repository ZIP](https://github.com/Jakiboy/apaapi/archive/refs/heads/dev.zip) (*Latest version*).
* **2** - Extract ZIP (*apaapi-dev*).
* **3** - Include this lines beelow (*apaapi self-autoloader*).

```
include('apaapi-dev/src/Autoloader.php');
\apaapi\Autoloader::init();
```

* **4** - You can now use the [Quickstart examples](#quickstart).

## 💡 Upgrade :

**See changes before migrate**: 

This version includes:  

* **Request Builder** (Easier way to fetch data).
* **Response Normalizer** (Normalize response items).
* **Search Filters** (Using builder).
* **Geotargeting** (Automatically redirect links based on the visitor's region).
* **Rating Stars** (Lagacy).
* **Keyword Converter** (ASIN, ISBN, EAN, Node, Root).
* **Caching System** (Basic built-in cache to reduce API calls).

[Full Changelog](#).  
[Previous Version](/Jakiboy/apaapi/tree/1.1.7).

## ⚡ Getting Started:

### Variables:

* "\_KEY\_" : From your Amazon Associates (*your locale*), [More](https://affiliate-program.amazon.com/help/node/topic/GTPNVFFUV2GQ8AZV). 
* "\_SECRET\_" : From your Amazon Associates (*your locale*), [More](https://affiliate-program.amazon.com/help/node/topic/GTPNVFFUV2GQ8AZV). 
* "\_TAG\_" : From your Amazon Associates (*your locale*), [More](https://webservices.amazon.com/paapi5/documentation/troubleshooting/sign-up-as-an-associate.html). 
* "\_LOCALE\_" : **TLD** of the target marketplace to which you are sending requests (*com/fr/co.jp*), [Get TLD](https://webservices.amazon.com/paapi5/documentation/common-request-parameters.html#host-and-region). 
* "\_KEYWORDS\_" : What you are looking for (*Products*), [More](https://webservices.amazon.com/paapi5/documentation/search-items.html). 
* "\_ASIN\_" : Accepts (ISBN), Amazon Standard Identification Number (*your locale*), [More](https://webservices.amazon.com/paapi5/documentation/get-items.html#ItemLookup-rp). 
* "\_NODE\_" : Browse Node ID (*your locale*), [More](https://webservices.amazon.com/paapi5/documentation/use-cases/organization-of-items-on-amazon/browse-nodes/browse-node-properties.html#browse-node-ids). 

### Quickstart:

Using Apaapi builder is recommended.

```php

/**
 * @see Use Composer, 
 * Or include Apaapi Autoloader Here.
 */

use Apaapi\includes\Builder;

// (1) Init request builder
$builder = new Builder('_KEY_', '_SECRET_', '_TAG_', '_LOCALE_');

// (2) Get response (Search)
$data = $builder->searchOne('Sony Xperia Pro-I'); // Normalized array

```

* *See advanced builder usage at [/docs/builder.md](https://github.com/Jakiboy/apaapi/tree/dev/docs/builder.md)*

### Basic (Search):

Extensible search method.

```php

use Apaapi\operations\SearchItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;

// (1) Set operation
$operation = new SearchItems();
$operation->setPartnerTag('_TAG_')->setKeywords('_KEYWORDS_');

// (2) Prapere request
$request = new Request('_KEY_', '_SECRET_');
$request->setLocale('_LOCALE_')->setPayload($operation);

// (3) Get response
$response = new Response($request);
$data = $response->get(); // Array

```

* *See all available TLDs used by setLocale() at [/docs/tlds.md](https://github.com/Jakiboy/apaapi/tree/dev/docs/tlds.md)*


### Basic (Get):

Extensible get method.

```php

use Apaapi\operations\GetItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;

// Set operation
$operation = new GetItems();
$operation->setPartnerTag('_TAG_')->setItemIds(['_ASIN_']);

// Prapere request
$request = new Request('_KEY_', '_SECRET_');
$request->setLocale('_LOCALE_')->setPayload($operation);

// Get response
$response = new Response($request);
$data = $response->get(); // Array

```

### Operations:

All available operations.

```php

use Apaapi\operations\GetItems;
use Apaapi\operations\SearchItems;
use Apaapi\operations\GetVariations;
use Apaapi\operations\GetBrowseNodes;

// (1) GetItems
$operation = new GetItems();
$operation->setPartnerTag('_TAG_');
$operation->setItemIds(['_ASIN_']); // Array

// (2) SearchItems
$operation = new SearchItems();
$operation->setPartnerTag('_TAG_');
$operation->setKeywords('_KEYWORDS_'); // String

// (3) GetVariations
$operation = new GetVariations();
$operation->setPartnerTag('_TAG_');
$operation->setASIN('_ASIN_'); // String

// (4) GetBrowseNodes
$operation = new GetBrowseNodes();
$operation->setPartnerTag('_TAG_');
$operation->setBrowseNodeIds(['_NODE_']); // Array

```

### Ressources:

Optimize response time by setting only the needed resources.

```php

use Apaapi\operations\SearchItems;

// Set Operation
$operation = new SearchItems();
$operation->setPartnerTag('_TAG_')->setKeywords('_KEYWORDS_');

// Set Ressources (3)
$operation->setResources(['Images.Primary.Small', 'ItemInfo.Title', 'Offers.Listings.Price']);

```

* *See all available ressources used by setResources() at [/docs/ressources.md](https://github.com/Jakiboy/apaapi/tree/dev/docs/ressources.md)*

### Cart:

Get affiliate cart URL.

```php

use Apaapi\lib\Cart;

// Init Cart
$cart = new Cart();
$cart->setLocale('_LOCALE_')->setPartnerTag('_TAG_');

// Get Response
$data = $cart->set(['_ASIN_' => 3]); // String

```

### Rating:

Get product average rating and count (Legacy).

```php

use Apaapi\includes\Rating;

// Init Rating
$rating = new Rating('_ASIN_', '_LOCALE_');

// Get Response
$data = $rating->get(); // Array

```

## Contributing:

Please read [CONTRIBUTING.md](https://github.com/Jakiboy/apaapi/blob/dev/CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning:

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/Jakiboy/apaapi/tags). 

## Authors:

* **Jihad Sinnaour** - [Jakiboy](https://github.com/Jakiboy) (*Initial work*)

See also the full list of [contributors](https://github.com/Jakiboy/apaapi/contributors) who participated in this project. Any suggestions (Pull requests) are welcome!

## License:

This project is licensed under the MIT License - see the [LICENSE](https://github.com/Jakiboy/apaapi/blob/dev/LICENSE) file for details.

## ⭐ Support:

Please give it a Star if you like the project.

## 💡 Notice:

* *The Amazon logo included in top of this page refers only to the [Amazon Product Advertising API V5](https://webservices.amazon.com/paapi5/documentation/index.html)*.
* *All available use case examples located in [/examples](https://github.com/Jakiboy/apaapi/tree/dev/examples)*.
