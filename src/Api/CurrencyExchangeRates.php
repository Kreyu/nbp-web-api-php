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
use Webmozart\Assert\Assert;

/**
 * @author Sebastian Wróblewski <kontakt@swroblewski.pl>
 */
class CurrencyExchangeRates extends ExchangeRates
{
    private $currencyCode;

    /**
     * @param  ClientInterface $client
     * @param  string          $tableType    Table type (A, B, or C)
     * @param  string          $currencyCode Three letter currency code (ISO 4217 standard)
     */
    public function __construct(ClientInterface $client, string $tableType, string $currencyCode)
    {
        parent::__construct($client, $tableType);

        Assert::notEmpty($currencyCode);
        Assert::maxLength($currencyCode, 3);

        $this->currencyCode = $currencyCode;
    }

    /**
     * Current table of exchange rates from the selected table type.
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function all(): ResponseInterface
    {
        return $this->get(sprintf('exchangerates/rates/%s/%s', $this->currencyCode, $this->tableType));
    }

    /**
     * Series of latest {count} exchange rates of the provided currency from the selected table type.
     *
     * @param  int $count
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function latest(int $count): ResponseInterface
    {
        return $this->get(sprintf('exchangerates/rates/%s/%s/last/%d', $this->currencyCode, $this->tableType, $count));
    }

    /**
     * Exchange rates of the provided currency from the selected table type, published today (or lack of data).
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function today(): ResponseInterface
    {
        return $this->get(sprintf('exchangerates/rates/%s/%s/today', $this->currencyCode, $this->tableType));
    }

    /**
     * Exchange rates of the provided currency from the selected table type, published on {date} (or lack of data).
     *
     * @param  DateTimeInterface $date
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function inDate(DateTimeInterface $date): ResponseInterface
    {
        return $this->get(sprintf(
            'exchangerates/rates/%s/%s/%s',
            $this->currencyCode,
            $this->tableType,
            $date->format(self::DATE_FORMAT)
        ));
    }

    /**
     * Exchange rates of the provided currency from the selected table type,
     * published from {startDate} to {endDate} (or lack of data).
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
            'exchangerates/rates/%s/%s/%s/%s',
            $this->currencyCode,
            $this->tableType,
            $startDate->format(self::DATE_FORMAT),
            $endDate->format(self::DATE_FORMAT)
        ));
    }
}