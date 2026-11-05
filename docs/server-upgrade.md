# Upgrading the server

This is the plan for the production server of professorfrancken.nl, for deploying the
`new-style-next` branch and for the upgrades that can only happen on the server itself.
`scripts/server-upgrade.sh` does the steps one by one, asks before each step and makes a
backup first. Nothing in this file has to happen at once: the steps are ordered from
necessary to optional.

The website runs with `docker-compose.prod.yaml` in `/var/www/professorfrancken`. The PHP
containers read their settings from `.docker.env` and `.env` in that folder. The database
is not part of `docker-compose.prod.yaml`, so it runs outside of Docker.

## Before you start

- Do it at a quiet moment, like a weekday morning, not right before an activity with
  sign-ups.
- Make sure there is room for the backup: `df -h /var/www`.
- Read the step before running it. Every step can be skipped and run later.

## The steps

### 1. Backup (always)

`scripts/server-upgrade.sh backup`

Saves the databases (`DB_DATABASE` and `DB_LEGACY_DATABASE` of `.env`), `.env`,
`.docker.env` and `storage/` in `~/francken-backups/<date>/`. The uploads in `public/uploads`
are not copied, as they don't change during the upgrade and are large.

**Undo:** nothing to undo.

### 2. Deploy the new code

`scripts/server-upgrade.sh deploy`

1. `git pull` of the branch that is deployed.
2. Rebuild the PHP images (the PHP version of `docker/php/Dockerfile-base` may have changed).
3. `composer install`.
4. Build the stylesheets and scripts with Vite (Node 22) into `public/build/`.
5. Run the migrations and clear the caches.
6. Restart the containers.

**Undo:** `git checkout <previous commit>` and run the step again. The previous commit is
written in the backup folder (`commit.txt`). If a migration went wrong: restore the database
from the backup (see "Restoring a backup" below).

### 3. New setting in `.env`

`scripts/server-upgrade.sh env`

Adds `GOOGLE_MAPS_EMBED_KEY` to `.env` if it isn't there, after a backup of `.env`. Without it
the map of an activity in the admin is left out; nothing breaks.

**Undo:** `cp ~/francken-backups/<date>/.env .env`

### 4. Redis 4 → 7

`scripts/server-upgrade.sh redis`

`docker-compose.prod.yaml` now uses `redis:7-alpine`. Redis only keeps the cache, the
sessions and the queue of jobs. Redis 7 reads the data of Redis 4, so nobody is logged out.

**Undo:** set the image back to `redis:4-alpine` in `docker-compose.prod.yaml` and run
`docker compose -f docker-compose.prod.yaml up -d redis`.

### 5. Clean up after the switch to Vite

`scripts/server-upgrade.sh cleanup`

Removes `public/dist/` and `public/mix-manifest.json`, which Laravel Mix made and nothing
uses anymore, and unused Docker images.

### 6. MySQL 5.7 → 8 (separately, later)

MySQL 5.7 has had no security updates since October 2023. As the database doesn't run in
Docker, how to upgrade depends on how it was installed. `scripts/server-upgrade.sh mysql-check`
only shows the version and what to look out for; it doesn't change anything.

The general approach:

1. Make a backup (step 1) and check that it can be restored on another machine.
2. Check for problems with MySQL 8 with the MySQL Shell upgrade checker:
   `mysqlsh -- util checkForServerUpgrade user@localhost:3306`
3. Upgrade the package of the server (for example with `apt`), or start MySQL 8 next to
   it and import the backup.
4. Test the website, the admin and the consumption counter (PlusOne) before removing the
   old installation.

Things to look out for with MySQL 8: the default character set is `utf8mb4`, the default
authentication is `caching_sha2_password` (PHP 8 supports it) and some words are reserved,
like `rank` and `groups`.

**Undo:** the backup of step 1.

### 7. Optional: caching of `/build/`

The files in `public/build/` get a new name whenever they change, so browsers can keep them for
a year. In the nginx config of the server (`nginx/francken.conf`, not in git):

```
location /build/ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```

Then: `docker compose -f docker-compose.prod.yaml restart nginx`.

## Restoring a backup

```
cd /var/www/professorfrancken
cp ~/francken-backups/<date>/.env .env
gunzip < ~/francken-backups/<date>/<database>.sql.gz | mysql -u <user> -p <database>
git checkout "$(cat ~/francken-backups/<date>/commit.txt)"
scripts/server-upgrade.sh deploy
```
