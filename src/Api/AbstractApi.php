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

namespace Kreyu\NBPWebApi\Api;

use Kreyu\NBPWebApi\ClientInterface;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Sebastian Wróblewski <kontakt@swroblewski.pl>
 */
abstract class AbstractApi implements ApiInterface
{
    protected const DATE_FORMAT = 'Y-m-d';

    private const URI_PREFIX = '/api';

    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @param  string $uri
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    protected function get(string $uri): ResponseInterface
    {
        return $this->client->getHttpClient()->get($this->prepareUri($uri));
    }

    private function prepareUri(string $uri): string
    {
        return sprintf('%s/%s', self::URI_PREFIX, $uri);
    }
}
