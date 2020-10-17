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
class ExchangeRates extends AbstractApi
{
    public const TABLE_TYPE_A = 'a';
    public const TABLE_TYPE_B = 'b';
    public const TABLE_TYPE_C = 'c';

    protected $tableType;

    /**
     * @param  ClientInterface $client
     * @param  string          $tableType Table type (A, B, or C)
     */
    public function __construct(ClientInterface $client, string $tableType)
    {
        parent::__construct($client);

        Assert::oneOf($tableType, [self::TABLE_TYPE_A, self::TABLE_TYPE_B, self::TABLE_TYPE_C]);

        $this->tableType = $tableType;
    }

    public function fromTable(string $tableType): self
    {
        $this->tableType = $tableType;

        return $this;
    }

    public function forCurrency(string $currencyCode): CurrencyExchangeRates
    {
        return new CurrencyExchangeRates($this->client, $this->tableType, $currencyCode);
    }

    /**
     * Current table of exchange rates from the selected table type.
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function all(): ResponseInterface
    {
        return $this->get(sprintf('exchangerates/tables/%s', $this->tableType));
    }

    /**
     * Series of latest {count} exchange rates from the selected table type.
     *
     * @param  int $count
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function latest(int $count): ResponseInterface
    {
        return $this->get(sprintf('exchangerates/tables/%s/last/%d', $this->tableType, $count));
    }

    /**
     * Exchange rate from the selected table type, published today (or lack of data).
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function today(): ResponseInterface
    {
        return $this->get(sprintf('exchangerates/tables/%s/today', $this->tableType));
    }

    /**
     * Exchange rate from the selected table type, published on {date} (or lack of data).
     *
     * @param  DateTimeInterface $date
     *
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function inDate(DateTimeInterface $date): ResponseInterface
    {
        return $this->get(sprintf(
            'exchangerates/tables/%s/%s',
            $this->tableType,
            $date->format(self::DATE_FORMAT)
        ));
    }

    /**
     * Exchange rates from the selected table type, published from {startDate} to {endDate} (or lack of data).
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
            'exchangerates/tables/%s/%s/%s',
            $this->tableType,
            $startDate->format(self::DATE_FORMAT),
            $endDate->format(self::DATE_FORMAT)
        ));
    }
}