<?php

return [
    'gcm' => [
        'priority' => 'normal',
        'dry_run'  => false,
        'apiKey'   => 'My_ApiKey',
    ],
    'fcm' => [
        'priority' => 'normal',
        'dry_run'  => false,
        'apiKey'   => 'AAAA44ix9jE:APA91bEHgBTvbBCUgvT8sM0tArxMPJS0o-w8h1jbp_y4-bMCbC9yTJNp2YH8LFwVb1UycL5ZUlaXqv62J-GENj0lqJS5UD865bTfkmz8cIku-QC1lb9KuHw7PAH0WfOJoKVE0efDC8rv',
    ],
    'apn' => [
        'certificate' => __DIR__ . '/iosCertificates/apns-dev-cert.pem',
        'passPhrase'  => '1234', //Optional
        'passFile'    => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
        'dry_run'     => true
    ]
];