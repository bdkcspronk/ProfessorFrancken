<?php

declare(strict_types=1);

namespace Francken\Study\BooksSale\Http;

use Francken\Shared\Http\Controllers\Controller;
use Francken\Study\BooksSale\Book;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BooksController extends Controller
{
    public function index(Request $request) : View
    {
        $search = trim((string) $request->input('search', ''));

        $books = Book::query()
            ->available()
            ->when($search !== '', fn (Builder $query) => $this->search($query, $search))
            ->orderBy('title', 'asc')
            ->orderBy('edition', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('book.index')
            ->with(['books'=> $books, 'search' => $search])
            ->with('breadcrumbs', [
                ['url' => '/study', 'text' => 'Study'],
                ['url' => '/study/books', 'text' => 'Books'],
            ]);
    }

    public function show(Book $book) : View
    {
        if ($book->has_been_sold) {
            abort(404);
        }

        return view('book.show')
            ->with(['book' => $book])
            ->with('breadcrumbs', [
                ['url' => '/study', 'text' => 'Study'],
                ['url' => '/study/books', 'text' => 'Books'],
                ['text' => $book->title],
            ]);
    }

    /**
     * Every word has to be in the title, the author or the ISBN of the book
     */
    private function search(Builder $query, string $search) : Builder
    {
        $words = preg_split('/\s+/u', $search, -1, PREG_SPLIT_NO_EMPTY) ?: [];

        foreach ($words as $word) {
            // ISBNs are stored without dashes
            $word = preg_match('/^[\d-]+[xX]?$/', $word) === 1 ? str_replace('-', '', $word) : $word;
            $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $word) . '%';

            $query->where(function (Builder $query) use ($like) : void {
                $query->where('title', 'like', $like)
                    ->orWhere('author', 'like', $like)
                    ->orWhere('isbn', 'like', $like);
            });
        }

        return $query;
    }
}
