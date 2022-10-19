# OCHA Api

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
