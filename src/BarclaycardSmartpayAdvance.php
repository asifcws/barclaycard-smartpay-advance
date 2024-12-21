<?php

namespace Cws\BarclaycardSmartpayAdvance;

use Cws\BarclaycardSmartpayAdvance\Api\Client\BarclaycardClient;
use Cws\BarclaycardSmartpayAdvance\Api\Functions\BeginWebPaymentSoapFunction;
use Cws\BarclaycardSmartpayAdvance\Mappers\BeginWebPayments\BeginWebPaymentsRequestMapper;
use Cws\BarclaycardSmartpayAdvance\Mappers\BeginWebPayments\BeginWebPaymentsResponseMapper;
use Cws\BarclaycardSmartpayAdvance\Services\LoggingService;

class BarclaycardSmartpayAdvance
{
    /**
     * @var BarclaycardClient
     */
    protected BarclaycardClient $client;

    /**
     * @var LoggingService
     */
    protected LoggingService $loggingService;

    /**
     * @param BarclaycardClient $client
     * @param LoggingService $loggingService
     */
    public function __construct(BarclaycardClient $client, LoggingService $loggingService)
    {
        $this->client = $client;
        $this->loggingService = $loggingService;
    }

    /**
     * @param array $request
     * @param array $auditTag
     * @param MapperService $mapperService
     * @return array
     * @throws \Exception
     */
    public function beginWebPayment(array $request, array $auditTags): array
    {
        $arguments = app(BeginWebPaymentsRequestMapper::class)->map($request);

        $response = $this->client->call(
            app(BeginWebPaymentSoapFunction::class, [
                'arguments' => $arguments,
                'auditTags' => $auditTags,
                'loggingService' => $this->loggingService,
            ])
        );

        return app(BeginWebPaymentsResponseMapper::class)->parse($response);
    }
}