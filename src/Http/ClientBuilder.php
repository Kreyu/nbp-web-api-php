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

namespace Kreyu\NBPWebApi\Http;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin;
use Http\Client\Common\Plugin\CachePlugin;
use Http\Client\Common\PluginClientFactory;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

/**
 * @author Sebastian Wróblewski <kontakt@swroblewski.pl>
 */
class ClientBuilder
{
    private $client;
    private $requestFactory;
    private $streamFactory;
    private $uriFactory;
    private $cache;
    private $plugins = [];

    public function __construct(
        ClientInterface $client = null,
        RequestFactoryInterface $requestFactory = null,
        StreamFactoryInterface $streamFactory = null,
        UriFactoryInterface $uriFactory = null
    ) {
        $this->client = $client ?? Psr18ClientDiscovery::find();
        $this->requestFactory = $requestFactory ?? Psr17FactoryDiscovery::findRequestFactory();
        $this->streamFactory = $streamFactory ?? Psr17FactoryDiscovery::findStreamFactory();
        $this->uriFactory = $uriFactory ?? Psr17FactoryDiscovery::findUriFactory();
    }

    public function getClient(): HttpMethodsClientInterface
    {
        $plugins = $this->plugins;

        if (null !== $this->cache) {
            $plugins[] = $this->cache;
        }

        return new HttpMethodsClient(
            (new PluginClientFactory())->createClient($this->client, $plugins),
            $this->requestFactory,
            $this->streamFactory
        );
    }

    public function getRequestFactory(): RequestFactoryInterface
    {
        return $this->requestFactory;
    }

    public function getStreamFactory(): StreamFactoryInterface
    {
        return $this->streamFactory;
    }

    public function getUriFactory(): UriFactoryInterface
    {
        return $this->uriFactory;
    }

    public function setCache(CacheItemPoolInterface $pool, array $config = []): void
    {
        $this->cache = CachePlugin::clientCache($pool, $this->streamFactory, $config);
    }

    public function addPlugin(Plugin $plugin): void
    {
        $this->plugins[get_class($plugin)] = $plugin;
    }

    public function removePlugin(string $pluginFQCN): void
    {
        unset($this->plugins[$pluginFQCN]);
    }
}
