# Tests and code checks

GitHub runs two workflows on every push (`.github/workflows/`):

- **PHP** (`php.yml`): builds the assets with Node 22, runs the tests with phpunit, and runs
  phpstan and psalm.
- **Code Style** (`code-style.yml`): runs php-cs-fixer. On a fork it can't push its fixes,
  so it fails when the code isn't formatted; run the fixer yourself before pushing.

Run them locally before pushing:

```sh
docker compose exec php vendor/bin/phpunit
docker compose exec php vendor/bin/phpstan analyse -c phpstan.neon.dist
docker compose exec php vendor/bin/psalm --no-cache
docker compose exec php vendor/bin/php-cs-fixer fix
```

## Tests

`tests/Features` has tests that visit pages and fill in forms (laravel/browser-kit-testing),
`tests/Francken` tests of classes. They use an SQLite database and the newsletter driver that
doesn't send anything (`tests/Francken/Association/Newsletter/NullDriver.php`).

Tests that look for text on a page break when that text changes, for example when a button
gets another name. Change the test along with the page, and check what the test meant to
check: a test once only passed because the old menu contained the word `touchstart`.

## phpstan and psalm

Both check the types of the code. They have a baseline (`phpstan-baseline.neon`,
`psalm-baseline.xml`): known findings in older code, mostly about the "magic" methods and
properties of Laravel models, which they don't report again. New code shouldn't add to them.

- Document the columns of a model with `@property` in its docblock, like
  `Association\Committees\Committee`.
- After upgrading phpstan, psalm or Laravel, the new findings of older code can be added to
  the baselines:

  ```sh
  docker compose exec php vendor/bin/phpstan analyse -c phpstan.neon.dist --generate-baseline phpstan-baseline.neon
  docker compose exec php vendor/bin/psalm --no-cache --set-baseline=psalm-baseline.xml
  ```

  Fix the findings in code that you wrote yourself instead.

## Security of packages

```sh
docker compose exec php composer audit
docker compose run --rm npm npm audit
```

`roave/security-advisories` was left out of `composer.json` with the upgrade to Laravel 10,
as it blocks every update while `plank/laravel-mediable` is below version 7 (which needs a
newer Laravel). Add it again after that upgrade.
