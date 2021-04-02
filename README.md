# APAAPI

<img src="https://raw.githubusercontent.com/Jakiboy/apaapi/master/amazon.svg" width="100" alt="Amazon Product Advertising API PHP">

Amazon Product Advertising API 5.0 [Without Amazon SDK] v1.0.9.
This repository contains a PHP Wrapper Lib that allows you accessing the [Amazon Product Advertising API](https://webservices.amazon.com/paapi5/documentation/index.html) from your PHP App, Quickly & easily.

## Installing :

```
composer require jakiboy/apaapi
```

## Upgrade :

:grey_exclamation: **See changes before migrate** : This version includes Error reporting (All & com.amazon.paapi#ErrorData with status 200 & Client errors), Response parsing, Throws exceptions if locale (Region) is invalid or ressources are invalid (Instead of no response), And had many improvements : Use default ressources for each operation, Clean ecosystem, Extendable HTTP Client.

## Getting Started :

### Quickstart :

```php

use Apaapi\operations\SearchItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;

/**
 * @see With Three Easy Steps,
 * You can Achieve Connection to Amazon Product Advertising API
 */

// (1) Set Operation
$operation = new SearchItems();
$operation->setPartnerTag('{Your-partner-tag}')->setKeywords('{Your-keywords}');

// (2) Prapere Request
$request = new Request('{Your-key-id}','{Your-secrect-key}');
$request->setLocale('{your-region}')->setPayload($operation);

// (3) Get Response
$response = new Response($request);
echo $response->get(); // JSON ready for parsing

```

### Operations :

```php

use Apaapi\operations\GetItems;
use Apaapi\operations\SearchItems;
use Apaapi\operations\GetVariations;
use Apaapi\operations\GetBrowseNodes;

/**
 * @see 4 Operations
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

### Advanced (Custom ressources) :

```php

/**
 * @see Using setResources() method to set custom ressources,
 * Instead of default ressources.
 * This can improve response time.
 */

// Set Operation
$operation->setPartnerTag('{Your-partner-tag}')->setKeywords('{Your-keywords}')
->setResources(['Images.Primary.Small','ItemInfo.Title','Offers.Listings.Price']);

```

### Advanced (Custom Request Client) :

```php

use Apaapi\operations\GetItems;
use Apaapi\lib\Request;
use Apaapi\lib\Response;
use Apaapi\includes\RequestClient;

/**
 * @see Extending RequestClient: Allows Overriding Curl Settings,
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
 * @see Helps generating quick decoded response
 * @param object|array|serialized
 */

// Get Response
$response = new Response($request, new ResponseType('array'));
return $response->get(); // Array ready to be used

```

```php

use Apaapi\includes\ResponseType;

/**
 * @see Helps parsing response
 * @param Response::PARSE
 */

// Get Response
$response = new Response($request, new ResponseType('object'), Response::PARSE);
return $response->get(); // Object ready to be used

```
### Advanced (Response Errors) :

```php

/**
 * @see Error catching
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


## Contributing :

Please read [CONTRIBUTING.md](https://github.com/Jakiboy/apaapi/blob/master/CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning :

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/Jakiboy/apaapi/tags). 

## Authors :

* **Jihad Sinnaour** - [Jakiboy](https://github.com/Jakiboy) (*Initial work*)
* **Kay Marquardt** - [gnadelwartz](https://github.com/gnadelwartz)

See also the full list of [contributors](https://github.com/Jakiboy/apaapi/contributors) who participated in this project.

## License :

This project is licensed under the MIT License - see the [LICENSE.txt](https://github.com/Jakiboy/apaapi/blob/master/LICENSE.txt) file for details

## Notice : 

* *The Amazon logo included in top of this page refers only to the [Amazon Product Advertising API](https://webservices.amazon.com/paapi5/documentation/index.html)*
* *All available use case examples located in /examples*
