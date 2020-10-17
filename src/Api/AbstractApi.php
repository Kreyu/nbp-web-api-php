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

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Sebastian Wróblewski <kontakt@swroblewski.pl>
 */
abstract class AbstractApi implements ApiInterface
{
    protected const DATE_FORMAT = 'Y-m-d';

    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Send a request using the "GET" method.
     *
     * @param  string $uri
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    protected function get(string $uri): ResponseInterface
    {
        return $this->client->getHttpClient()->get($uri);
    }
}
