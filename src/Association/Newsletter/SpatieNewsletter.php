<?php

declare(strict_types=1);

namespace Francken\Association\Newsletter;

use DrewM\MailChimp\MailChimp;
use Spatie\Newsletter\Drivers\Driver;
use Spatie\Newsletter\Drivers\MailChimpDriver;

/**
 * The newsletter through spatie/laravel-newsletter, which uses the driver of
 * config/newsletter.php
 */
final class SpatieNewsletter implements Newsletter
{
    public function __construct(private Driver $driver)
    {
    }

    public function subscribe(string $email, array $mergeFields = []) : void
    {
        $this->driver->subscribe($email, $mergeFields);
    }

    public function unsubscribe(string $email) : void
    {
        $this->driver->unsubscribe($email);
    }

    public function hasMember(string $email) : bool
    {
        return $this->driver->hasMember($email);
    }

    public function isSubscribed(string $email) : bool
    {
        return $this->driver->isSubscribed($email);
    }

    /**
     * The package can't change the email address of a subscriber, so this asks Mailchimp
     * directly, like version 4 of the package did
     */
    public function updateEmailAddress(string $currentEmailAddress, string $newEmailAddress) : void
    {
        if ( ! $this->driver instanceof MailChimpDriver) {
            return;
        }

        /** @var MailChimp $api */
        $api = $this->driver->getApi();
        $listId = config('newsletter.lists.subscribers.id');
        if ( ! is_string($listId) || $listId === '') {
            return;
        }

        $subscriberHash = $api->subscriberHash($currentEmailAddress);

        $api->patch("lists/{$listId}/members/{$subscriberHash}", [
            'email_address' => $newEmailAddress,
        ]);
    }
}
