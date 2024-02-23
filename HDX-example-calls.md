# HDX example calls

## Locations

Use `PUT`

```bash
curl -X 'PUT' \
  'https://api-test.docksal.site/api/v1/hdx_locations/1' \
  -H 'accept: application/json' \
  -H 'API-KEY: MY-KEY' \
  -H 'APP-NAME: HDX' \
  -H 'Content-Type: application/json' \
  -d '{
  "id": 1,
  "code": "AFG",
  "name": "Afghanistan",
  "is_historical": false,
  "valid_date": "2023-08-02",
  "hdx_admin1s": []
}'
```

or `POST`

```bash
curl -X 'POST' \
  'https://api-test.docksal.site/api/v1/hdx_locations' \
  -H 'accept: application/json' \
  -H 'API-KEY: MY-KEY' \
  -H 'APP-NAME: HDX' \
  -H 'Content-Type: application/json' \
  -d '{
  "code": "MLI",
  "name": "Mali",
  "is_historical": false,
  "valid_date": "2023-09-21"
}'
```

## Admin levels

Use `'Content-Type: application/ld+json'`

You can either use `"location_ref": "1"` or `"location_ref": "/api/v1/hdx_locations/1"`

```bash
curl -X 'POST' \
  'https://api-test.docksal.site/api/v1/hdx_admin1s' \
  -H 'accept: application/json' \
  -H 'API-KEY: MY-KEY' \
  -H 'APP-NAME: HDX' \
  -H 'Content-Type: application/ld+json' \
  -d '{
  "location_ref": "/api/v1/hdx_locations/1",
  "code": "AF17",
  "name": "Badakhshan",
  "valid_date": "2023-09-21T11:41:22.287Z"
}'
```

```bash
curl -X 'POST' \
  'https://api-test.docksal.site/api/v1/hdx_admin2s' \
  -H 'accept: application/json' \
  -H 'API-KEY: MY-KEY' \
  -H 'APP-NAME: HDX' \
  -H 'Content-Type: application/ld+json' \
  -d '{
  "admin1_ref": "/api/v1/hdx_admin1s/1",
  "code": "AF1702",
  "name": "Argo",
  "valid_date": "2023-09-21T12:02:58.896Z"
}'
```
