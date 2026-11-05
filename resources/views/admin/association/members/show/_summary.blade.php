{{-- TODO add links --}}
<p class="lead">
    @php
        // The start of the membership, or else the day that the member was added
        $memberSince = $member->start_lidmaatschap !== null
            ? \Illuminate\Support\Carbon::parse($member->start_lidmaatschap)
            : $member->created_at;
    @endphp
    {{ $member->fullname }} {{ $member->einde_lidmaatschap !== null ? 'was' : 'has been' }} a member of T.F.V. ‘Professor Francken’ since {{ $memberSince->format('j F Y') }}@if ($member->einde_lidmaatschap !== null) until {{ \Illuminate\Support\Carbon::parse($member->einde_lidmaatschap)->format('j F Y') }}@endif.
    @foreach ($boardMembers as $boardMember)
        Was installed as <em>{{ $boardMember->title }}</em> during the board year of <em>"{{ $boardMember->board->board_name->toString()  }}"</em> from {{ $boardMember->installed_at->format('F j Y') }}
        @if ($boardMember->decharged_at)
            to {{ $boardMember->decharged_at->format('F j Y') }}
        @endif.
    @endforeach
    @if ($currentCommittees->isNotEmpty())
        Is part of {{ $currentCommittees->count() }} committees. 
    @endif
</p>
