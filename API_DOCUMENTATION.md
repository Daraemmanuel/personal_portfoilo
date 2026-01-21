# API Documentation

This document describes the RESTful API endpoints available for accessing portfolio data.

## Base URL

```
https://yourdomain.com/api/v1
```

## Authentication

Currently, all API endpoints are public and do not require authentication. Rate limiting is applied to prevent abuse.

## Rate Limiting

All API endpoints are rate-limited. The default limit is 60 requests per minute per IP address.

## Response Format

All responses are returned as JSON. Successful responses return a `200 OK` status code.

### Success Response Example

```json
{
  "data": [
    {
      "id": 1,
      "title": "Example Project",
      "description": "Project description",
      "image_url": "https://yourdomain.com/storage/projects/image.jpg",
      "link": "https://example.com",
      "tags": ["Laravel", "Vue.js"],
      "sort_order": 0,
      "is_archived": false,
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-01T00:00:00.000000Z"
    }
  ]
}
```

### Error Response Example

```json
{
  "message": "Too many requests",
  "errors": {}
}
```

## Endpoints

### Projects

#### Get All Projects

Retrieve a list of all active (non-archived) projects.

**Endpoint:** `GET /api/v1/projects`

**Response:**
- `200 OK` - Returns array of projects

**Example Request:**
```bash
curl https://yourdomain.com/api/v1/projects
```

**Example Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "E-commerce Platform",
      "description": "Full-stack e-commerce solution",
      "image_url": "https://yourdomain.com/storage/projects/ecommerce.jpg",
      "link": "https://example.com",
      "tags": ["Laravel", "Vue.js", "MySQL"],
      "sort_order": 0,
      "is_archived": false,
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-01T00:00:00.000000Z"
    }
  ]
}
```

---

### Articles

#### Get All Articles

Retrieve a paginated list of published articles.

**Endpoint:** `GET /api/v1/articles`

**Query Parameters:**
- `per_page` (optional) - Number of articles per page (default: 20, max: 100)

**Response:**
- `200 OK` - Returns paginated articles

**Example Request:**
```bash
curl https://yourdomain.com/api/v1/articles?per_page=10
```

**Example Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Getting Started with Laravel",
      "slug": "getting-started-with-laravel",
      "excerpt": "Learn the basics of Laravel framework",
      "featured_image_url": "https://yourdomain.com/storage/articles/laravel.jpg",
      "category": "Tutorial",
      "tags": ["Laravel", "PHP", "Backend"],
      "published_at": "2025-01-01T00:00:00.000000Z",
      "views": 150,
      "reading_time": 5,
      "series": null,
      "series_order": null,
      "is_featured": false,
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-01T00:00:00.000000Z"
    }
  ],
  "links": {
    "first": "https://yourdomain.com/api/v1/articles?page=1",
    "last": "https://yourdomain.com/api/v1/articles?page=5",
    "prev": null,
    "next": "https://yourdomain.com/api/v1/articles?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 5,
    "path": "https://yourdomain.com/api/v1/articles",
    "per_page": 20,
    "to": 20,
    "total": 100
  }
}
```

#### Get Single Article

Retrieve a single published article by slug, including full content.

**Endpoint:** `GET /api/v1/articles/{slug}`

**Response:**
- `200 OK` - Returns article with content
- `404 Not Found` - Article not found or not published

**Example Request:**
```bash
curl https://yourdomain.com/api/v1/articles/getting-started-with-laravel
```

**Example Response:**
```json
{
  "data": {
    "id": 1,
    "title": "Getting Started with Laravel",
    "slug": "getting-started-with-laravel",
    "excerpt": "Learn the basics of Laravel framework",
    "content": "<p>Full article content in HTML format...</p>",
    "featured_image_url": "https://yourdomain.com/storage/articles/laravel.jpg",
    "category": "Tutorial",
    "tags": ["Laravel", "PHP", "Backend"],
    "published_at": "2025-01-01T00:00:00.000000Z",
    "views": 150,
    "reading_time": 5,
    "series": null,
    "series_order": null,
    "is_featured": false,
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z"
  }
}
```

---

### Skills

#### Get All Skills

Retrieve a list of all skills.

**Endpoint:** `GET /api/v1/skills`

**Response:**
- `200 OK` - Returns array of skills

**Example Request:**
```bash
curl https://yourdomain.com/api/v1/skills
```

**Example Response:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Frontend",
      "icon": "code",
      "items": ["Vue.js", "React", "TypeScript", "Tailwind CSS"],
      "sort_order": 0,
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-01T00:00:00.000000Z"
    }
  ]
}
```

---

### Experiences

#### Get All Experiences

Retrieve a list of all work experiences.

**Endpoint:** `GET /api/v1/experiences`

**Response:**
- `200 OK` - Returns array of experiences

**Example Request:**
```bash
curl https://yourdomain.com/api/v1/experiences
```

**Example Response:**
```json
{
  "data": [
    {
      "id": 1,
      "role": "Senior Frontend Engineer",
      "company": "TechCorp Inc.",
      "period": "2023 — Present",
      "description": "Leading the migration to Vue 3 and TypeScript...",
      "sort_order": 0,
      "created_at": "2025-01-01T00:00:00.000000Z",
      "updated_at": "2025-01-01T00:00:00.000000Z"
    }
  ]
}
```

---

## Legacy Endpoints

For backward compatibility, legacy endpoints are available that redirect to v1:

- `GET /api/projects` → `GET /api/v1/projects`
- `GET /api/articles` → `GET /api/v1/articles`
- `GET /api/articles/{slug}` → `GET /api/v1/articles/{slug}`
- `GET /api/skills` → `GET /api/v1/skills`
- `GET /api/experiences` → `GET /api/v1/experiences`

---

## Error Codes

| Status Code | Description |
|-------------|-------------|
| 200 | Success |
| 404 | Resource not found |
| 429 | Too many requests (rate limited) |
| 500 | Internal server error |

---

## Rate Limit Headers

When rate limiting is active, the following headers are included in the response:

- `X-RateLimit-Limit` - The number of allowed requests
- `X-RateLimit-Remaining` - The number of remaining requests
- `Retry-After` - The number of seconds to wait before retrying (when rate limited)

---

## Changelog

### v1.0.0 (2025-01-01)
- Initial API release
- Projects endpoint
- Articles endpoint (list and single)
- Skills endpoint
- Experiences endpoint
- Rate limiting implementation
- API versioning

---

## Support

For API support or questions, please contact: immanuelatwork@gmail.com

