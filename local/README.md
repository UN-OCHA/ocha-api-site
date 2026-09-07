# Local stack

The `local` folder contains scripts and configuration to create an instance of the OCHA API site locally.

## Setup

1. Rename `local/.env.example` to `local/.env` and edit it to adjust the environment variables. The default should be enough.
2. Ensure the [local reverse proxy](https://github.com/UN-OCHA/local-reverse-proxy/blob/main/setup-notes.md) is running.

`local/shared/config/doctrine.yaml` is mounted as `config/packages/zz_doctrine_local.yaml` and merges over the hosted `when@dev` AWS RDS SSL options so local MySQL can connect without SSL.

`local/shared/config/headers.conf` replaces the image nginx CSP headers so inline scripts (Symfony Web Debug Toolbar, page scripts) work locally. Hosted CSP stays in `docker/etc/nginx/custom/headers.conf`.

## Scripts

**Important:** Run the scripts from the root of the repository.

The script `./local/install.sh` is used to create/stop/remove containers etc. Run `./local/install.sh -h` to see the script options.

The script `./local/exec.sh` is a shortcut for `docker compose -f local/docker-compose.yml exec`

The script `./local/import-db.sh` replaces the local database with a SQL dump (from a file path, a `.sql.gz` file, or stdin). This overwrites whatever is already in the database, including fixtures — do not run `hautelook:fixtures:load` afterward.

```bash
./local/import-db.sh < dump.sql
./local/import-db.sh dump.sql
./local/import-db.sh dump.sql.gz
```

To run additional docker compose commands, use `docker compose -f local/docker-compose.yml` + `command`.

## Create instance

1. Run `./local/install.sh -m -i` to build the local image, start the containers, run migrations and load fixtures.
2. Run `./local/install.sh -d` to install the composer dev dependencies.

The site will be available at https://ocha-api-local.test (via the local reverse proxy).

## Stop/start containers

- Run `./local/install.sh -s` to stop the containers
- Run `./local/install.sh` to start the containers

## Shutdown/recreate containers

- Run `./local/install.sh -x` to stop and remove the containers.
- Run `./local/install.sh -d` to recreate the containers and install the dev dependencies.

**Note:** Run `./local/install.sh -x -v` to completely clean up a local instance (remove containers and volumes). Follow the "create instance" steps above to recreate an instance.

## Update site image

After modifications to the composer files (for example, after the automatic composer update), it is recommended to recreate the local site image:

- Run `./local/install.sh -m -d` to recreate the site image and install the dev dependencies.

## Update service/base images

When a new image used by a service has been created by the OPS team (ex: new mysql or php image):

- Run `./local/install.sh -u -d` to pull the service and base site images, recreate the local site image and the containers and install the dev dependencies.

When an image **with a new tag** has been created, then update the `local/docker-compose.yml` or the `docker/Dockerfile` accordingly before running the update command above.

## Composer

- Install all packages (including dev) with `./local/exec.sh -w /srv/www site composer install`.
- Update all packages (including dev) with `./local/exec.sh -w /srv/www site composer update`.
- Add a package with `./local/exec.sh -w /srv/www site composer require vendor/package`.
- Remove a package with `./local/exec.sh -w /srv/www site composer remove vendor/package`.

## Console

Run Symfony console commands with:

```bash
./local/exec.sh -w /srv/www -u appuser site ./bin/console list
./local/exec.sh -w /srv/www -u appuser site ./bin/console cache:clear
```

Examples from the project README:

```bash
./local/exec.sh -w /srv/www -u appuser site ./bin/console app:add-provider fts "FTS" fts key_figures
./local/exec.sh -w /srv/www -u appuser site ./bin/console app:add-user admin admin@example.com admin --admin
```

## Tests

Run the full CI-equivalent suite (build image, start test stack, install DB/fixtures, lint, PHPUnit):

```bash
./tests/test.sh
```

For a quick PHPUnit run against the already-running local stack:

```bash
./local/exec.sh -w /srv/www -u appuser site ./bin/console doctrine:database:drop --env=test --force
./local/exec.sh -w /srv/www -u appuser site ./bin/console doctrine:database:create --env=test --if-not-exists -n
./local/exec.sh -w /srv/www -u appuser site ./bin/console doctrine:schema:create --env=test -n
./local/exec.sh -w /srv/www -u appuser site ./bin/console netbrothers:version --env=test --drop-version
./local/exec.sh -w /srv/www -u appuser site ./bin/console netbrothers:version --env=test
./local/exec.sh -w /srv/www -u appuser site ./bin/console hautelook:fixtures:load --env=test -n
./local/exec.sh -w /srv/www -u appuser site ./bin/phpunit
```

## Local proxy

Check the [setup-notes](https://github.com/UN-OCHA/local-reverse-proxy/blob/main/setup-notes.md) for first-time set-up of a local reverse proxy.
