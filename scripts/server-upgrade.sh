#!/usr/bin/env bash
#
# Steps for the production server of professorfrancken.nl, see docs/server-upgrade.md.
# Every step shows what it will do and asks before doing it. Run it from the folder of the
# website (/var/www/professorfrancken):
#
#   scripts/server-upgrade.sh backup        backup of the databases, .env and storage
#   scripts/server-upgrade.sh deploy        new code, images, dependencies, build, migrations
#   scripts/server-upgrade.sh env           add GOOGLE_MAPS_EMBED_KEY to .env
#   scripts/server-upgrade.sh redis         Redis 4 -> 7
#   scripts/server-upgrade.sh cleanup       remove what Laravel Mix left behind
#   scripts/server-upgrade.sh mysql-check   show the MySQL version and what to look out for
#   scripts/server-upgrade.sh all           backup, deploy, env, redis and cleanup in a row
#
set -euo pipefail

COMPOSE="docker compose -f docker-compose.prod.yaml"
BACKUP_ROOT="${BACKUP_ROOT:-$HOME/francken-backups}"
GOOGLE_MAPS_EMBED_KEY_VALUE="AIzaSyBmxy9LR0IeIDPmfVY_2ZQOLSbgNz_jDpw"

say() { printf '\n\033[1m%s\033[0m\n' "$*"; }
fail() { printf '\033[31m%s\033[0m\n' "$*" >&2; exit 1; }

# Asks before a step; anything but "y" skips it
confirm() {
    printf '\n%s\nContinue? [y/N] ' "$1"
    read -r answer
    [ "$answer" = "y" ] || [ "$answer" = "Y" ]
}

# A value of .env, without quotes, or empty when it isn't there
env_value() {
    grep -E "^$1=" .env 2>/dev/null | tail -n 1 | cut -d= -f2- | sed -e 's/^"//' -e 's/"$//' -e "s/^'//" -e "s/'$//"
}

check_folder() {
    [ -f docker-compose.prod.yaml ] && [ -f artisan ] || fail "Run this from the folder of the website (/var/www/professorfrancken)."
    [ -f .env ] || fail "There is no .env in $(pwd)."
}

backup() {
    local folder
    folder="$BACKUP_ROOT/$(date +%Y-%m-%d-%H%M)"
    confirm "Backup of the databases, .env, .docker.env and storage/ to $folder" || return 0

    mkdir -p "$folder"
    git rev-parse HEAD > "$folder/commit.txt"
    cp .env "$folder/.env"
    [ -f .docker.env ] && cp .docker.env "$folder/.docker.env"
    tar -czf "$folder/storage.tar.gz" --exclude='storage/framework/cache' --exclude='storage/logs' storage

    local host port user password database legacy_database
    host="$(env_value DB_HOST)"; port="$(env_value DB_PORT)"; port="${port:-3306}"
    user="$(env_value DB_USERNAME)"; password="$(env_value DB_PASSWORD)"
    database="$(env_value DB_DATABASE)"; legacy_database="$(env_value DB_LEGACY_DATABASE)"

    for db in $(printf '%s\n%s\n' "$database" "$legacy_database" | sort -u); do
        [ -n "$db" ] || continue
        say "Database $db"
        if command -v mysqldump >/dev/null 2>&1; then
            MYSQL_PWD="$password" mysqldump --single-transaction --routines --triggers \
                -h "$host" -P "$port" -u "$user" "$db" | gzip > "$folder/$db.sql.gz"
        else
            # Without mysqldump on the server, the one of the MySQL image is used
            docker run --rm --network host -e MYSQL_PWD="$password" mysql:5.7 \
                mysqldump --single-transaction --routines --triggers \
                -h "$host" -P "$port" -u "$user" "$db" | gzip > "$folder/$db.sql.gz"
        fi
        [ -s "$folder/$db.sql.gz" ] || fail "The backup of $db is empty; stopping."
    done

    say "Backup in $folder:"
    ls -lh "$folder"
}

deploy() {
    local branch
    branch="$(git rev-parse --abbrev-ref HEAD)"
    confirm "Deploy: git pull of $branch, rebuild the PHP images, composer install, build with Vite, migrations and restart" || return 0

    say "1/6 git pull"
    git pull --ff-only

    say "2/6 PHP images"
    $COMPOSE build php-base
    # The Dockerfiles start from francken/php-base:v8.2, while the compose file names it :v8
    docker tag francken/php-base:v8 francken/php-base:v8.2 2>/dev/null || true
    $COMPOSE build php scheduler queue-worker

    say "3/6 composer install"
    $COMPOSE run --rm php composer install --no-interaction --optimize-autoloader

    say "4/6 Vite build (Node 22)"
    $COMPOSE run --rm npm sh -c "npm ci && npm run build"

    say "5/6 migrations and caches"
    $COMPOSE run --rm php php artisan migrate --force
    $COMPOSE run --rm php php artisan optimize:clear

    say "6/6 restart"
    $COMPOSE up -d php scheduler queue-worker nginx
    $COMPOSE ps
}

env_key() {
    if grep -q '^GOOGLE_MAPS_EMBED_KEY=' .env; then
        say "GOOGLE_MAPS_EMBED_KEY is already in .env."
        return 0
    fi
    confirm "Add GOOGLE_MAPS_EMBED_KEY to .env (after a copy to .env.backup-$(date +%F))" || return 0

    cp .env ".env.backup-$(date +%F)"
    # Start on a new line when the file doesn't end with one
    [ -n "$(tail -c1 .env)" ] && echo >> .env
    printf 'GOOGLE_MAPS_EMBED_KEY=%s\n' "$GOOGLE_MAPS_EMBED_KEY_VALUE" >> .env
    say "Added. The last lines of .env:"
    tail -n 2 .env
    # The containers read .env when they start
    $COMPOSE up -d php scheduler queue-worker
}

redis() {
    confirm "Restart Redis with the image of docker-compose.prod.yaml ($(grep -A1 '^  redis:' docker-compose.prod.yaml | grep image | awk '{print $2}'))" || return 0
    $COMPOSE pull redis
    $COMPOSE up -d redis
    $COMPOSE exec redis redis-server --version
    $COMPOSE restart queue-worker
}

cleanup() {
    confirm "Remove public/dist/, public/mix-manifest.json and unused Docker images" || return 0
    rm -rf public/dist public/mix-manifest.json
    docker image prune -f
}

mysql_check() {
    local host port user password
    host="$(env_value DB_HOST)"; port="$(env_value DB_PORT)"; port="${port:-3306}"
    user="$(env_value DB_USERNAME)"; password="$(env_value DB_PASSWORD)"

    say "MySQL version"
    if command -v mysql >/dev/null 2>&1; then
        MYSQL_PWD="$password" mysql -h "$host" -P "$port" -u "$user" -N -e 'SELECT VERSION();'
    else
        docker run --rm --network host -e MYSQL_PWD="$password" mysql:5.7 \
            mysql -h "$host" -P "$port" -u "$user" -N -e 'SELECT VERSION();'
    fi
    say "Upgrading MySQL is done separately; see step 6 of docs/server-upgrade.md. Nothing was changed."
}

check_folder
case "${1:-}" in
    backup) backup ;;
    deploy) deploy ;;
    env) env_key ;;
    redis) redis ;;
    cleanup) cleanup ;;
    mysql-check) mysql_check ;;
    all) backup; deploy; env_key; redis; cleanup ;;
    *) sed -n '3,14p' "$0"; exit 1 ;;
esac
