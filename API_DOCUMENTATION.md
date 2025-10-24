# Tea Backend API Documentation

## Overview
Complete API documentation for the Tea Backend platform including authentication, endpoints, request/response formats, and examples.

## Base URL
```
Production: https://api.tea.com/v1
Development: http://localhost:8000/api/v1
```

## Authentication
All protected endpoints require a Bearer token in the Authorization header:
```
Authorization: Bearer <your-token>
```

## Response Format
All API responses follow this format:
```json
{
  "message": "Success message",
  "data": { ... },
  "pagination": { ... } // For paginated responses
}
```

## Error Format
```json
{
  "message": "Error message",
  "errors": { ... } // For validation errors
}
```

## Endpoints

### Authentication

#### POST /auth/register
Register a new user account.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  "password": "Password123!",
  "password_confirmation": "Password123!"
}
```

**Response (201):**
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "role": "user",
    "status": "active"
  },
  "token": "1|abc123..."
}
```

#### POST /auth/login
Login with email and password.

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "Password123!"
}
```

**Response (200):**
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  "token": "1|abc123..."
}
```

#### POST /auth/logout
Logout and invalidate token.

**Headers:** `Authorization: Bearer <token>`

**Response (200):**
```json
{
  "message": "Logout successful"
}
```

#### GET /profile
Get authenticated user profile.

**Headers:** `Authorization: Bearer <token>`

**Response (200):**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "role": "user",
    "status": "active",
    "alerts": [...],
    "notifications": [...]
  }
}
```

#### PUT /profile
Update user profile.

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "name": "John Smith",
  "phone": "+0987654321"
}
```

**Response (200):**
```json
{
  "message": "Profile updated successfully",
  "user": {
    "id": 1,
    "name": "John Smith",
    "phone": "+0987654321"
  }
}
```

### Feed Posts

#### GET /feed/posts
Get paginated list of feed posts.

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 15)
- `sort` (optional): Sort by `trending` or `new` (default: trending)

**Response (200):**
```json
{
  "posts": [
    {
      "id": 1,
      "title": "Post Title",
      "body": "Post content...",
      "image_url": "https://...",
      "upvotes": 10,
      "downvotes": 2,
      "comments_count": 5,
      "user": {
        "id": 1,
        "name": "John Doe"
      },
      "created_at": "2025-01-27T10:00:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```

#### POST /feed/posts
Create a new feed post.

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "title": "Post Title",
  "body": "Post content...",
  "image": "base64_encoded_image" // Optional
}
```

**Response (201):**
```json
{
  "message": "Feed post created successfully",
  "post": {
    "id": 1,
    "title": "Post Title",
    "body": "Post content...",
    "upvotes": 0,
    "downvotes": 0,
    "comments_count": 0
  }
}
```

#### GET /feed/posts/{id}
Get a single feed post.

**Response (200):**
```json
{
  "post": {
    "id": 1,
    "title": "Post Title",
    "body": "Post content...",
    "upvotes": 10,
    "downvotes": 2,
    "comments_count": 5,
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "created_at": "2025-01-27T10:00:00Z"
  }
}
```

#### POST /feed/posts/{id}/vote
Vote on a feed post.

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "vote_type": "up" // or "down"
}
```

**Response (200):**
```json
{
  "message": "Vote recorded successfully",
  "upvotes": 11,
  "downvotes": 2
}
```

#### GET /feed/posts/{id}/comments
Get comments for a feed post.

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 15)

**Response (200):**
```json
{
  "comments": [
    {
      "id": 1,
      "body": "Great post!",
      "user": {
        "id": 2,
        "name": "Jane Doe"
      },
      "created_at": "2025-01-27T10:30:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

#### POST /feed/posts/{id}/comments
Add a comment to a feed post.

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "body": "Great post!"
}
```

**Response (201):**
```json
{
  "message": "Comment added successfully",
  "comment": {
    "id": 1,
    "body": "Great post!",
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "created_at": "2025-01-27T10:30:00Z"
  }
}
```

### Men Posts

#### GET /men/posts
Get paginated list of men posts.

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 15)
- `city` (optional): Filter by city
- `tags` (optional): Filter by tags (comma-separated)

**Response (200):**
```json
{
  "posts": [
    {
      "id": 1,
      "full_name": "John Smith",
      "city": "New York",
      "tags": ["gym", "instagram"],
      "caption": "Post caption...",
      "photo_url": "https://...",
      "flag_counts": {
        "red": 5,
        "green": 2,
        "neutral": 1
      },
      "user": {
        "id": 1,
        "name": "John Doe"
      },
      "created_at": "2025-01-27T10:00:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 15,
    "total": 150
  }
}
```

#### POST /men/posts
Create a new men post.

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "full_name": "John Smith",
  "city": "New York",
  "tags": ["gym", "instagram"],
  "caption": "Post caption...",
  "photo": "base64_encoded_image" // Optional
}
```

**Response (201):**
```json
{
  "message": "Men post created successfully",
  "post": {
    "id": 1,
    "full_name": "John Smith",
    "city": "New York",
    "tags": ["gym", "instagram"],
    "caption": "Post caption...",
    "flag_counts": {
      "red": 0,
      "green": 0,
      "neutral": 0
    }
  }
}
```

#### GET /men/posts/{id}
Get a single men post.

**Response (200):**
```json
{
  "post": {
    "id": 1,
    "full_name": "John Smith",
    "city": "New York",
    "tags": ["gym", "instagram"],
    "caption": "Post caption...",
    "photo_url": "https://...",
    "flag_counts": {
      "red": 5,
      "green": 2,
      "neutral": 1
    },
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "created_at": "2025-01-27T10:00:00Z"
  }
}
```

#### POST /men/posts/{id}/flag
Flag a men post.

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "flag_type": "red" // or "green" or "neutral"
}
```

**Response (200):**
```json
{
  "message": "Flag recorded successfully",
  "red_flags": 6,
  "green_flags": 2,
  "neutral_flags": 1
}
```

#### GET /men/posts/{id}/comments
Get comments for a men post.

**Response (200):**
```json
{
  "comments": [
    {
      "id": 1,
      "body": "Great post!",
      "user": {
        "id": 2,
        "name": "Jane Doe"
      },
      "created_at": "2025-01-27T10:30:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

#### POST /men/posts/{id}/comments
Add a comment to a men post.

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "body": "Great post!"
}
```

**Response (201):**
```json
{
  "message": "Comment added successfully",
  "comment": {
    "id": 1,
    "body": "Great post!",
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "created_at": "2025-01-27T10:30:00Z"
  }
}
```

### Alerts

#### GET /alerts
Get user's alerts.

**Headers:** `Authorization: Bearer <token>`

**Response (200):**
```json
{
  "alerts": [
    {
      "id": 1,
      "name": "John Smith",
      "city": "New York",
      "tags": ["gym", "instagram"],
      "is_active": true,
      "created_at": "2025-01-27T10:00:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75
  }
}
```

#### POST /alerts
Create a new alert.

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "name": "John Smith",
  "city": "New York",
  "tags": ["gym", "instagram"]
}
```

**Response (201):**
```json
{
  "message": "Alert created successfully",
  "alert": {
    "id": 1,
    "name": "John Smith",
    "city": "New York",
    "tags": ["gym", "instagram"],
    "is_active": true
  }
}
```

#### DELETE /alerts/{id}
Delete an alert.

**Headers:** `Authorization: Bearer <token>`

**Response (200):**
```json
{
  "message": "Alert deleted successfully"
}
```

### Events

#### GET /events
Get upcoming events.

**Response (200):**
```json
{
  "events": [
    {
      "id": 1,
      "title": "Community Meetup",
      "description": "Join us for a community meetup!",
      "date": "2025-02-15T18:00:00Z",
      "location": "Central Park, New York",
      "max_attendees": 100,
      "current_attendees": 25,
      "created_at": "2025-01-27T10:00:00Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 45
  }
}
```

## Error Codes

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `429` - Too Many Requests
- `500` - Internal Server Error

### Validation Errors
```json
{
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

## Rate Limiting

### Limits
- **API Endpoints**: 60 requests per minute per user
- **Authentication**: 5 attempts per minute per IP
- **File Uploads**: 10 uploads per minute per user

### Headers
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1640995200
```

## File Uploads

### Supported Formats
- **Images**: JPEG, PNG, GIF, WebP
- **Max Size**: 5MB
- **Max Dimensions**: 4000x4000px
- **Min Dimensions**: 100x100px

### Upload Process
1. Encode image as base64
2. Include in request body
3. Server processes and stores
4. Returns public URL

## Webhooks

### Event Notifications
```json
{
  "event": "user.registered",
  "data": {
    "user_id": 1,
    "email": "john@example.com",
    "timestamp": "2025-01-27T10:00:00Z"
  }
}
```

### Available Events
- `user.registered`
- `user.login`
- `post.created`
- `post.flagged`
- `alert.matched`

## SDK Examples

### JavaScript/Node.js
```javascript
const axios = require('axios');

const api = axios.create({
  baseURL: 'https://api.tea.com/v1',
  headers: {
    'Authorization': 'Bearer ' + token
  }
});

// Get feed posts
const posts = await api.get('/feed/posts');

// Create a post
const newPost = await api.post('/feed/posts', {
  title: 'My Post',
  body: 'Post content...'
});
```

### Python
```python
import requests

headers = {'Authorization': f'Bearer {token}'}
base_url = 'https://api.tea.com/v1'

# Get feed posts
response = requests.get(f'{base_url}/feed/posts', headers=headers)
posts = response.json()

# Create a post
new_post = requests.post(f'{base_url}/feed/posts', 
                        json={'title': 'My Post', 'body': 'Content...'}, 
                        headers=headers)
```

### PHP
```php
$client = new GuzzleHttp\Client([
    'base_uri' => 'https://api.tea.com/v1',
    'headers' => ['Authorization' => 'Bearer ' . $token]
]);

// Get feed posts
$response = $client->get('/feed/posts');
$posts = json_decode($response->getBody(), true);

// Create a post
$newPost = $client->post('/feed/posts', [
    'json' => ['title' => 'My Post', 'body' => 'Content...']
]);
```

## Support

### Contact
- **Email**: support@tea.com
- **Documentation**: https://docs.tea.com
- **Status Page**: https://status.tea.com

### Response Times
- **Critical Issues**: <1 hour
- **General Support**: <24 hours
- **Feature Requests**: <1 week


