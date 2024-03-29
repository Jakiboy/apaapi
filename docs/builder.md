# Apaapi Builder Documentation

This documentation provides a comprehensive guide on how to use the Apaapi Builder.

### ⚡ Initialization:

To start using the Apaapi Builder, you need to initialize it with your API credentials.

```php

use Apaapi\includes\Builder;

$builder = new Builder('_KEY_', '_SECRET_', '_TAG_', '_LOCALE_');

```

### ⚡ Authorization:

You can verify if your builder credentials are authorized by using the `isAuthorized()` method.

```php

if ( $builder->isAuthorized() ) {
    // ...
}

```

### ⚡ Search:

The **search** methods allows you to search for products using a variety of keywords.

#### Single search

To search for a single product, use the `searchOne()` method.  

* Accept: `String` Keyword, ASIN, ISBN, EAN, URL
* Include: `Filter`

```php

$data = $builder->searchOne('Sony Xperia Pro-I');

```

#### Multiple search

To search for multiple products, use the `search()` method.

* Accept: `String|Array` Keyword, ASIN, ISBN, EAN, URL
* Include: `Filter`, `Order`, `Count`, `Page`

```php

$data = $builder->search('Sony Xperia');

```

#### Search with filters

You can also apply filters to your search.

```php

$data = $builder->search('SONY Xperia 5', 3, 1, [
    'min'       => 500.50,
    'max'       => 800,
    'available' => true,
    'saving'    => 10,
    'sort'      => 'highest',
    'category'  => 'Electronics',
    'brand'     => 'Sony Mobile',
    'title'     => 'SONY Xperia',
    'rank'      => true,
    'reviews'   => 4,
    'condition' => 'new',
    'node'      => '218193031',
    'delivery'  => 'any'
]);

```

#### Search with order

You can specify the order of the search results.

```php

$data = $builder->order(['price' => 'asc'])->search('SONY Xperia 1', 5, 1, [
    'min'      => 400,
    'sort'     => 'highest',
    'category' => 'Electronics',
    'brand'    => 'Sony Mobile',
    'title'    => 'SONY Xperia'
]);

```

### ⚡ Get:

The **get** methods is designed to retrieve product(s) by specific IDs.

#### Single get

To get a single product, use the `getOne()` method.
* Accept: `String` ASIN, ISBN, URL
* Include: `Filter`

```php

$data = $builder->getOne('B09LPB9SQH');

```

#### Multiple get

To get multiple products, use the `get()` method.
* Accept: `String|Array` ASIN, ISBN, URL
* Include: `Filter (Extra)`, `Order`

```php

$data = $builder->get([
    'B09LPB9SQH',
    'b09g8xnw16',
    '1718501870',
    'https://www.amazon.com/dp/B08BHTDLJR/'
]);

```

#### Get with filters

You can also apply filters when getting products.

```php

$data = $builder->get('B09LPB9SQH, 1718501870', [
    'condition' => 'new'
]);

```

#### Get with order

You can specify the order of the retrieved products.

```php

$data = $builder->order(['title' => 'asc'])->get([
    'B09LPB9SQH',
    'b09g8xnw16',
    '1718501870'
]);

```

### ⚡ Variation:

The **variation** methods are designed to get product variations.

#### Get variation

The `getVariation()` method retrieve variations by specific IDs.

* Accept: `String` ASIN, ISBN, URL
* Include: `Filter`, `Order`, `Count`, `Page`

```php

$data = $builder->getVariation('B09G8XNW16');

```

#### Search variation

The `searchVariation()` method retrieve variations using a variety of keywords.

* Accept: `String` Keyword, ASIN, ISBN, EAN, URL
* Include: `Filter`, `Order`, `Count`, `Page`
* Note: *This methode is expensive*

```php

$data = $builder->searchVariation('Apple iPhone 13 Mini Silicone');

```

### ⚡ Bestseller:

The **bestseller** methods are designed to get bestseller product(s).

#### Get bestseller

The `getBestseller()` method retrieve bestseller by specific IDs.

* Accept: `String` NodeId, URL (node)
* Include: `Filter`, `Order`, `Count`, `Page`
* Note: *This methode is expensive & unstable*

```php

$data = $builder->getBestseller('1455795031', 3, 1, [
    'category' => 'Electronics'
]);

```

#### Search bestseller

The `searchBestseller()` method retrieve bestseller using a variety of keywords.

* Accept: `String` Keyword, ASIN, ISBN, EAN, URL
* Include: `Filter`, `Order`, `Count`, `Page`
* Note: *This methode is expensive & unstable*

```php

$data = $builder->searchBestseller('Amazon Fire TV', 5, 1, [
    'category' => 'Electronics'
]);

```

### ⚡ Newest:

The `getNewest()` method is designed to get the newest products.

* Accept: `String` Keyword, ASIN, ISBN, EAN, URL
* Include: `Filter`, `Order`, `Count`, `Page`
* Note: *This methode is unstable*

```php

$data = $builder->getNewest('Amazon Fire TV', 3, 1, [
    'category' => 'Electronics'
]);

```

### ⚡ Category:

This section covers operations related to product categories (**Search Index**).

#### Get category

The `getCategory()` method allows you to get product category using a variety of keywords.

* Accept: `String` Keyword, ASIN, ISBN, EAN, URL
* Include: `Filter`

```php

$data = $builder->getCategory('B09G8XNW16');

```

#### Search category

The `getCategory()` method allows you to get a detailed product category[] using keywords.

* Accept: `String` Keyword, ASIN, ISBN, EAN, URL
* Include: `Filter`

```php

$data = $builder->searchCategory('B09G8XNW16');

```

### ⚡ Node:

This section covers operations related to nodes (Categories).

#### Get node

The `getNode()` method allows you to retrieve a node using specific IDs.

* Accept: `String` NodeId, URL

```php

$data = $builder->getNode('218193031');

```

#### Search node

The `searchNode()` method allows you to search for a node using a variety of keywords.

* Accept: `String` NodeId, Keyword, ASIN, URL
* Include: `Filter`
* Note: *This methode is expensive*

```php

$data = $builder->searchNode('218193031', [
    'category' => 'Electronics'
]);

```

### ⚡ Convert:

This section covers operations related to converting keywords.

#### Convert to EAN

The `toEAN()` method allows you to convert keywords to EAN.

* Accept: `String` Keyword, ASIN, ISBN, URL
* Include: `Filter`

```php

$data = $builder->toEAN('B09G8XNW16');

```

#### Convert to ASIN

The `toASIN()` method allows you to convert keywords to ASIN or ISBN.

* Accept: `String` Keyword, EAN, URL
* Include: `Filter`

```php

$data = $builder->toASIN('0194252780657');

```

#### Convert to NodeId

The `toNodeId()` method allows you to convert keywords to NodeId.

* Accept: `String` Keyword, ASIN, ISBN, EAN, URL
* Include: `Filter`

```php

$data = $builder->toNodeId('B09G8XNW16');

```

#### Convert to RootId

The `toRootId()` method allows you to convert keywords to root NodeId.

* Accept: `String` Keyword, ASIN, ISBN, EAN, URL
* Include: `Filter`

```php

$data = $builder->toRootId('B09G8XNW16');

```

### ⚡ Cart:

This section covers operations related to the Amazon cart.

#### Get cart

The `getCart()` method allows you to generate an "Add to Cart" URL using specific IDs,  
You can specify the quantity for each individual ID.

* Accept: `String|Array` ASIN, ISBN, URL
* Note: No authentication is required for this operation.

```php

$data = $builder->getCart([
    'B09LPB9SQH' => 3,
    'B09G8XNW16' => 1
]);

```

### ⚡ Rating:

This section covers operations related to product ratings (**Legacy**).

#### Get rating

The `getRating()` method allows you to get the average rating and count for a product using specific IDs.

* Accept: `String` ASIN, ISBN, URL
* Note: No authentication is required for this operation.

```php

$data = $builder->getRating('B09G8XNW16');

```

#### Search rating

The `searchRating()` method allows you to search for the rating of a product using many keywords.

* Accept: `String` Keyword, ASIN, ISBN, EAN, URL

```php

$data = $builder->searchRating('Sony Xperia Pro');

```

### ⚡ Filters:

This section covers the filters that can be applied when performing a **search** operations.  
The **get** operations only accept extra parameters.

#### Search parameters

* `sort`: Sort order [ featured | newest | relevance | reviews | highest | lowest ]. (string)
* `condition`: Condition [ any | new | used | refurbished | collectible ]. (string)
* `delivery`: Delivery type [ global | free | fulfilled | prime ]. (string)
* `category`: Category [ see provider categories ]. (string)
* `min`: Minimum price. (float|integer|string)
* `max`: Maximum price. (float|integer|string)
* `available`: Availability. (boolean)
* `saving`: Minimum saving percentage. (integer|string)
* `brand`: Brand. (string)
* `title`: Title. (string)
* `rank`: Include rank in results. (boolean)
* `reviews`: Minimum number of reviews. (integer|string)
* `node`: Node Id. (string)

#### Extra parameters

* `lang`: Language [ see provider languages ]. (string)
* `currency`: Currency [ see provider currencies ]. (string)
* `merchant`: Merchant name. (string)
* `marketplace`: Marketplace URL. (string)
* `actor`: Actor name. (string)
* `artist`: Artist name. (string)
* `author`: Author name. (string)

### ⚡ Geotargeting:

The Geotargeting feature allows you to redirect the product URL based on the visitor's geographical location. This feature is useful when you want to serve different content to users based on their location. It uses the `redirect()` method, where the `target` parameter matches the detected country code.

#### Parameters

The **redirect** method accepts an array with the following keys:

* `code`: The country code obtained from an external API.
* `target`: An array mapping country codes to Amazon affiliate tags.
* `api`: An array containing information about the external IP and GEO APIs.

#### Using external country code

In this example, the country code is obtained from an external API like [MaxMind GeoIP](https://dev.maxmind.com/geoip).

```php

$data = $builder->redirect([
    'code'   => 'us', // Country code from API
    'target' => [
        'us' => 'affiliate-tag-us',
        'es' => 'affiliate-tag-es',
        'fr' => 'affiliate-tag-fr',
        'ca' => 'affiliate-tag-ca'
    ]
])->searchOne('Sony Xperia Pro');

```

#### Using external GEO API

In this example, an external GEO API is used for IP detection. Note that internal IP detection may not always be reliable.

```php

$data = $builder->redirect([
    'api' => [
        'geo' => [
            'address' => 'http://ip-api.com/json/{ip}',
            'param'   => 'countryCode'
        ]
    ],
    'target' => [
        'us' => 'affiliate-tag-us'
    ]
])->searchOne('Sony Xperia Pro');

```

#### External GEO & IP APIs

In this example, both external GEO and IP APIs are used for better accuracy.

```php

$data = $builder->redirect([
    'api' => [
        'ip'  => [
            'address' => 'https://api.ipify.org/?format=json',
            'param'   => 'ip'
        ],
        'geo' => [
            'address' => 'http://ip-api.com/json/{ip}',
            'param'   => 'countryCode'
        ]
    ],
    'target' => [
        'us' => 'affiliate-tag-us'
    ]
])->searchOne('Sony Xperia Pro');

```

Remember to replace 'affiliate-tag-us' etc. with your actual Amazon affiliate tags.

#### Advanced usage

You can customize the behavior of the Geotargeting using the following methods before calling the `redirect()` method.

```php

// Redirect 404 page to search page
Geotargeting::redirectNotFound();

// Set custom visitor key (Caching)
Geotargeting::setVisitorKey('visitor-id');

// Exclude IPs from geotargeting
Geotargeting::setException(['127.0.0.1']);

```

### ⚡ Error handling:

The Builder class provides methods to handle HTTP errors that may occur during API calls or HTTP client operations.  
Including semantic errors with HTTP code `200`.

#### Check error

The `hasError()` method returns a boolean indicating whether an error has occurred.

```php

if ( $builder->hasError() ) {
    // ...
}

```

#### Get error

If an error has occurred, you can retrieve the error message using the `getError()` method. 

```php

if ( $builder->hasError() ) {
    $error = $builder->getError();
}

```

### 💡 Note:

* **Keyword** : Keywords like ASIN differ from marktplace to other, Whiche can cause 404.
* **Filter** : Performing product search with a keyword without using any filters may yield undesired results.
* **Expensive** : The operation may consume more resources or take longer to execute.
* **Unstable** : The operation may yield unstable results.
