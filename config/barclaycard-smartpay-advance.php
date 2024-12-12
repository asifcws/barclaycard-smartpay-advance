<?php

return [
    'base_url' => env('BARCLAYCARD_SMARTPAY_ADVANCE_BASE_URL', 'https://ws.eshapay.net/Bureau/EMISws/WebPayment_3_0'),
    'enterprise_id' => env('BARCLAYCARD_SMARTPAY_ADVANCE_ENTERPRISE_ID'),
    'client_id' => env('BARCLAYCARD_SMARTPAY_ADVANCE_CLIENT_ID'),
    'hmac_secret' => env('BARCLAYCARD_SMARTPAY_ADVANCE_HMAC_SECRET'),
    'version' => env('BARCLAYCARD_SMARTPAY_ADVANCE_VERSION'),
];