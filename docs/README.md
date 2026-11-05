# Developer documentation

The documentation for the Compucie: how the website of T.F.V. ‘Professor Francken’ is put
together and how to work on it. The [readme](../readme.md) explains how to get the website
running on your own computer; these pages explain the rest.

| Page | About |
|---|---|
| [Architecture](architecture.md) | How the code is organised, the two databases, scheduled jobs, permissions, settings and the services the website talks to |
| [Front end](frontend.md) | Vite, the stylesheets, the design (colours, fonts, components, the lattice), the scripts and the icons |
| [Admin](admin.md) | The layout of the admin, adding a page to it, and the patterns its pages use |
| [Tests and code checks](quality.md) | phpunit, phpstan, psalm, php-cs-fixer and what GitHub checks |
| [Upgrading the server](server-upgrade.md) | Deploying and the upgrades on the server, with `scripts/server-upgrade.sh` |

## In short

- Laravel 10 on PHP 8.2, in Docker (`docker-compose.yaml` locally,
  `docker-compose.prod.yaml` on the server).
- The code is in `src/`, grouped by part of the association (`Association`, `Study`,
  `Extern`, `Treasurer`, ...), not in Laravel's default `app/`.
- The stylesheets and scripts are built with Vite from `resources/assets/` into
  `public/build/`.
- The website and the admin share one stylesheet and one design: navy (`#14213d`) and
  crimson (`#a51c30`), with the fonts Inter and Space Grotesk.

## Everyday commands

```sh
docker compose up -d nginx proxy                          # the website (see the readme for the address)
docker compose up vite                                    # rebuilds stylesheets while you work
docker compose run --rm npm npm run build                 # builds stylesheets and scripts once
docker compose exec php vendor/bin/phpunit                # the tests
docker compose exec php vendor/bin/phpstan analyse -c phpstan.neon.dist
docker compose exec php vendor/bin/psalm
docker compose exec php vendor/bin/php-cs-fixer fix       # formats the PHP code
docker compose exec php php artisan view:clear            # after changing Blade components
```
