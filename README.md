# NBP Web API Client

This library structure is strongly inspired by [knplabs/github-api](https://github.com/KnpLabs/php-github-api) package.

### Installation

This package is decoupled from any HTTP client thanks to the [HTTPlug](https://httplug.io/).  
Because of that, you have to install packages that provide [psr/http-client-implementation](https://packagist.org/providers/psr/http-client-implementation) and [psr/http-factory-implementation](https://packagist.org/providers/psr/http-factory-implementation).  
For more information visit [HTTPlug for library users](https://docs.php-http.org/en/latest/httplug/users.html).

```
$ composer require kreyu/nbp-web-api
```

### Usage

```php
use Kreyu\NBPWebApi\Client;
use Kreyu\NBPWebApi\Api\ExchangeRates;

$client = new Client();

// Example call
$client->exchangeRates(ExchangeRates::TABLE_TYPE_A)->forCurrency('PLN')->latest(5);
```

#### Changing content type

If you wish to retrieve responses in XML format rather than JSON, you can change it in the client:

```php
use Kreyu\NBPWebApi\Client;
use Kreyu\NBPWebApi\ClientInterface;

$client = new Client();
$client->setContentType(ClientInterface::CONTENT_TYPE_XML);
```

#### Providing HTTP client and factories

By default, the HTTP client builder uses HTTP client and factories provided by the [discovery](https://github.com/php-http/discovery).    
If you have to provide specific [PSR-18](https://www.php-fig.org/psr/psr-18/) compliant client or any [PSR-17](https://www.php-fig.org/psr/psr-17/) factory, you can pass them to the HTTP client builder:

```php
use Kreyu\NBPWebApi\Client;
use Kreyu\NBPWebApi\Http\ClientBuilder as HttpClientBuilder;

/**
 * @var $client         Psr\Http\Client\ClientInterface
 * @var $requestFactory Psr\Http\Message\RequestFactoryInterface
 * @var $streamFactory  Psr\Http\Message\StreamFactoryInterface
 * @var $uriFactory     Psr\Http\Message\UriFactoryInterface
 */

$builder = new HttpClientBuilder(
    $client,
    $requestFactory,
    $streamFactory,
    $uriFactory
);

$client = new Client($builder);
```

#### Caching

To use caching mechanism, provide any [PSR-6](https://www.php-fig.org/psr/psr-6/) compliant item pool to the HTTP client builder:

```php
use Kreyu\NBPWebApi\Client;
use Kreyu\NBPWebApi\Http\ClientBuilder as HttpClientBuilder;

/** @var $pool Psr\Cache\CacheItemPoolInterface */

$builder = new HttpClientBuilder();
$builder->setCache($pool);

$client = new Client($builder);
```

#### Using HTTPlug plugins

You can provide any [HTTPlug plugin](http://docs.php-http.org/en/latest/plugins/) to the HTTP client builder:

```php
use Kreyu\NBPWebApi\Client;
use Kreyu\NBPWebApi\Http\ClientBuilder as HttpClientBuilder;

/** @var $plugin Http\Client\Common\Plugin */

$builder = new HttpClientBuilder();
$builder->addPlugin($plugin);

$client = new Client($builder);
```
