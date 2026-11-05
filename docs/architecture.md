# Architecture

## Where the code is

The PHP code is in `src/`, with the namespace `Francken\` (see `composer.json`). It is grouped
by part of the association instead of by kind of class:

| Folder | What |
|---|---|
| `src/Association` | Members and registrations, boards, committees, activities and sign-ups, news, photos, the symposium, the Francken Vrij, the newsletter, borrelcie, the almanak |
| `src/Study` | The book sale |
| `src/Extern` | Partners (companies), their profiles, job openings and the fact sheet |
| `src/Treasurer` | Products, transactions and deductions |
| `src/PlusOne` | The API of the consumption counter (PlusOne), with JWT tokens |
| `src/Auth` | Accounts, roles and permissions |
| `src/Shared` | What everything uses: providers, settings, media, markdown, view components, the console kernel |

Each part has its own models, `Http/` (controllers and requests) and sometimes a
`ServiceProvider` that registers its event handlers. The providers are listed in
`config/app.php`.

Views are in `resources/views/`, with the admin in `resources/views/admin/`. Routes are in
`routes/web.php` (the website) and `routes/admin.php` (everything under `/admin`).

## Two databases

- The default connection is the database of this website.
- `francken-legacy` (`DB_LEGACY_*` in `.env`) is the database of the old website, which is
  still used for members (`leden`), products and transactions of the consumption counter.
  Models on it set `protected $connection = 'francken-legacy'`, like `LegacyMember`,
  `Treasurer\Product` and `Treasurer\Transaction`. Their columns are Dutch (`voornaam`,
  `prijs`, `categorie`, ...); keep those names, as the consumption counter uses the same
  tables.

## Scheduled jobs

`src/Shared/Console/Kernel.php`; on the server the `scheduler` container runs them.

| Command | When | What |
|---|---|---|
| `activities:update-ical` and `activities:import` | Hourly | Downloads the Google Calendar of the association and imports its activities. Activities are therefore changed in the calendar, not in the admin |
| `photos:synchronize` | Hourly | Reads the photo albums from Nextcloud |
| `boards:update-board-member-status` | Daily | Updates whether board members are candidate, current, demissioned or decharged |

Other commands: `permission:setup` and `permission:import` (roles and permissions from
`config/francken/permissions.php`), `committees:remove-empty-photos`, `photos:update-metadata`,
`symposia:send-information-email`, `migrate:legacy-db`.

Jobs of the queue (like the newsletter handlers) run in the `queue-worker` container with
Horizon (`/admin/compucie/horizon`).

## Permissions

Permissions are defined in `config/francken/permissions.php` and given to roles
(spatie/laravel-permission). Members of a committee get the role of the committee, and board
members the permissions of the board. The pages of the admin check them with
`can:dashboard:...-read` middleware in `routes/admin.php`, and the navigation of the admin
only shows what an account may see (see [Admin](admin.md)).

## Settings

The settings on `/admin/compucie/settings` (phone numbers, the image of the homepage,
whether albums are only for members, links in the menu) are stored in
`storage/app/settings.json` with spatie/valuestore, through `Francken\Shared\Settings\Settings`.

## Services the website talks to

| Service | For | Settings in `.env` |
|---|---|---|
| Google Calendar | The activities (a public iCal address in `FetchLatestFranckenIcal`) | - |
| Google Maps | The map of an activity in the admin | `GOOGLE_MAPS_EMBED_KEY` |
| Nextcloud | The photo albums | `NEXTCLOUD_*` |
| Flickr | The old photo albums (only read) | `FLICKR_*` |
| Mailchimp | The newsletter (`Francken\Association\Newsletter`) | `MAILCHIMP_DRIVER`, `MAILCHIMP_APIKEY`, `MAILCHIMP_LIST_ID` |
| imaginary | Resizing images (`image()` helper, the `imaginary` container) | - |

With `MAILCHIMP_DRIVER=log` (the default) nothing is sent to Mailchimp, which is what
local development and the tests use.

## Things to know

- Dates of models are `$casts` (`'datetime'`); Laravel 10 removed `$dates`.
- Amounts are written in the international format, `number_format($amount, 2)` (€1,234.56).
- Dates in forms are `yyyy-mm-dd` (see [Front end](frontend.md)).
- Images and uploads have relative urls (`/uploads/...`), so the website also works on a
  phone on the same network during development.
