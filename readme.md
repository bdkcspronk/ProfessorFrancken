|            |            |
| ------------- |:-------------:|
| <a href="https://professorfrancken.nl"><img width="200px" alt="Logo of T.F.V. Professor Francken" src="https://github.com/ProfessorFrancken/ProfessorFrancken/blob/master/public/images/LOGO_KAAL.png"></a> | <h1>T.F.V. 'Professor Francken'</h1> [![Build Status](http://github.scriptcie.nl/api/badges/ProfessorFrancken/ProfessorFrancken/status.svg)](http://github.scriptcie.nl/ProfessorFrancken/ProfessorFrancken) [![Coverage Status](https://coveralls.io/repos/github/ProfessorFrancken/ProfessorFrancken/badge.svg?branch=master)](https://coveralls.io/github/ProfessorFrancken/ProfessorFrancken?branch=master) |

This is the repository containing code for the new website of
[T.F.V. Porfessor Francken](http://professorfrancken.nl/).
We are using the [Laravel v6.0](http://laravel.com/docs/6.0) framework.

You can find some high quality introductory videos on
[laravelfromscratch.com](laravelfromscratch.com).


- [Getting started](#getting-started)
    - [Summary](#summary)
- [Contributing](#contributing)
    - [Generating css (or compiling assets)](#generating-css-or-compiling-assets)
        - [Windows configuration](#windows-configuration)
    - [Testing](#testing)
    - [Code style](#code-style)
    - [Git Usage](#git-usage)

## Getting started

If you want to get started working on our website, first clone the repository
using git. Next copy the `.env.example` file and name the copy `.env`.

The following instructions assume that you've
installed [Docker](https://www.docker.com/products/docker#/linux)
and [docker-compose](https://docs.docker.com/compose/install/).
If you haven't yet, follow the links and install Docker.

Next you will want to download our php and javascript dependencies. Using you
terminal run `docker compose run php composer install` and `docker compose run npm npm ci`
in the root of the project, and build the stylesheets and scripts with
`docker compose run npm npm run build`.
Once you've installed the dependencies run `docker-compose run php php artisan
key:generate` to generate an application key.
Next run `docker-compose run php php artisan migrate:refresh --seed` to setup a
sqlite database and lastly run `docker-compose up nginx` to start the website.

You can now visit the website at [localhost](http://localhost).


### Summary

Run the following commands to setup and start the website.

```sh
git clone git@github.com:ProfessorFrancken/ProfessorFrancken.git Francken && cd Francken

cp .env.example .env

docker compose run php composer install
docker compose run npm npm install
docker compose run npm npm run build
docker compose run php php artisan key:generate
docker compose run php php artisan migrate:refresh --seed
docker compose up nginx proxy
```

## Contributing
Before you push your changes to this repository make sure that the tests are all
green and that there are no issues with code style.

Below you will find a quick summary on how to run the tests and a code style
fixer.

### Generating css (or compiling assets)

We use [scss](http://sass-lang.com/) which compiles to css.

To compile our assets you will first have to install some necessary javascript
dependencies. You can do this using [npm](npmjs.com).
Assuming you've installed npm locally you can run the command,
```
npm install
```
Alternatively if you prefer to use a docker container (that is if you have
installed Docker but don't have npm installed), then you can run
`docker-compose run npm npm install`.


Once you've installed the javascript dependencies you can compile our assets by
running
```
npm run build
```
in the root of this project (the folder in which a `package.json` file  is
present), or alternatively use `docker compose run npm npm run build`.
The compiled files will be placed in the `/public/build` folder. This folder is
added to our `.gitignore` so you won't see the folder until you run the above
command.

While working on the stylesheets you can instead start the development server of
Vite with `npm run dev`, or `docker compose up vite`, which updates the pages in
the browser as soon as a stylesheet changes. While it runs the pages load the
stylesheets from it; when it stops they load the built ones again.

Check the documentation of [Vite](https://vite.dev) and
[Laravel's Vite integration](https://laravel.com/docs/9.x/vite) to learn more
about how our assets are compiled.

#### Windows configuration

I haven't yet tested whether this works on Windows.. If you're using Docker
(which you should :-)) then the docker commands should work.

### Testing
We use [phpunit](https://phpunit.de/) for most of our tests. To execute the
tests using Docker run,
```
docker-compose run php vendor/bin/phpunit
```

### Code style
We're using the PSR-2 code style guidelines as well as PSR-4 for autoloading classes.
See the [PHP-fig](http://www.php-fig.org/psr/) for more info on the standards.
You can use `fabpot/php-cs-fixer` to verify that your code conforms to the PSR-2
code style guidelines. By running the following command in the root directory
all code style issues be found.
```
vendor/bin/php-cs-fixer fix . --level=psr2 --dry-run --fixers=-psr0
```
By removing the `--dry-run` option any issues found by the program will be fixed
automatically. Add the `--diff` option to show the diff for each file.
N.B. once we've gone open source we will use a continuous integration service
that automatically checks whether your PR conforms to the code style guidelines.

*Note* we omit the `psr0` fixer. This fixer looks at the namespace declarations
however it is not compatible with PSR-4, which is an improvement over PSR-0.

### Deploying
The production server runs the website with `docker-compose.prod.yaml`. Its PHP containers
read their settings from `.docker.env` and `.env` in the root of the project on the server.

After pulling a new version on the server, these are the usual steps (check them against how
the website has been deployed so far):

1. Install the PHP dependencies:
   `docker compose -f docker-compose.prod.yaml run --rm php composer install`
2. Build the stylesheets and scripts with Vite (Node 20.19 or newer, which the `npm` service
   of `docker-compose.prod.yaml` has):
   `docker compose -f docker-compose.prod.yaml run --rm npm sh -c "npm ci && npm run build"`.
   The files are placed in `public/build/`.
3. Run the migrations:
   `docker compose -f docker-compose.prod.yaml run --rm php php artisan migrate --force`
4. Clear the caches:
   `docker compose -f docker-compose.prod.yaml run --rm php php artisan optimize:clear`

#### Settings in `.env`
These settings in the `.env` of the server are needed besides those of a fresh install
(see `.env.example` for all of them):

- `GOOGLE_MAPS_EMBED_KEY`: the key of the Google Maps Embed API, for the map of an
  activity in the admin. Without it the location is shown without a map; nothing breaks.
  The key is in `.env.example`.

#### Once, when switching from Laravel Mix to Vite
The stylesheets used to be built with Laravel Mix (`npx mix build`) into `public/dist/`.
Since the switch to Vite they are built into `public/build/` (step 2 above), and
`public/dist/` and `public/mix-manifest.json` can be removed from the server.
Optionally, nginx can let browsers keep the files in `/build/` for a year, as their names
change whenever their contents change:

```
location /build/ {
    expires 1y;
    add_header Cache-Control "public, immutable";
}
```

### Git Usage
The master branch is a protected branch, meaning you won't be able to force push changes to the master branch and status checks (i.e. tests should be green) are required before merging to master.
Before sending a pull request with your latest changes you should make sure that your branch is up to date by rebasing your branch onto master.
This makes it easier to review your pull request since there won't be many merge commits and it gives us a nice linear history.
