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

### n8n

n8n needs the following environment variables defined, they will not be visible in the UI.

- OCHA_API_URL
- ACAPS_USERNAME
- ACAPS_PASSWORD

### Add new key figure provider

```bash
console app:add-provider cbpf "Country-Based Pooled Funds" cbpf key_figures
console app:add-user cbpf cbpf@example.com cbpf cbpf cbpf
console cache:clear
```

### Reset data of a provider

```bash
console doctrine:query:sql "delete from key_figures where provider = \"cbpf\""
```

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
console doctrine:database:drop --env=test --force
console doctrine:database:create --env=test --if-not-exists -n
console doctrine:schema:create --env=test -n
console hautelook:fixtures:load --env=test -n
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
