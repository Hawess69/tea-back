# Tea Backend API Documentation
## Complete Developer Reference for Frontend Team

---

## 📋 **Quick Overview**

This document provides complete API documentation for the Tea Backend platform. All endpoints have been tested and verified to be working correctly.

**Base URLs:**
- **Development**: `http://localhost:8000/api/v1`
- **Production**: `https://api.tea.com/v1` (when deployed)

**Authentication**: Bearer Token (Laravel Sanctum)
**Content-Type**: `application/json`**

---

## 🔐 **Authentication**

### **POST** `/auth/register`
Register a new user account.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+1234567890",
  "password": "Password123!@#",
  "password_confirmation": "Password123!@#"
}
```

**Validation Rules:**
- `name`: Required, string, 2-100 characters
- `email`: Required, valid email, unique, max 150 characters
- `phone`: Optional, string, max 20 characters, unique
- `password`: Required, confirmed, min 8 characters, must contain mixed case, numbers, and symbols

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
  "token": "1|abc123def456..."
}
```

**Error Response (422):**
```json
{
  "message": "This phone number is already registered.",
  "errors": {
    "phone": ["This phone number is already registered."]
  }
}
```

---

### **POST** `/auth/login`
Login with email and password.

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "Password123!@#"
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
  "token": "2|xyz789abc123..."
}
```

**Error Response (401):**
```json
{
  "message": "Invalid credentials"
}
```

---

## 👤 **User Profile**

### **GET** `/profile`
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
    "avatar": null,
    "created_at": "2025-01-27T10:00:00Z"
  }
}
```

---

### **PUT** `/profile`
Update user profile.

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "name": "John Smith",
  "phone": "+0987654321",
  "avatar": "https://example.com/avatar.jpg"
}
```

**Response (200):**
```json
{
  "message": "Profile updated successfully",
  "user": {
    "id": 1,
    "name": "John Smith",
    "phone": "+0987654321",
    "avatar": "https://example.com/avatar.jpg"
  }
}
```

---

## 📝 **Feed Posts**

### **GET** `/feed/posts`
Get paginated list of feed posts.

**Headers:** `Authorization: Bearer <token>`

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
      "image_url": "https://example.com/image.jpg",
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

---

### **POST** `/feed/posts`
Create a new feed post.

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "title": "My Amazing Post",
  "body": "This is the content of my post...",
  "image": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQ..." // Optional base64 image
}
```

**Response (201):**
```json
{
  "message": "Feed post created successfully",
  "post": {
    "id": 1,
    "title": "My Amazing Post",
    "body": "This is the content of my post...",
    "image_url": "https://example.com/storage/posts/image.jpg",
    "upvotes": 0,
    "downvotes": 0,
    "comments_count": 0,
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "created_at": "2025-01-27T10:00:00Z"
  }
}
```

---

### **GET** `/feed/posts/{id}`
Get a single feed post.

**Headers:** `Authorization: Bearer <token>`

**Response (200):**
```json
{
  "post": {
    "id": 1,
    "title": "Post Title",
    "body": "Post content...",
    "image_url": "https://example.com/image.jpg",
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

---

### **POST** `/feed/posts/{id}/vote`
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

---

### **GET** `/feed/posts/{id}/comments`
Get comments for a feed post.

**Headers:** `Authorization: Bearer <token>`

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

---

### **POST** `/feed/posts/{id}/comments`
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

---

## 👨 **Men Posts**

### **GET** `/men/posts`
Get paginated list of men posts.

**Headers:** `Authorization: Bearer <token>`

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
      "photo_url": "https://example.com/photo.jpg",
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

---

### **POST** `/men/posts`
Create a new men post.

**Headers:** `Authorization: Bearer <token>`

**Request Body:**
```json
{
  "full_name": "John Smith",
  "city": "New York",
  "tags": ["gym", "instagram"],
  "caption": "Post caption...",
  "photo": "data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQ..." // Optional base64 image
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
    "photo_url": "https://example.com/storage/men-posts/photo.jpg",
    "flag_counts": {
      "red": 0,
      "green": 0,
      "neutral": 0
    },
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "created_at": "2025-01-27T10:00:00Z"
  }
}
```

---

### **GET** `/men/posts/{id}`
Get a single men post.

**Headers:** `Authorization: Bearer <token>`

**Response (200):**
```json
{
  "post": {
    "id": 1,
    "full_name": "John Smith",
    "city": "New York",
    "tags": ["gym", "instagram"],
    "caption": "Post caption...",
    "photo_url": "https://example.com/photo.jpg",
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

---

### **POST** `/men/posts/{id}/flag`
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

---

### **GET** `/men/posts/{id}/comments`
Get comments for a men post.

**Headers:** `Authorization: Bearer <token>`

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

---

### **POST** `/men/posts/{id}/comments`
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

---

## 🚨 **Alerts**

### **GET** `/alerts`
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

---

### **POST** `/alerts`
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
    "is_active": true,
    "created_at": "2025-01-27T10:00:00Z"
  }
}
```

---

### **DELETE** `/alerts/{id}`
Delete an alert.

**Headers:** `Authorization: Bearer <token>`

**Response (200):**
```json
{
  "message": "Alert deleted successfully"
}
```

---

## 📅 **Events**

### **GET** `/events`
Get upcoming events.

**Headers:** `Authorization: Bearer <token>`

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

---

## 🔔 **Notifications**

### **GET** `/notifications`
Get user notifications.

**Headers:** `Authorization: Bearer <token>`

**Response (200):**
```json
{
  "notifications": [
    {
      "id": 1,
      "type": "alert_matched",
      "title": "Alert Match Found",
      "message": "Someone matching your alert criteria was found!",
      "data": {
        "post_id": 123,
        "user_id": 456
      },
      "read_at": null,
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

---

## 📊 **Error Handling**

### **HTTP Status Codes**
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized (Invalid or missing token)
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `429` - Too Many Requests
- `500` - Internal Server Error

### **Error Response Format**
```json
{
  "message": "Error description",
  "errors": {
    "field_name": ["Validation error message"]
  }
}
```

### **Common Error Examples**

**Validation Error (422):**
```json
{
  "message": "Validation failed",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

**Unauthorized (401):**
```json
{
  "message": "Unauthenticated."
}
```

**Not Found (404):**
```json
{
  "message": "Post not found"
}
```

---

## 🔧 **File Uploads**

### **Supported Image Formats**
- **Formats**: JPEG, PNG, GIF, WebP
- **Max Size**: 5MB
- **Max Dimensions**: 4000x4000px
- **Min Dimensions**: 100x100px

### **Upload Process**
1. Convert image to base64
2. Include in request body as `image` or `photo` field
3. Server processes and stores the image
4. Returns public URL in response

**Example:**
```javascript
// Convert file to base64
const base64 = await fileToBase64(file);

// Send in request
const response = await fetch('/api/v1/feed/posts', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer ' + token,
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    title: 'My Post',
    body: 'Content...',
    image: base64
  })
});
```

---

## 📱 **SDK Examples**

### **JavaScript/React/Node.js**
```javascript
const axios = require('axios');

const api = axios.create({
  baseURL: 'http://localhost:8000/api/v1',
  headers: {
    'Content-Type': 'application/json'
  }
});

// Add token to requests
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('auth_token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Register user
const register = async (userData) => {
  const response = await api.post('/auth/register', userData);
  localStorage.setItem('auth_token', response.data.token);
  return response.data;
};

// Login user
const login = async (credentials) => {
  const response = await api.post('/auth/login', credentials);
  localStorage.setItem('auth_token', response.data.token);
  return response.data;
};

// Get feed posts
const getFeedPosts = async (page = 1) => {
  const response = await api.get(`/feed/posts?page=${page}`);
  return response.data;
};

// Create feed post
const createFeedPost = async (postData) => {
  const response = await api.post('/feed/posts', postData);
  return response.data;
};

// Vote on post
const votePost = async (postId, voteType) => {
  const response = await api.post(`/feed/posts/${postId}/vote`, {
    vote_type: voteType
  });
  return response.data;
};
```

### **React Hook Example**
```javascript
import { useState, useEffect } from 'react';
import axios from 'axios';

const useApi = () => {
  const [token, setToken] = useState(localStorage.getItem('auth_token'));
  
  const api = axios.create({
    baseURL: 'http://localhost:8000/api/v1',
    headers: {
      'Content-Type': 'application/json',
      ...(token && { Authorization: `Bearer ${token}` })
    }
  });

  const login = async (credentials) => {
    const response = await api.post('/auth/login', credentials);
    const { token: newToken } = response.data;
    setToken(newToken);
    localStorage.setItem('auth_token', newToken);
    return response.data;
  };

  const logout = () => {
    setToken(null);
    localStorage.removeItem('auth_token');
  };

  return { api, login, logout, token };
};
```

### **Python Example**
```python
import requests
import json

class TeaAPI:
    def __init__(self, base_url="http://localhost:8000/api/v1"):
        self.base_url = base_url
        self.token = None
    
    def _headers(self):
        headers = {'Content-Type': 'application/json'}
        if self.token:
            headers['Authorization'] = f'Bearer {self.token}'
        return headers
    
    def register(self, user_data):
        response = requests.post(f'{self.base_url}/auth/register', 
                               json=user_data, 
                               headers=self._headers())
        if response.status_code == 201:
            self.token = response.json()['token']
        return response.json()
    
    def login(self, credentials):
        response = requests.post(f'{self.base_url}/auth/login', 
                               json=credentials, 
                               headers=self._headers())
        if response.status_code == 200:
            self.token = response.json()['token']
        return response.json()
    
    def get_feed_posts(self, page=1):
        response = requests.get(f'{self.base_url}/feed/posts?page={page}', 
                              headers=self._headers())
        return response.json()
    
    def create_feed_post(self, post_data):
        response = requests.post(f'{self.base_url}/feed/posts', 
                               json=post_data, 
                               headers=self._headers())
        return response.json()

# Usage
api = TeaAPI()
user = api.register({
    "name": "John Doe",
    "email": "john@example.com",
    "password": "Password123!@#",
    "password_confirmation": "Password123!@#"
})
posts = api.get_feed_posts()
```

---

## 🚀 **Testing & Development**

### **Local Development Setup**
1. Start Laravel server: `php artisan serve`
2. API will be available at: `http://localhost:8000/api/v1`
3. Use the endpoints with proper authentication headers

### **Testing with cURL**
```bash
# Register user
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test User","email":"test@example.com","password":"Password123!@#","password_confirmation":"Password123!@#"}'

# Login
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"Password123!@#"}'

# Get feed posts (with token)
curl -X GET http://localhost:8000/api/v1/feed/posts \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 📋 **API Status & Testing Results**

✅ **All APIs Tested and Working:**
- Authentication (Register/Login) ✅
- User Profile Management ✅
- Feed Posts (CRUD + Voting + Comments) ✅
- Men Posts (CRUD + Flagging + Comments) ✅
- Alerts (CRUD) ✅
- Events (Read) ✅
- Notifications (Read) ✅

**Last Tested:** January 27, 2025
**Status:** All endpoints functional and ready for frontend integration

---

## 📞 **Support & Contact**

- **Backend Developer**: Available for API questions
- **Documentation**: This file is the single source of truth
- **Updates**: This document will be updated as new endpoints are added

---

*This documentation is comprehensive and ready for your frontend team to implement the Tea social platform! 🚀*


