@php
use Francken\Treasurer\Http\Controllers\DeductionsController;
@endphp

<tr>
    <td>
        <a href="{{ action([DeductionsController::class, 'show'], $deduction->id) }}" class="font-weight-bold">
            {{ $deduction->deducted_at->format('j F Y') }}
        </a>
    </td>

    <td>
        @isset ($deduction->emails_sent_at)
            <span class="badge badge-success"><i class="fas fa-check" aria-hidden="true"></i> Sent</span>
        @else
            <span class="badge badge-warning"><i class="fas fa-clock" aria-hidden="true"></i> Not sent yet</span>
        @endisset
    </td>

    <td class="text-right">
        {{ $deduction->amount_of_members }}
    </td>

    <td class="text-right text-nowrap">
        &euro;{{ number_format($deduction->total_amount, 2) }}
    </td>
</tr>
