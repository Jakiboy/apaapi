# Apaapi Provider Documentation

This document guide you on how to utilize the Apaapi static Provider.

### ⚡ Initialization:

To use Apaapi Provider, it needs to be imported into your project.

```php

use Apaapi\includes\Provider;

```

### ⚡ Data:

The Apaapi Provider offers various methods to retrieve different types of static data.

#### Retrieving Host, and other static values

```php

$data = Provider::HOST; // Retrieve the host variable
$data = Provider::CONDITION; // Retrieve the condition filter
$data = Provider::SORT; // Retrieve the sort filter
$data = Provider::SHIPPING; // Retrieve the delivery filter

```

#### Retrieving Regions and Countries

```php

$data = Provider::getRegions(); // Retrieve all regions
$data = Provider::getRegion('com'); // Retrieve a specific region
$data = Provider::getCountries(); // Retrieve all countries
$data = Provider::getCountry('com'); // Retrieve a specific country

```

#### Retrieving Locales and Languages

```php

$data = Provider::getLocales(); // Retrieve all locales
$data = Provider::getLanguages(); // Retrieve all languages
$data = Provider::getLanguages('com'); // Retrieve languages for a specific region

```

#### Retrieving Categories

```php

$data = Provider::getCategories(); // Retrieve all categories
$data = Provider::getCategories('com'); // Retrieve categories for a specific region
$data = Provider::getCategoryId('Apps & Games', 'com'); // Retrieve the ID of a specific category in a specific region

```

#### Retrieving Currency Symbols

```php

$data = Provider::getSymbols(); // Retrieve all currency symbols
$data = Provider::getSymbol('USD'); // Retrieve a specific currency symbol

```
