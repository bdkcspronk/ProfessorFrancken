{{-- News items, of which the whole row opens the item to edit it --}}
@if ($news->isNotEmpty())
    <table class="table table-hover admin-clickable-rows mb-0">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th class="text-nowrap">Publication date</th>
                <th class="text-right"><span class="sr-only">Preview</span></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($news as $item)
                <tr>
                    <td>
                        <a class="admin-row-link font-weight-bold" href="{{ action([\Francken\Association\News\Http\AdminNewsController::class, 'show'], ['news' => $item]) }}">
                            {{ $item->title }}
                        </a>
                    </td>
                    <td class="text-muted">
                        {{ $item->author_name }}
                    </td>
                    <td class="text-muted text-nowrap">
                        {{ optional($item->published_at)->format('j M Y') ?? 'Not published' }}
                    </td>
                    <td class="text-right">
                        <a class="text-muted" href="{{ action([\Francken\Association\News\Http\AdminNewsController::class, 'preview'], ['news' => $item]) }}" title="Show a preview">
                            <i class="far fa-eye" aria-hidden="true"></i>
                            <span class="sr-only">Show a preview of {{ $item->title }}</span>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
