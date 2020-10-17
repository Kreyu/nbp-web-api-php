<?php

/*
 * This file is part of the NBPWebApi package.
 *
 * (c) Sebastian Wróblewski <kontakt@swroblewski.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Kreyu\NBPWebApi;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Kreyu\NBPWebApi\Http\ClientBuilder;
use Webmozart\Assert\Assert;

/**
 * @author Sebastian Wróblewski <kontakt@swroblewski.pl>
 */
class Client implements ClientInterface
{
    public const CONTENT_TYPE_JSON = 'application/json';
    public const CONTENT_TYPE_XML = 'application/xml';

    private const BASE_URI = 'http://api.nbp.pl/api';

    private $httpClientBuilder;

    public function __construct(ClientBuilder $httpClientBuilder = null, ?string $contentType = null)
    {
        $this->httpClientBuilder = $httpClientBuilder ?? new ClientBuilder();

        $this->setBaseUri(self::BASE_URI);

        if (null !== $contentType) {
            $this->setContentType($contentType);
        }
    }

    public function getHttpClient(): HttpMethodsClientInterface
    {
        return $this->httpClientBuilder->getClient();
    }

    public function setBaseUri(string $uri): void
    {
        Assert::notEmpty($uri);

        $this->httpClientBuilder->removePlugin(AddHostPlugin::class);
        $this->httpClientBuilder->addPlugin(new AddHostPlugin(
            $this->httpClientBuilder->getUriFactory()->createUri($uri)
        ));
    }

    public function setContentType(string $contentType): void
    {
        Assert::oneOf($contentType, [self::CONTENT_TYPE_JSON, self::CONTENT_TYPE_XML]);

        $this->httpClientBuilder->removePlugin(HeaderDefaultsPlugin::class);
        $this->httpClientBuilder->addPlugin(new HeaderDefaultsPlugin([
            'Accept' => $contentType,
        ]));
    }

    public function exchangeRates(string $tableType): ExchangeRates
    {
        return new ExchangeRates($this, $tableType);
    }

    public function goldPrices(): GoldPrices
    {
        return new GoldPrices($this);
    }
}
