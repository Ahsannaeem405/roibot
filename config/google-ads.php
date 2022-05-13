<?php
return [
    //Environment=> test/production
    'env' => 'test',
    //Google Ads
    'production' => [
        'developerToken' => "AGdbDbjWtTjXcwuaBKQk4g",
        'clientCustomerId' => "223-590-6845",
        'userAgent' => "Rehman Ahmad",
        'clientId' => "817297598323-a7dl070mo74514q4mp98q73s78318ejj.apps.googleusercontent.com",
        'clientSecret' => "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",
        'refreshToken' => "REFRESH-TOKEN"
    ],
    'test' => [
        'developerToken' => "AGdbDbjWtTjXcwuaBKQk4g",
        'clientCustomerId' => "223-590-6845",
        'userAgent' => "Rehman Ahmad",
        'clientId' => "817297598323-a7dl070mo74514q4mp98q73s78318ejj.apps.googleusercontent.com",
        'clientSecret' => "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",
        'refreshToken' => "REFRESH-TOKEN"
    ],
    'oAuth2' => [
        'authorizationUri' => 'https://accounts.google.com/o/oauth2/v2/auth',
        'redirectUri' => 'http://127.0.0.1:8001/data',
        'tokenCredentialUri' => 'https://www.googleapis.com/oauth2/v4/token',
        'scope' => 'https://www.googleapis.com/auth/adwords'
    ]
];
