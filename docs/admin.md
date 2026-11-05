# Admin

The admin (`/admin`) has the design of the website, in `resources/views/admin/layout.blade.php`:

- the navy header with the logo block, a tab for every section (Association, Study, ...),
  and links back to the website and to log out;
- a crimson hero with the title of the page (`@section('page-title', ...)`) and its buttons
  (`@section('actions')`);
- below it, the pages of the open section as tabs;
- the content (`@section('content')`), at most 1400px wide.

`/admin` itself is the dashboard: every page that the account may see, as cards grouped by
section.

## Adding a page

1. Add the route to `routes/admin.php`, in a group with the permission of the page
   (`can:dashboard:...-read`), and the permission to `config/francken/permissions.php` if
   it is new.
2. Add the page to `admin-menu` in `config/francken/navigation.php`, in its section:

   ```php
   [
       "name" => "Books",
       "url" => "books",                 // after the url of its section: /admin/study/books
       "icon" => "fas fa-book",          // Font Awesome 6
       "description" => "Second-hand books for sale",   // on the card of the dashboard
       "works" => true,                  // false: only shown to super admins
       "can" => "dashboard:books-read",  // only shown to accounts with this permission
       // "also" => ["admin/association/boards/*/committees"],  // other urls of this page
   ],
   ```

   `also` is for pages whose urls are below another page, like the committees of a board,
   so that their own tab is the open one.
3. Make the view extend `admin.layout`.

The navigation is built by `Francken\Shared\ViewComponents\Admin\Navigation`
(`<x-admin-navigation variant="sections|section-pages|page" />`).

## Patterns

The stylesheet of the admin is `resources/assets/sass/admin.scss`; Bootstrap 4 classes
(`card`, `table`, `btn`, `badge`, forms) work as usual, in the colours of the website.

| What | How |
|---|---|
| A row of a table that opens a page | `class="admin-clickable-rows"` on the table and `class="admin-row-link"` on the main link of a row. Other links and buttons in the row stay clickable |
| Cards | `.admin-pages__link` (the dashboard), `.album-card` (photo albums), `.admin-committee` (committees); the whole card is the link |
| An empty list | A row or paragraph that says so, like "There are no symposia yet.", instead of an empty table |
| Buttons in the hero | Put them in `@section('actions')`; they become navy |
| Deleting | A small red `btn btn-outline-danger` button with `confirm(...)`, below the content |
| Forms | The components in `resources/views/components/forms` (`<x-forms.text>`, `<x-forms.date>`, `<x-forms.select>`, `<x-forms.markdown>`, `<x-forms.submit>`, ...) |
| Dates | `yyyy-mm-dd` in forms; `j F Y` or `D j M Y` in text |
| Amounts | `€{{ number_format($amount, 2) }}`, so €1,234.56 |

## Things that come from elsewhere

- Activities come from the Google Calendar of the association and are imported every hour,
  so their name, dates and description can't be changed in the admin. Their sign-ups can.
- Photo albums come from Nextcloud (`photos:synchronize`); the admin sets their
  visibility, which is "members only" for new albums.
- Members, products and transactions are in the old database (`francken-legacy`), which
  the consumption counter uses as well.
