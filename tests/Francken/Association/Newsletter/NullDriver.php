<?php

declare(strict_types=1);

namespace Francken\Tests\Association\Newsletter;

use Francken\Association\Newsletter\Newsletter;
use Illuminate\Support\Facades\Log;

/**
 * A newsletter that doesn't send anything to Mailchimp, for tests and local development
 */
class NullDriver implements Newsletter
{
    public function __construct(private bool $logCalls = false)
    {
    }

    public function subscribe(string $email, array $mergeFields = []) : void
    {
        $this->log('subscribe', [$email, $mergeFields]);
    }

    public function unsubscribe(string $email) : void
    {
        $this->log('unsubscribe', [$email]);
    }

    public function hasMember(string $email) : bool
    {
        return false;
    }

    public function isSubscribed(string $email) : bool
    {
        return false;
    }

    public function updateEmailAddress(string $currentEmailAddress, string $newEmailAddress) : void
    {
        $this->log('updateEmailAddress', [$currentEmailAddress, $newEmailAddress]);
    }

    private function log(string $method, array $arguments) : void
    {
        if ($this->logCalls) {
            Log::debug('Called newsletter method: ' . $method . ' with:', $arguments);
        }
    }
}
