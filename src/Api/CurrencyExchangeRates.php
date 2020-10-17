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
class CurrencyExchangeRates extends ExchangeRates
{
    private $currencyCode;

    public function __construct(ClientInterface $client, string $tableType, string $currencyCode)
    {
        parent::__construct($client, $tableType);

        $this->currencyCode = $currencyCode;
    }

    public function all(): ResponseInterface
    {
        return $this->get(sprintf('exchangerates/rates/%s/%s', $this->currencyCode, $this->tableType));
    }
    
    public function latest(int $count): ResponseInterface
    {
        return $this->get(sprintf('exchangerates/rates/%s/%s/last/%d', $this->currencyCode, $this->tableType, $count));
    }

    public function today(): ResponseInterface
    {
        return $this->get(sprintf('exchangerates/rates/%s/%s/today', $this->currencyCode, $this->tableType));
    }

    public function inDate(DateTimeInterface $date): ResponseInterface
    {
        return $this->get(sprintf(
            'exchangerates/rates/%s/%s/%s',
            $this->currencyCode,
            $this->tableType,
            $date->format(self::DATE_FORMAT)
        ));
    }

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