<?php

return [
//    'credentials'       => [
//        'key'    => env('AWS_COGNITO_KEY', 'AKIARONHZ3SVLR7RV757'),
//        'secret' => env('AWS_COGNITO_SECRET', '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'),
//    ],
//    'region'            => env('AWS_COGNITO_REGION', 'us-east-1'),
//    'version'           => env('AWS_COGNITO_VERSION', 'latest'),
//    'app_client_id'     => env('AWS_COGNITO_CLIENT_ID', '9dekvubvedkdl9otk7qb6jkrl'),
//    'app_client_secret' => env('AWS_COGNITO_CLIENT_SECRET', 'h03d9etrkd83kr4eo30oapfltc6np2s6gspjkj3uk4v351qnvll'),
//    'user_pool_id'      => env('AWS_COGNITO_USER_POOL_ID', 'us-east-1_QyROgfk9W'),

    'credentials'       => [
        'key'    => env('AWS_COGNITO_KEY', 'AKIAXRCUSMJWVVAO2GS5'),
        'secret' => env('AWS_COGNITO_SECRET', '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'),
    ],
    'region'            => env('AWS_COGNITO_REGION', 'us-east-1'),
    'version'           => env('AWS_COGNITO_VERSION', 'latest'),
    'app_client_id'     => env('AWS_COGNITO_CLIENT_ID', '2bjmh5uct12jplt5g86ajbu87u'),
    'app_client_secret' => env('AWS_COGNITO_CLIENT_SECRET', 'n0ofcv4cds9p5ubr2f21jvsosmrc47s17qemfgnjf9ccumgm0no'),
    'user_pool_id'      => env('AWS_COGNITO_USER_POOL_ID', 'us-east-1_m2V7lEabN'),

    // package configuration
    'use_sso'           => env('USE_SSO', true),
    'sso_user_fields'   => [
        'name',
        'email',
    ],
    'sso_user_model'    => 'App\User',
    'delete_user'       => env('AWS_COGNITO_DELETE_USER', true),
];
