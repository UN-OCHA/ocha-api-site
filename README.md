# OCHA Api

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

## Sources

### FTS

#### Import data

```bash
console import:fts 2022
console import:fts 2021
```

#### API

- `/api/fts`
- `/api/fts{id}`
- `/api/fts/country/{iso3}`
- `/api/fts/year/{year}`
