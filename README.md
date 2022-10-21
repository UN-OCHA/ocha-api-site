# OCHA Api

## Todo

- [Use plain API keys](https://symfony.com/doc/5.2/security/guard_authentication.html) or https://habr.com/ru/post/669590/
- https://symfony.com/doc/current/security/custom_authenticator.html
- https://stackoverflow.com/questions/72441939/symfony-6-apikeyauthenticator-with-selfvalidatingpassport-replaces-guard

## Resources

- [Export as CSV](https://locastic.com/blog/easy-csv-export-in-api-platform)
- [Hide entity from docs](https://api-platform.com/docs/core/operations/#expose-a-model-without-any-routes)


## Helpers

### Entity

```bash
console make:entity --api-resource
console make:migration
console doctrine:migrations:migrate
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
console doctrine:database:drop --force
console doctrine:database:create
console doctrine:schema:create
```

## Security

### API-Key (active)

```bash
console app:add-user username password info@example.com
console lexik:jwt:generate-token info@example.com --user-class="App\Entity\User"
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
