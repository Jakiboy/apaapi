# APAAPI

<img src="https://raw.githubusercontent.com/Jakiboy/apaapi/master/amazon.svg" width="100" alt="Amazon Product Advertising API PHP">

Amazon Product Advertising API V5.0 (**Without Amazon SDK**).  
This repository contains a PHP Lightweight Wrapper Lib (**v1.1.0**), Allows you accessing the [Amazon Product Advertising API V5.0](https://webservices.amazon.com/paapi5/documentation/index.html) from your PHP App, Quickly & easily!

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

## 🔨 Upgrade :

**See changes before migrate**: 

This version includes:  

* Error reporting (Including errors with **status 200** & **HTTP Client Errors**), [More](https://webservices.amazon.com/paapi5/documentation/troubleshooting/processing-of-errors.html#processing-of-errors). 
* Response parsing (*object/array/serialized*).
* Throws exceptions if Locale (*Region/TLD*) is invalid, [More](https://webservices.amazon.fr/paapi5/documentation/locale-reference.html). 

And had many improvements: 


* Uses Default [Ressources](https://webservices.amazon.fr/paapi5/documentation/resources.html) for each [Operation](https://webservices.amazon.fr/paapi5/documentation/operations.html). 
* Clean ecosystem.
* [Extendable HTTP Client](#advanced-custom-http-request-client).


## ⚡ Getting Started:

### Variables (Basics):

* "{Your-partner-tag}" : From your Amazon Associates (*your locale*), [More](https://webservices.amazon.com/paapi5/documentation/troubleshooting/sign-up-as-an-associate.html). 
* "{Your-secrect-key}" : From your Amazon Associates (*your locale*), [More](https://affiliate-program.amazon.com/help/node/topic/GTPNVFFUV2GQ8AZV). 
* "{Your-key-id}" : From your Amazon Associates (*your locale*), [More](https://affiliate-program.amazon.com/help/node/topic/GTPNVFFUV2GQ8AZV). 
* "{Your-keywords}" : What you are looking for (*Products*), [More](https://webservices.amazon.com/paapi5/documentation/search-items.html). 
* "{Your-region}" : **TLD** of the target to which you are sending requests (*com/fr/de*), [Get TLD](https://webservices.amazon.com/paapi5/documentation/common-request-parameters.html#host-and-region). 
* "{ASIN}" : Amazon Standard Identification Number (*your locale*), [More](https://webservices.amazon.com/paapi5/documentation/get-items.html#ItemLookup-rp). 


### Quickstart:


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
$operation->setPartnerTag('{Your-partner-tag}')->setKeywords('{Your-keywords}');

// (2) Prapere Request
$request = new Request('{Your-key-id}','{Your-secrect-key}');
$request->setLocale('{Your-region}')->setPayload($operation);

// (3) Get Response
$response = new Response($request);
echo $response->get(); // JSON ready for parsing

```

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
$operation->setPartnerTag('{Your-partner-tag}')
->setItemIds(['{ASIN}']); // Array|String

// SearchItems
$operation = new SearchItems();
$operation->setPartnerTag('{Your-partner-tag}')
->setKeywords('{Your-keywords}'); // Array|String

// GetVariations
$operation = new GetVariations();
$operation->setPartnerTag('{Your-partner-tag}')
->setASIN('{ASIN}'); // String

// GetBrowseNodes
$operation = new GetBrowseNodes();
$operation->setPartnerTag('{Your-partner-tag}')
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
$operation->setPartnerTag('{Your-partner-tag}')->setKeywords('{Your-keywords}')
->setResources(['Images.Primary.Small','ItemInfo.Title','Offers.Listings.Price','Offers.Listings.SavingBasis']);

```

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
$operation->setPartnerTag('{Your-partner-tag}')->setItemIds('{ASIN}');

// Prapere Request
$request = new Request('{Your-key-id}','{Your-secrect-key}');
$request->setLocale('{your-region}')->setPayload($operation);

// Set Custom Client After Payload
$request->setClient(
	new MyRequestClient($request->getEndpoint(), $request->getParams())
);

// Get Response
$response = new Response($request);
echo $response->get(); // JSON ready for parsing

```
### Advanced (Response Type Helper) :

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
### Advanced (Response Errors) :

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


## Contributing:

Please read [CONTRIBUTING.md](https://github.com/Jakiboy/apaapi/blob/master/CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning:

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/Jakiboy/apaapi/tags). 

## Authors:

* **Jihad Sinnaour** - [Jakiboy](https://github.com/Jakiboy) (*Initial work*)

See also the full list of [contributors](https://github.com/Jakiboy/apaapi/contributors) who participated in this project. Any suggestions (Pull requests) are welcome!

## License:

This project is licensed under the MIT License - see the [LICENSE.txt](https://github.com/Jakiboy/apaapi/blob/master/LICENSE.txt) file for details.

## ⭐ Support:

Please give it a Star if you like the project.

## 💡 Notice:

* *The Amazon logo included in top of this page refers only to the [Amazon Product Advertising API V5](https://webservices.amazon.com/paapi5/documentation/index.html)*.
* *All available use case examples located in [/examples](https://github.com/Jakiboy/apaapi/tree/master/examples)*.
