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

namespace Kreyu\NBPWebApi;

use Http\Client\Common\HttpMethodsClientInterface;

/**
 * @author Sebastian Wróblewski <kontakt@swroblewski.pl>
 */
interface ClientInterface
{
    public const CONTENT_TYPE_JSON = 'application/json';

    public const CONTENT_TYPE_XML = 'application/xml';

    public function getHttpClient(): HttpMethodsClientInterface;

    public function setBaseUri(string $uri): void;

}
