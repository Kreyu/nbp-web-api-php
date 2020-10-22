<?php

/*
 * This file is part of the NBP Web API Client package.
 *
 * (c) Sebastian Wróblewski <kontakt@swroblewski.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Kreyu\NBPWebApi\Tests;

use Http\Client\Common\Plugin\AddHostPlugin;
use Http\Client\Common\Plugin\HeaderDefaultsPlugin;
use Kreyu\NBPWebApi\Client;
use Kreyu\NBPWebApi\ClientInterface;
use Kreyu\NBPWebApi\Http;
use Psr\Http\Message\UriInterface;

/**
 * @author Sebastian Wróblewski <kontakt@swroblewski.pl>
 */
class ClientTest extends TestCase
{
    public function testChangingBaseUri(): void
    {
        $builder = new Http\ClientBuilder();

        $client = new Client($builder);
        $client->setBaseUri('http://test.wbp.pl');

        $plugins = $this->getInaccessibleProperty($builder, 'plugins');

        self::assertArrayHasKey(AddHostPlugin::class, $plugins);
        self::assertInstanceOf(AddHostPlugin::class, $plugins[AddHostPlugin::class]);

        /** @var UriInterface $uri */
        $uri = $this->getInaccessibleProperty($plugins[AddHostPlugin::class], 'host');

        self::assertInstanceOf(UriInterface::class, $uri);
        self::assertSame((string) $uri, 'http://test.wbp.pl');
    }

    public function testSettingContentTypeJson(): void
    {
        $this->executeTestChangingContentType(ClientInterface::CONTENT_TYPE_JSON);
    }

    public function testSettingContentTypeXml(): void
    {
        $this->executeTestChangingContentType(ClientInterface::CONTENT_TYPE_XML);
    }

    private function executeTestChangingContentType(string $contentType): void
    {
        $builder = new Http\ClientBuilder();

        $client = new Client($builder);
        $client->setContentType($contentType);

        $plugins = $this->getInaccessibleProperty($builder, 'plugins');

        self::assertArrayHasKey(HeaderDefaultsPlugin::class, $plugins);
        self::assertInstanceOf(HeaderDefaultsPlugin::class, $plugins[HeaderDefaultsPlugin::class]);

        $headers = $this->getInaccessibleProperty($plugins[HeaderDefaultsPlugin::class], 'headers');

        self::assertArrayHasKey('Accept', $headers);
        self::assertSame($headers['Accept'], $contentType);
    }
}
