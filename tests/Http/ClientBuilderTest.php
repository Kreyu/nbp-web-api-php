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

namespace Kreyu\NBPWebApi\Tests\Http;

use Http\Client\Common\Plugin;
use Http\Client\Common\Plugin\CachePlugin;
use Kreyu\NBPWebApi\Http;
use Kreyu\NBPWebApi\Tests\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Sebastian Wróblewski <kontakt@swroblewski.pl>
 */
class ClientBuilderTest extends TestCase
{
    public function testAddingPluginShouldInvalidateHttpClient(): void
    {
        $builder = new Http\ClientBuilder();

        $client = $builder->getClient();

        $builder->addPlugin($this->createMock(Plugin::class));

        self::assertNotSame($client, $builder->getClient());
    }

    public function testRemovingPluginShouldInvalidateHttpClient(): void
    {
        $builder = new Http\ClientBuilder();

        $plugin = $this->createMock(Plugin::class);

        $builder->addPlugin($plugin);

        $client = $builder->getClient();

        $builder->removePlugin(get_class($plugin));

        self::assertNotSame($client, $builder->getClient());
    }

    public function testChangingCacheShouldUseGivenItemPool(): void
    {
        /** @var CacheItemPoolInterface|MockObject $cache */
        $cache = $this->createMock(CacheItemPoolInterface::class);

        $builder = new Http\ClientBuilder();
        $builder->setCache($cache);

        $cachePlugin = $this->getInaccessibleProperty($builder, 'cache');

        self::assertInstanceOf(CachePlugin::class, $cachePlugin);

        $cachePluginPool = $this->getInaccessibleProperty($cachePlugin, 'pool');

        self::assertInstanceOf(CacheItemPoolInterface::class, $cachePluginPool);
    }
}
