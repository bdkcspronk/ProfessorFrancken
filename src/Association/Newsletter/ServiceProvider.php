<?php

declare(strict_types=1);

namespace Francken\Association\Newsletter;

use Francken\Association\Members\Events\MemberEmailWasChanged;
use Francken\Association\Members\Registration\Events\MemberWasRegistered;
use Francken\Association\Newsletter\EventHandlers\SubscribeMemberToMailchimp;
use Francken\Association\Newsletter\EventHandlers\UpdateMailchimpSubscription;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Spatie\Newsletter\Drivers\Driver;
use Webmozart\Assert\Assert;

final class ServiceProvider extends BaseServiceProvider
{
    public function register() : void
    {
        // The driver of config/newsletter.php, which the package registers as "newsletter"
        $this->app->singleton(Newsletter::class, function () : Newsletter {
            $driver = $this->app->make('newsletter');
            Assert::isInstanceOf($driver, Driver::class);

            return new SpatieNewsletter($driver);
        });
    }

    public function boot() : void
    {
        $dispatcher = $this->app->make(Dispatcher::class);
        $this->subscribeToRegistrationEvents($dispatcher);
    }

    private function subscribeToRegistrationEvents(Dispatcher $events) : void
    {
        $events->listen(
            MemberWasRegistered::class,
            SubscribeMemberToMailchimp::class
        );

        $events->listen(
            MemberEmailWasChanged::class,
            UpdateMailchimpSubscription::class
        );
    }
}
