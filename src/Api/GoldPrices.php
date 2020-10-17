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

use DateTimeInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Sebastian Wróblewski <kontakt@swroblewski.pl>
 */
class GoldPrices extends AbstractApi
{
    public function all(): ResponseInterface
    {
        return $this->get('cenyzlota');
    }

    public function latest(int $count): ResponseInterface
    {
        return $this->get(sprintf('cenyzlota/%d', $count));
    }

    public function today(): ResponseInterface
    {
        return $this->get('cenyzlota/today');
    }

    public function inDate(DateTimeInterface $date): ResponseInterface
    {
        return $this->get(sprintf('cenyzlota/%s', $date->format(self::DATE_FORMAT)));
    }

    public function betweenDates(DateTimeInterface $startDate, DateTimeInterface $endDate): ResponseInterface
    {
        return $this->get(sprintf(
            'cenyzlota/%s/%s',
            $startDate->format(self::DATE_FORMAT),
            $endDate->format(self::DATE_FORMAT)
        ));
    }
}