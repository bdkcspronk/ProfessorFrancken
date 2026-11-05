<?php

declare(strict_types=1);

use Spatie\Newsletter\Drivers\MailChimpDriver;

/*
 * The newsletter in Mailchimp (spatie/laravel-newsletter). The environment variables are the
 * same as before version 5 of the package: MAILCHIMP_DRIVER is "log" (nothing is sent, the
 * default) or anything else for Mailchimp, like the "api" of before
 */
$driver = env('MAILCHIMP_DRIVER', 'log');

return [

    'driver' => in_array($driver, [null, '', 'log'], true) ? 'log' : MailChimpDriver::class,

    'driver_arguments' => [
        'api_key' => env('MAILCHIMP_APIKEY'),

        'endpoint' => null,
    ],

    'default_list_name' => 'subscribers',

    'lists' => [

        'subscribers' => [

            'id' => env('MAILCHIMP_LIST_ID'),
        ],
    ],
];
