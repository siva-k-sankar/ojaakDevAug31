<?php
return [
    'gcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AAAAQYOr29Y:APA91bEv7ZHeROX74zs2pVLG5aDrJ5jKQTYO5Cgm39Bm37TUaE8mIpQ1q2OWewShzTGFKGjumK6zTW2f4SfrgvKLV4nC5Ve0zbuIC0MSX9I1K7lhxgbghdpUnNN-iYsTWAjuvZbYDfAC',
    ],
    'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AAAAQYOr29Y:APA91bEv7ZHeROX74zs2pVLG5aDrJ5jKQTYO5Cgm39Bm37TUaE8mIpQ1q2OWewShzTGFKGjumK6zTW2f4SfrgvKLV4nC5Ve0zbuIC0MSX9I1K7lhxgbghdpUnNN-iYsTWAjuvZbYDfAC',
    ],
    'apn' => [
        'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
        'passPhrase' => '1234', //Optional
       // 'passPhrase' => 'secret',
        'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
        'dry_run' => true,
    ],
];
