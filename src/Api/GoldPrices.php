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
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * @author Sebastian Wróblewski <kontakt@swroblewski.pl>
 */
class GoldPrices extends AbstractApi
{
    /**
     * Current gold price quotation.
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function all(): ResponseInterface
    {
        return $this->get('cenyzlota');
    }

    /**
     * Series of latest {count} gold price quotations.
     *
     * @param  int $count
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function latest(int $count): ResponseInterface
    {
        return $this->get(sprintf('cenyzlota/%d', $count));
    }

    /**
     * Gold price quotation published today (or lack of data).
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function today(): ResponseInterface
    {
        return $this->get('cenyzlota/today');
    }

    /**
     * Gold price quotation published on {date} (or lack of data).
     *
     * @param  DateTimeInterface $date
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function inDate(DateTimeInterface $date): ResponseInterface
    {
        return $this->get(sprintf('cenyzlota/%s', $date->format(self::DATE_FORMAT)));
    }

    /**
     * Gold price quotations published from {startDate} to {endDate} (or lack of data).
     *
     * @param  DateTimeInterface $startDate
     * @param  DateTimeInterface $endDate
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function betweenDates(DateTimeInterface $startDate, DateTimeInterface $endDate): ResponseInterface
    {
        return $this->get(sprintf(
            'cenyzlota/%s/%s',
            $startDate->format(self::DATE_FORMAT),
            $endDate->format(self::DATE_FORMAT)
        ));
    }
}