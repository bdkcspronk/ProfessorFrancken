<?php

declare(strict_types=1);

namespace Francken\Features\Auth\Admin;

use Francken\Association\LegacyMember;
use Francken\Auth\Account;
use Francken\Auth\Http\Controllers\Admin\AccountsController;
use Francken\Features\LoggedInAsAdmin;
use Francken\Features\TestCase;

class AccountsFeature extends TestCase
{
    use LoggedInAsAdmin;

    /** @test */
    public function accounts_can_be_searched_by_email_name_or_member_id() : void
    {
        $kathinka = factory(Account::class)->create([
            'email' => 'kathinka@scriptcie.nl',
            'member_id' => factory(LegacyMember::class)->create([
                'voornaam' => 'Kathinka',
                'tussenvoegsel' => 'van',
                'achternaam' => 'Dijk',
            ]),
        ]);
        factory(Account::class)->create([
            'email' => 'someone@example.com',
            'member_id' => factory(LegacyMember::class)->create([
                'voornaam' => 'Someone',
                'tussenvoegsel' => '',
                'achternaam' => 'Else',
            ]),
        ]);

        $this->visit(action([AccountsController::class, 'index']))
            ->type('scriptcie', 'search')
            ->press('Search')
            ->see('kathinka@scriptcie.nl')
            ->dontSee('someone@example.com');

        $this->visit(action([AccountsController::class, 'index'], ['search' => 'kathinka dijk']))
            ->see('kathinka@scriptcie.nl')
            ->see('Kathinka van Dijk')
            ->dontSee('someone@example.com');

        $this->visit(action([AccountsController::class, 'index'], ['search' => (string) $kathinka->member_id]))
            ->see('kathinka@scriptcie.nl')
            ->dontSee('someone@example.com');

        $this->visit(action([AccountsController::class, 'index'], ['search' => 'nobody']))
            ->see('No accounts found for "nobody"');
    }

    /** @test */
    public function accounts_can_be_sorted_by_any_column() : void
    {
        $few = factory(Account::class)->create([
            'email' => 'few@example.com',
            'member_id' => factory(LegacyMember::class)->create(['voornaam' => 'Bert', 'tussenvoegsel' => '', 'achternaam' => 'Zwart']),
        ]);
        $many = factory(Account::class)->create([
            'email' => 'many@example.com',
            'member_id' => factory(LegacyMember::class)->create(['voornaam' => 'Anna', 'tussenvoegsel' => '', 'achternaam' => 'Appel']),
        ]);
        $many->givePermissionTo('dashboard:news-read', 'dashboard:news-write');

        $this->visit(action([AccountsController::class, 'index']))
            ->click('Nr. permissions');
        $this->assertTextOrder(['few@example.com', 'many@example.com']);

        $this->click('Nr. permissions');
        $this->assertTextOrder(['many@example.com', 'few@example.com']);

        $this->visit(action([AccountsController::class, 'index'], ['sort' => 'member']));
        $this->assertTextOrder(['Anna Appel', 'Bert Zwart']);

        $this->visit(action([AccountsController::class, 'index'], ['sort' => 'member', 'direction' => 'desc']));
        $this->assertTextOrder(['Bert Zwart', 'Anna Appel']);

        $this->visit(action([AccountsController::class, 'index'], ['sort' => 'roles', 'search' => 'example.com']))
            ->see('few@example.com')
            ->see('many@example.com');
    }

    private function assertTextOrder(array $texts) : void
    {
        $content = $this->crawler->filter('table')->text();
        $positions = array_map(fn (string $text) => mb_strpos($content, $text), $texts);

        $this->assertNotContains(false, $positions, 'Not all texts are shown on the page');
        $this->assertSame($positions, collect($positions)->sort()->values()->all());
    }
}
