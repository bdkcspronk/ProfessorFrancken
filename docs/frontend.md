# Front end

## Building with Vite

The stylesheets and scripts in `resources/assets/` are built with [Vite](https://vite.dev)
(`vite.config.js`) into `public/build/`, which isn't in git. Files there get a new name when
their contents change, so browsers can keep them as long as they like.

```sh
docker compose run --rm npm npm ci          # once, and after package.json changes
docker compose run --rm npm npm run build   # builds everything once
docker compose up vite                      # a development server that updates the page
                                            # in the browser when a stylesheet changes
```

While the development server runs, the pages load the stylesheets from it (it writes its
address in `public/hot`); when it stops they load `public/build/` again.

Views load files with `@vite(...)`, which `Francken\Shared\Vite` turns into relative urls.

### What is built (the inputs of `vite.config.js`)

| File | Used by |
|---|---|
| `resources/assets/sass/app.scss` | Every page of the website and the admin |
| `resources/assets/css/vendor.css` | Every page: the fonts and the icons. Loaded after `app.scss`, so that the font of the icons wins from classes like `.h2` |
| `resources/assets/sass/print.scss` | The print layout of the admin |
| `resources/assets/js/site.js` | Every page of the website |
| `resources/assets/js/admin.js` | Every page of the admin |
| `BoardPhotos.js`, `photos.js`, `markdown-editor.js`, `404.js` | Single pages, which load them with `@vite` in `@push('scripts')` |

A new script for a single page: add it to `input` in `vite.config.js` and load it with
`@vite('resources/assets/js/name.js')` in the page.

## Stylesheets

`app.scss` imports Bootstrap 4 (`variables.scss` sets its colours and fonts), a few old
stylesheets of before the redesign (`header.scss`, `footer.scss`, `junk.scss`, ...), and then:

- `home.scss`: the design of the website, from the homepage to every other page;
- `admin.scss`: what only the admin uses (see [Admin](admin.md)).

### Design

The variables at the top of `home.scss`:

| Variable | Value | For |
|---|---|---|
| `$home-navy` | `#14213d` | The header, text, buttons |
| `$home-crimson` | `#a51c30` | The heroes, lines, accents |
| `$home-crimson-light` | `#f0889a` | Accents on navy |
| `$home-navy-light` | `#4b6795` | The tint of the photo of the board |
| `$home-heading-font` | Space Grotesk | Headings, buttons, menus |
| `$home-body-font` | Inter | Text |
| `$slant` | tan(30°) | The slanted edges, which lean like the logo blocks |

The fonts and Font Awesome come from our own server (`vendor.css`), not from Google Fonts or
a CDN.

### Components to reuse

| Class | What |
|---|---|
| `.page-hero` | The crimson block with the title of a page (`layout/_page-hero.blade.php`) |
| `.home-heading` | A heading with a crimson line below it |
| `.home-btn`, `.home-btn--crimson`, `--outline`, `--white` | Buttons |
| `.cms-page` | Text from the admin (Markdown), like custom pages and committee pages |
| `.page-intro` | The introduction at the top of a page |
| `.album-grid`, `.album-card` | Cards with a photo and a caption |
| `.login-prompt` | Asks visitors to log in for something |
| `.news-pager` | Previous and next links |
| `@include fcc-lattice(...)` | The FCC lattice behind a block (see below) |
| `stripes()`, `@include striped-top` | The diagonal navy and crimson stripes |

### Pages and layouts

A page of the website extends `layout.one-column-layout` (or `two-column-layout`, with an
`aside`), sets its title with `@section('page-title', ...)` for the crimson hero, and its
content in `@section('content')`. Scripts and styles of one page go in `@push('scripts')` and
`@push('styles')`.

## Scripts

Our scripts are plain JavaScript modules in `resources/assets/js/`, without jQuery or a
framework:

| Script | What |
|---|---|
| `FccLattice.js` | Draws the lattice of `[data-fcc-lattice]` on a canvas, which shrinks near the mouse and sends a wave from a click. Without it the lattice is a background image |
| `LogoBalls.js` | The balls of the logo in the header, which react to the mouse |
| `SiteMenu.js` | The menu that slides in from the side, with its sections |
| `AccountMenu.js` | The dropdown of the profile in the header |
| `SiteSearch.js` | The search field of the header |
| `ExternalLinks.js` | Opens links to other websites in a new tab |
| `math.js` | Renders LaTeX with KaTeX, which it only loads on pages that contain `$$`, `$`, `\(` or `\[` |
| `date-inputs.js` | Replaces date fields by flatpickr, so every browser shows and takes `yyyy-mm-dd`; only loaded on pages with a date field |
| `AdminTabs.js` | Scrolls the open tabs of the admin into view on small screens |

jQuery is only loaded on the page of a photo album (`photos.js`), for the lightbox. Some old
views of the admin (like the form of a book) still use their own jQuery from a CDN.

Respect `prefers-reduced-motion`: the scripts and stylesheets leave out animations when a
visitor asks for less motion.

## Icons

Font Awesome 6 Free, with only the icons that the code uses: `vite/fontawesome-subset.js`
looks through `resources/views`, `resources/assets/js`, `src`, `config` and
`database/migrations` for `fa-...` names and for strings that are the name of an icon (like
`'users'`), and builds `resources/assets/fontawesome/` from them.

An icon name that is put together in Blade (`fa-{{ $icon }}`) from a value that doesn't
appear in the code as a string, or an icon of a library (like the toolbar of EasyMDE), has to
be added to `extra` in `vite.config.js`. The build warns about icon names that Font Awesome
doesn't have.

Use the names of Font Awesome 6 (`fas fa-...`, `far fa-...`, `fab fa-...`); most names of
version 5 still work.
