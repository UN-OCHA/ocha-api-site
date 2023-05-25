# OCHA Api

## Todo

- ~~Use array for provider on user~~
- ~~Autofilter on provider~~
- ~~Auto build aliases for routes~~
- ~~Add base class for state providers~~
- ~~Add command to add providers~~
- ~~Add user command with providers~~
- ~~Check read/write security~~
- ~~batch endpoint~~
- ~~extra fields as json blob~~
- ~~add service name in header~~

## OPS

### env

- `N8N_WORKFLOW_ENDPOINT="http://192.168.3.20:5678/api/v1"`
- `N8N_WORKFLOW_API_KEY="n8n_api_cd9be...."`

### n8n

n8n needs the following environment variables defined, they will not be visible in the UI.

- `N8N_TEMPLATES_HOST=http://api-test.docksal.site/api/v1/n8n`
- `OCHA_API_URL=http://api-test.docksal.site/api/v1`
- `ACAPS_USERNAME`
- `ACAPS_PASSWORD`

### Workflows

Workflows can be executed from files, no UI needed.

`files` is a mapped directory.

```bash
docker-compose exec -u node n8n n8n execute --file /files/IDPS.json
docker-compose exec -e FTS_YEAR=2019 -u node n8n n8n execute --file /files/FTS.json

docker-compose exec -e CERF_YEAR=2022 -u node n8n n8n execute --file /files/cerf_by_country.json
docker-compose exec -e CERF_YEAR=2021 -u node n8n n8n execute --file /files/cerf_by_country.json
docker-compose exec -e CERF_YEAR=2020 -u node n8n n8n execute --file /files/cerf_by_country.json
docker-compose exec -e CERF_YEAR=2019 -u node n8n n8n execute --file /files/cerf_by_country.json
docker-compose exec -e CERF_YEAR=2018 -u node n8n n8n execute --file /files/cerf_by_country.json
docker-compose exec -e CERF_YEAR=2017 -u node n8n n8n execute --file /files/cerf_by_country.json
docker-compose exec -e CERF_YEAR=2016 -u node n8n n8n execute --file /files/cerf_by_country.json

```

### Add new key figure provider

```bash
console app:add-provider cerf "CERF" cerf key_figures
console app:add-user cerf cerf@example.com cerf cerf cerf
console app:grant-access numbers cerf
console cache:clear
```

### Reset data of a provider

```bash
console dbal:run-sql "delete from key_figures where provider = \"rw_crisis\""
console dbal:run-sql "select id, value from key_figures where year=2022 and iso3=\"afg\" and provider = \"rw_crisis\""
console dbal:run-sql "select * from key_figures where id = \"rw_crisis_AFG_2021_PeopleinNeed\""
```

console dbal:run-sql "update user set webhook = \"https://numbers.reliefweb.int/webhook/listen\" where Username = \"numbers\""
console dbal:run-sql "update user set webhook = \"http://numbers.docksal.site/webhook/listen\" where Username = \"numbers\""

## Resources

- [Export as CSV](https://locastic.com/blog/easy-csv-export-in-api-platform)
- [Hide entity from docs](https://api-platform.com/docs/core/operations/#expose-a-model-without-any-routes)

## Helpers

### Entity

```bash
console make:entity -a InternallyDisplacedPersonsValues
console make:entity --api-resource InternallyDisplacedPersons
console make:migration
console doctrine:migrations:migrate
console app:add-user idps idps@example.com idps --idps
```

### State provider

Use for custom endpoints.

```bash
console make:state-provider
```

### Import command

Use for creating a basic command.

```bash
console make:command
```

## Testing

```bash
fin console doctrine:database:drop --env=test --force
fin console doctrine:database:create --env=test --if-not-exists -n
fin console doctrine:schema:create --env=test -n
fin console hautelook:fixtures:load --env=test -n
phpunit
```

## Providers

```bash
console app:add-provider fts "FTS" fts key_figures
console app:add-provider idps "Internally displaced persons key figures" idps key_figures
console app:add-provider rw_crisis "ReliefWeb Crisis Figures" rw-crisis key_figures
console app:add-provider cbpf "Country-Based Pooled Funds" cbpf key_figures
```

## Security

### API-Key (active)

Admin have read/write access to all resources.

Add users with read permissions.

```bash
console app:add-user fts fts@example.com fts fts
console app:add-user rwcrisis rwcrisis@example.com rwcrisis rw_crisis
console app:add-user idps idps@example.com idps idps
console app:add-user admin admin@example.com admin --admin
```

Add users with read/write permissions.

```bash
console app:add-user idps idps@example.com idps idps idps
console app:add-user cbpf cbpf@example.com cbpf cbpf cbpf
```

### JWT (not enabled)

Install using `composer require lexik/jwt-authentication-bundle`

```bash
console app:add-user username password info@example.com
console lexik:jwt:generate-token info@example.com --user-class="App\Entity\User"
```

## Sources

### FTS

#### Import data

```bash
console import:fts 2022
console import:fts 2021
```

#### API

- `/api/fts/years`
- `/api/fts/countries`
- `/api/fts/country/{iso3}`
- `/api/fts/year/{year}`

### ReliefWeb Crisis Figures

#### Import data

```bash
console import:rw-crisis --all
```

#### API

- `/api/relief_web_crisis_figures/countries`
- `/api/relief_web_crisis_figures/country/{iso3}`

## Conventional changelog

- `composer run changelog` to create changelog
- `composer run release` to create new release
- `composer run release:patch` to create new release and bump patch version
- `composer run release:minor` to create new release and bump minor version
- `composer run release:major` to create new release and bump major version

## New release

On develop branch run the following

```sh {name=changelog}
git fetch --all
today=$(date +%d-%m-%Y)
git checkout -b $today-prep-release
composer run release:patch
git add composer.json
git add CHANGELOG.md
git commit -m "chore: $today prep release"
git push origin $today-prep-release
gh_pr
```

- Merge to dev
- [create PR to merge to main](https://github.com/UN-OCHA/ocha-api-site/compare/main...develop)
- Merge to main
- [Tag a new release](./gh_release)
- Deploy
