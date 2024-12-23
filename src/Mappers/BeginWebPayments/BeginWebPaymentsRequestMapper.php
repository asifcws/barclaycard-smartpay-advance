<?php

namespace Cws\BarclaycardSmartpayAdvance\Mappers\BeginWebPayments;

use Carbon\Carbon;
use Cws\BarclaycardSmartpayAdvance\Contracts\MapperService;
use Illuminate\Support\Arr;

class BeginWebPaymentsRequestMapper implements MapperService
{
    /**
     * @param array $request
     * @return array
     */
    public function map(array $request): array
    {
        $authType = $this->mapAuthType($request);
        $enterpriseId = $this->mapEnterpriseId();
        $clientId = $this->mapClientId();
        $transNo = $this->mapTransNo($request);
        $environment = $this->mapEnvironment();
        $version = $this->mapVersion();
        $transactionTime = $this->mapTransactionTime();
        $billingAddress = $this->mapBillingAddress($request);
        $purchaseAmount = $this->mapPurchaseAmount($request);
        $card = $this->mapCard($request);
        $authToken = $this->mapSetHmacToken(get_defined_vars());

        $return = [
            'beginWebPayment' => [
                'arg0' => [
                    'requester' => [
                        'authToken' => $authToken,
                        'enterpriseID' => $enterpriseId,
                        'clientID' => $clientId,
                        'transNo' => $transNo,
                        'environment' => $environment,
                        'version' => $version,
                    ],
                    'transactionTime' => $transactionTime,
                    'authType' => $authType,
                    'authenticate' => $this->mapAuthenticate(),
                    'authenticationRequest' => $this->mapAuthenticationRequest(),
                    'billingAddress' => $billingAddress,
                    'card' => $this->mapCard($request),
                    'currencyCode' => $this->mapCurrencyCode(),
                    'paymentMethod' => $this->mapPaymentMehod(),
                    'purchaseAmount' => $purchaseAmount,
                    'purchaseDescription' => $this->mapPurchaseDescription($request),
                    'storeResultPage' => $this->mapStoreResultPage($request),
                    'recurringPayment' => $this->mapRecurringPayment($request),
                    'schemeReferenceData' => $this->mapSchemeReferenceData($request),
                ]
            ]
        ];

        return $return;
    }

    /**
     * @param array $response
     * @return array
     */
    public function parse(array $response): array
    {
        return [];
    }

    /**
     * @param array $request
     * @return string
     */
    protected function mapAuthType(array $request): string
    {
        return Arr::get($request, 'options.auth_type') ?? 'AuthOnly';
    }

    /**
     * @return string
     */
    protected function mapEnterpriseId(): string
    {
        return config('barclaycard-smartpay-advance.enterprise_id');
    }

    /**
     * @return string
     */
    protected function mapClientId(): string
    {
        return config('barclaycard-smartpay-advance.client_id');
    }

    /**
     * @param array $request
     * @return string
     */
    protected function mapTransNo(array $request): string
    {
        return Arr::get($request, 'reference');
    }

    /**
     * @return string
     */
    protected function mapEnvironment(): string
    {
        return 'ECommerce';
    }

    protected function mapVersion(): int
    {
        return 22;
    }

    /**
     * @return string
     */
    protected function mapTransactionTime(): string
    {
        return Carbon::now('Europe/London')->format('Y-m-d\TH:i:s.001');
    }

    /**
     * @return bool
     */
    protected function mapAuthenticate(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    protected function mapAuthenticationRequest(): array
    {
        return [
            'challengePreference' => 'MANDATE'
        ];
    }

    /**
     * @param array $request
     * @return array
     */
    protected function mapBillingAddress(array $request): array
    {
        $line1 = Arr::get($request, 'options.address.line_1');
        $postcode = Arr::get($request, 'options.address.postcode');

        return [
            'line1' => $line1,
            'postcode' => $postcode,
        ];
    }

    /**
     * @param array $request
     * @return array|null
     */
    protected function mapCard(array $request): ?array
    {
        if (!Arr::get($request, 'options.card')) {
            return null;
        }

        return [
            'storageIndicator' => Arr::get($request, 'options.card.storage_indicator'),
            'onlineToken' => Arr::get($request, 'options.card.online_token'),
        ];
    }

    /**
     * @return string
     */
    protected function mapCurrencyCode(): string
    {
        return 'GBP';
    }

    /**
     * @return string
     */
    protected function mapPaymentMehod(): string
    {
        return 'Card';
    }

    /**
     * @param array $request
     * @return int
     */
    protected function mapPurchaseAmount(array $request): int
    {
        return (int) Arr::get($request, 'amount');
    }


    /**
     * @param array $request
     * @return int
     */
    protected function mapPurchaseDescription(array $request): int
    {
        return (int) Arr::get($request, 'description');
    }

    /**
     * @param array $request
     * @return string
     */
    protected function mapStoreResultPage(array $request): string
    {
        return Arr::get($request, 'callback_url');
    }

    /**
     * @param array $request
     * @return array|null
     */
    protected function mapRecurringPayment(array $request): ?array
    {
        if (!Arr::get($request, 'options.recurring_payment')) {
            return null;
        }

        return [
            'cardholderAgreement' => Arr::get($request, 'options.recurring_payment.cardholder_agreement'),
            'initialPayment' => Arr::get($request, 'options.recurring_payment.is_initial_payment'),
            'frequency' => Arr::get($request, 'options.recurring_payment.frequency'),
            'endDate' => Arr::get($request, 'options.recurring_payment.end_date'),
        ];
    }

    /**
     * @param array $request
     * @return string|null
     */
    protected function mapSchemeReferenceData(array $request): ?string
    {
        if (!Arr::get($request, 'options.scheme_reference_data')) {
            return null;
        }

        return Arr::get($request, 'options.scheme_reference_data');
    }

    /**
     * @param array $request
     * @return string
     */
    protected function mapSetHmacToken(array $request): string
    {
        $enterpriseId = config('barclaycard-smartpay-advance.enterprise_id');
        $clientId = config('barclaycard-smartpay-advance.client_id');
        $secret = config('barclaycard-smartpay-advance.hmac_secret');
        $transactionNo = Arr::get($request, 'transNo');
        $timestamp = Arr::get($request, 'transactionTime');
        $amount = Arr::get($request, 'purchaseAmount');
        $addressLine1 = Arr::get($request, 'billingAddress.line1');
        $postCode = Arr::get($request, 'billingAddress.postcode');
        $authType = Arr::get($request, 'authType');
        $onlineToken = Arr::get($request, 'card.onlineToken');
        $signature = "{$enterpriseId}{$clientId}{$transactionNo}{$timestamp}{$onlineToken}{$amount}{$addressLine1}{$postCode}{$authType}";

        return hash_hmac('sha256', $signature, $secret);
    }
}