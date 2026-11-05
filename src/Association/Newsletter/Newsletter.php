<?php

declare(strict_types=1);

namespace Francken\Association\Newsletter;

/**
 * The mailing list of the newsletter in Mailchimp, of which members are kept up to date
 * (see EventHandlers). Tests and local development use a driver that does nothing
 */
interface Newsletter
{
    public function subscribe(string $email, array $mergeFields = []) : void;

    public function unsubscribe(string $email) : void;

    public function hasMember(string $email) : bool;

    public function isSubscribed(string $email) : bool;

    public function updateEmailAddress(string $currentEmailAddress, string $newEmailAddress) : void;
}
