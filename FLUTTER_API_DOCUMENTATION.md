# Tea Backend API Documentation
## Complete API Reference for Flutter Integration

---

## 📋 **Quick Start**

**Base URLs:**
- **Development**: `http://localhost:8000/api/v1`
- **Production**: `https://your-production-url.com/api/v1` *(Update when deployed)*

**Authentication**: Bearer Token (Laravel Sanctum)

**Content-Type**: `application/json` (for JSON requests)

**Image Upload Content-Type**: `multipart/form-data` (for image uploads)

---

## 🔐 **Authentication**

All endpoints require a Bearer token in the Authorization header for protected routes:
```
Authorization: Bearer <your-token>
```

### **1. Register User**

**Endpoint:** `POST /auth/register`

**Headers:**
```json
{
  "Accept": "application/json",
  "Content-Type": "application/json"
}
```

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
- `password`: Required, min 8 characters, must contain uppercase, lowercase, numbers, and symbols

**Success Response (201):**
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "avatar": null,
    "role": "user",
    "status": "active",
    "created_at": "2025-01-24T10:30:00.000000Z",
    "updated_at": "2025-01-24T10:30:00.000000Z"
  },
  "token": "1|abc123def456..."
}
```

**Error Response (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email has already been taken."],
    "password": ["The password confirmation does not match."]
  }
}
```

---

### **2. Login User**

**Endpoint:** `POST /auth/login`

**Headers:**
```json
{
  "Accept": "application/json",
  "Content-Type": "application/json"
}
```

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "Password123!@#"
}
```

**Success Response (200):**
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "avatar": null,
    "role": "user",
    "status": "active",
    "created_at": "2025-01-24T10:30:00.000000Z",
    "updated_at": "2025-01-24T10:30:00.000000Z"
  },
  "token": "2|xyz789abc123..."
}
```

**Error Response (401):**
```json
{
  "message": "Invalid credentials",
  "errors": {}
}
```

---

### **3. Get Profile**

**Endpoint:** `GET /profile`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Success Response (200):**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "avatar": "https://example.com/avatar.jpg",
    "role": "user",
    "status": "active",
    "created_at": "2025-01-24T10:30:00.000000Z",
    "updated_at": "2025-01-24T10:30:00.000000Z"
  }
}
```

---

### **4. Update Profile**

**Endpoint:** `PUT /profile`

**Headers:**
```json
{
  "Accept": "application/json",
  "Content-Type": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Request Body:**
```json
{
  "name": "Jane Doe",
  "phone": "+0987654321",
  "avatar": "https://example.com/new-avatar.jpg"
}
```

**Validation Rules:**
- `name`: Optional, string, 2-100 characters
- `phone`: Optional, string, max 20 characters, unique
- `avatar`: Optional, string, max 255 characters

**Success Response (200):**
```json
{
  "message": "Profile updated successfully",
  "user": {
    "id": 1,
    "name": "Jane Doe",
    "email": "john@example.com",
    "phone": "+0987654321",
    "avatar": "https://example.com/new-avatar.jpg",
    "role": "user",
    "status": "active",
    "created_at": "2025-01-24T10:30:00.000000Z",
    "updated_at": "2025-01-24T11:00:00.000000Z"
  }
}
```

---

### **5. Get Notifications**

**Endpoint:** `GET /notifications`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 20)

**Success Response (200):**
```json
{
  "notifications": [
    {
      "id": 1,
      "type": "alert_match",
      "data": {
        "alert_id": 5,
        "post_id": 12,
        "name_matched": "John Doe"
      },
      "read_at": null,
      "created_at": "2025-01-24T12:00:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 20,
    "total": 45
  }
}
```

---

## 📝 **Feed Posts**

### **1. List Feed Posts**

**Endpoint:** `GET /feed/posts`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 20)
- `sort` (optional): Sorting method - `trending` (default), `newest`, `top`

**Success Response (200):**
```json
{
  "posts": [
    {
      "id": 1,
      "title": "Example Feed Post",
      "body": "This is the content of the feed post...",
      "image_url": "https://example.com/image.jpg",
      "upvotes": 45,
      "downvotes": 5,
      "comments_count": 12,
      "score": 40,
      "created_at": "2025-01-24T10:00:00.000000Z",
      "updated_at": "2025-01-24T10:00:00.000000Z",
      "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "avatar": null
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 10,
    "per_page": 20,
    "total": 200
  }
}
```

---

### **2. Create Feed Post**

**Endpoint:** `POST /feed/posts`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>",
  "Content-Type": "multipart/form-data"
}
```

**Request Body (Form Data):**
```
title: "My Feed Post Title"
body: "This is the content of my feed post..."
image: [File - OPTIONAL]
```

**Validation Rules:**
- `title`: Required, string, 5-200 characters
- `body`: Required, string, 10-5000 characters
- `image`: Optional, image file (jpeg, png, jpg, gif, webp), max 5MB

**Success Response (201):**
```json
{
  "message": "Feed post created successfully",
  "post": {
    "id": 1,
    "title": "My Feed Post Title",
    "body": "This is the content of my feed post...",
    "image_url": "https://example.com/uploads/feed/image.jpg",
    "upvotes": 0,
    "downvotes": 0,
    "comments_count": 0,
    "score": 0,
    "created_at": "2025-01-24T10:00:00.000000Z",
    "updated_at": "2025-01-24T10:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com"
    }
  }
}
```

---

### **3. Get Single Feed Post**

**Endpoint:** `GET /feed/posts/{id}`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Success Response (200):**
```json
{
  "post": {
    "id": 1,
    "title": "Example Feed Post",
    "body": "This is the content...",
    "image_url": "https://example.com/image.jpg",
    "upvotes": 45,
    "downvotes": 5,
    "comments_count": 12,
    "score": 40,
    "created_at": "2025-01-24T10:00:00.000000Z",
    "updated_at": "2025-01-24T10:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe"
    },
    "votes": [],
    "comments": []
  }
}
```

**Error Response (404):**
```json
{
  "message": "Feed post not found"
}
```

---

### **4. Vote on Feed Post**

**Endpoint:** `POST /feed/posts/{id}/vote`

**Headers:**
```json
{
  "Accept": "application/json",
  "Content-Type": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Request Body:**
```json
{
  "vote_type": "up"
}
```

**Validation Rules:**
- `vote_type`: Required, string, must be `"up"` or `"down"`

**Success Response (200):**
```json
{
  "message": "Vote recorded successfully",
  "upvotes": 46,
  "downvotes": 5
}
```

---

### **5. Get Feed Post Comments**

**Endpoint:** `GET /feed/posts/{id}/comments`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 20)

**Success Response (200):**
```json
{
  "comments": [
    {
      "id": 1,
      "body": "Great post!",
      "post_type": "feed",
      "created_at": "2025-01-24T10:05:00.000000Z",
      "updated_at": "2025-01-24T10:05:00.000000Z",
      "user": {
        "id": 2,
        "name": "Jane Smith",
        "email": "jane@example.com"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 2,
    "per_page": 20,
    "total": 35
  }
}
```

---

### **6. Add Comment to Feed Post**

**Endpoint:** `POST /feed/posts/{id}/comments`

**Headers:**
```json
{
  "Accept": "application/json",
  "Content-Type": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Request Body:**
```json
{
  "body": "Great post! Thanks for sharing."
}
```

**Validation Rules:**
- `body`: Required, string, 1-1000 characters

**Success Response (201):**
```json
{
  "message": "Comment added successfully",
  "comment": {
    "id": 50,
    "body": "Great post! Thanks for sharing.",
    "post_type": "feed",
    "created_at": "2025-01-24T11:00:00.000000Z",
    "updated_at": "2025-01-24T11:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe"
    }
  }
}
```

---

## 👥 **Men Posts**

### **1. List Men Posts**

**Endpoint:** `GET /men/posts`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 20)
- `city` (optional): Filter by city
- `tags` (optional): Filter by tags (comma-separated)
- `name` (optional): Search by name

**Success Response (200):**
```json
{
  "posts": [
    {
      "id": 1,
      "full_name": "John Smith",
      "city": "New York",
      "tags": ["red", "violent", "cheating"],
      "caption": "Be careful with this person...",
      "photo_url": "https://example.com/photo.jpg",
      "flag_counts": {
        "red": 15,
        "green": 2,
        "neutral": 3
      },
      "total_flags": 20,
      "flag_ratio": {
        "red_ratio": 0.75,
        "green_ratio": 0.1,
        "neutral_ratio": 0.15
      },
      "created_at": "2025-01-24T10:00:00.000000Z",
      "updated_at": "2025-01-24T10:00:00.000000Z",
      "user": {
        "id": 1,
        "name": "Jane Doe"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 8,
    "per_page": 20,
    "total": 150
  }
}
```

---

### **2. Create Men Post**

**Endpoint:** `POST /men/posts`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>",
  "Content-Type": "multipart/form-data"
}
```

**Request Body (Form Data):**
```
full_name: "John Smith"
city: "New York"
tags: ["red", "violent", "cheating"]
caption: "Be careful with this person..."
image: [File - OPTIONAL]
```

**Validation Rules:**
- `full_name`: Required, string, 2-150 characters
- `city`: Required, string, 2-100 characters
- `tags`: Optional, array, max 10 tags, each tag max 50 characters
- `caption`: Required, string, 10-2000 characters
- `image`: Optional, image file (jpeg, png, jpg, gif, webp), max 5MB

**Success Response (201):**
```json
{
  "message": "Men post created successfully",
  "post": {
    "id": 1,
    "full_name": "John Smith",
    "city": "New York",
    "tags": ["red", "violent", "cheating"],
    "caption": "Be careful with this person...",
    "photo_url": "https://example.com/uploads/men/photo.jpg",
    "flag_counts": {
      "red": 0,
      "green": 0,
      "neutral": 0
    },
    "created_at": "2025-01-24T10:00:00.000000Z",
    "updated_at": "2025-01-24T10:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe"
    }
  }
}
```

---

### **3. Get Single Men Post**

**Endpoint:** `GET /men/posts/{id}`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Success Response (200):**
```json
{
  "post": {
    "id": 1,
    "full_name": "John Smith",
    "city": "New York",
    "tags": ["red", "violent", "cheating"],
    "caption": "Be careful with this person...",
    "photo_url": "https://example.com/photo.jpg",
    "flag_counts": {
      "red": 15,
      "green": 2,
      "neutral": 3
    },
    "total_flags": 20,
    "created_at": "2025-01-24T10:00:00.000000Z",
    "updated_at": "2025-01-24T10:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe"
    }
  }
}
```

---

### **4. Flag Men Post**

**Endpoint:** `POST /men/posts/{id}/flag`

**Headers:**
```json
{
  "Accept": "application/json",
  "Content-Type": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Request Body:**
```json
{
  "flag_type": "red"
}
```

**Validation Rules:**
- `flag_type`: Required, string, must be `"red"`, `"green"`, or `"neutral"`

**Success Response (200):**
```json
{
  "message": "Flag recorded successfully",
  "red_flags": 16,
  "green_flags": 2,
  "neutral_flags": 3
}
```

---

### **5. Get Men Post Comments**

**Endpoint:** `GET /men/posts/{id}/comments`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 20)

**Success Response (200):**
```json
{
  "comments": [
    {
      "id": 10,
      "body": "I can confirm this behavior",
      "post_type": "men",
      "created_at": "2025-01-24T10:05:00.000000Z",
      "updated_at": "2025-01-24T10:05:00.000000Z",
      "user": {
        "id": 3,
        "name": "Sarah Johnson"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 1,
    "per_page": 20,
    "total": 5
  }
}
```

---

### **6. Add Comment to Men Post**

**Endpoint:** `POST /men/posts/{id}/comments`

**Headers:**
```json
{
  "Accept": "application/json",
  "Content-Type": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Request Body:**
```json
{
  "body": "I can confirm this behavior"
}
```

**Success Response (201):**
```json
{
  "message": "Comment added successfully",
  "comment": {
    "id": 10,
    "body": "I can confirm this behavior",
    "post_type": "men",
    "created_at": "2025-01-24T11:00:00.000000Z",
    "updated_at": "2025-01-24T11:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe"
    }
  }
}
```

---

## 💬 **Comments Management**

### **1. Get Single Comment**

**Endpoint:** `GET /comments/{id}`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Success Response (200):**
```json
{
  "comment": {
    "id": 10,
    "body": "Great comment!",
    "post_type": "feed",
    "created_at": "2025-01-24T10:05:00.000000Z",
    "updated_at": "2025-01-24T10:05:00.000000Z",
    "user": {
      "id": 2,
      "name": "Jane Smith"
    }
  }
}
```

---

### **2. Update Comment**

**Endpoint:** `PUT /comments/{id}`

**Headers:**
```json
{
  "Accept": "application/json",
  "Content-Type": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Request Body:**
```json
{
  "body": "Updated comment text"
}
```

**Validation Rules:**
- `body`: Required, string, 1-1000 characters

**Success Response (200):**
```json
{
  "message": "Comment updated successfully",
  "comment": {
    "id": 10,
    "body": "Updated comment text",
    "post_type": "feed",
    "created_at": "2025-01-24T10:05:00.000000Z",
    "updated_at": "2025-01-24T11:10:00.000000Z",
    "user": {
      "id": 2,
      "name": "Jane Smith"
    }
  }
}
```

**Error Response (403):**
```json
{
  "message": "You can only update your own comments"
}
```

---

### **3. Delete Comment**

**Endpoint:** `DELETE /comments/{id}`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Success Response (200):**
```json
{
  "message": "Comment deleted successfully"
}
```

---

## 🚨 **Alerts**

### **1. List User Alerts**

**Endpoint:** `GET /alerts`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Success Response (200):**
```json
{
  "alerts": [
    {
      "id": 1,
      "name_to_track": "John Smith",
      "is_active": true,
      "created_at": "2025-01-20T10:00:00.000000Z",
      "updated_at": "2025-01-20T10:00:00.000000Z",
      "user": {
        "id": 1,
        "name": "John Doe"
      }
    }
  ]
}
```

---

### **2. Create Alert**

**Endpoint:** `POST /alerts`

**Headers:**
```json
{
  "Accept": "application/json",
  "Content-Type": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Request Body:**
```json
{
  "name_to_track": "John Smith"
}
```

**Validation Rules:**
- `name_to_track`: Required, string, 2-150 characters

**Success Response (201):**
```json
{
  "message": "Alert created successfully",
  "alert": {
    "id": 5,
    "name_to_track": "John Smith",
    "is_active": true,
    "created_at": "2025-01-24T12:00:00.000000Z",
    "updated_at": "2025-01-24T12:00:00.000000Z",
    "user": {
      "id": 1,
      "name": "John Doe"
    }
  }
}
```

---

### **3. Delete Alert**

**Endpoint:** `DELETE /alerts/{id}`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Success Response (200):**
```json
{
  "message": "Alert deleted successfully"
}
```

---

## 🎉 **Events**

### **1. List Events**

**Endpoint:** `GET /events`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Query Parameters:**
- `page` (optional): Page number (default: 1)
- `per_page` (optional): Items per page (default: 20)
- `upcoming` (optional): Filter upcoming events (default: true)

**Success Response (200):**
```json
{
  "events": [
    {
      "id": 1,
      "title": "Community Meetup",
      "description": "Join us for a community meetup...",
      "event_date": "2025-02-01T18:00:00.000000Z",
      "location": "New York City",
      "created_at": "2025-01-15T10:00:00.000000Z",
      "updated_at": "2025-01-15T10:00:00.000000Z",
      "creator": {
        "id": 1,
        "name": "Admin"
      }
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 2,
    "per_page": 20,
    "total": 25
  }
}
```

---

## 🔒 **Admin Endpoints**

*All admin endpoints require admin role in addition to authentication*

### **1. Ban User**

**Endpoint:** `POST /admin/users/{id}/ban`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Request Body:**
```json
{
  "reason": "Violation of terms"
}
```

**Success Response (200):**
```json
{
  "message": "User banned successfully"
}
```

---

### **2. Change User Role**

**Endpoint:** `POST /admin/users/{id}/role`

**Headers:**
```json
{
  "Accept": "application/json",
  "Content-Type": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Request Body:**
```json
{
  "role": "moderator"
}
```

**Success Response (200):**
```json
{
  "message": "User role updated successfully"
}
```

---

### **3. Hide Feed Post**

**Endpoint:** `POST /admin/feed-posts/{id}/hide`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Success Response (200):**
```json
{
  "message": "Feed post hidden successfully"
}
```

---

### **4. Publish Feed Post**

**Endpoint:** `POST /admin/feed-posts/{id}/publish`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Success Response (200):**
```json
{
  "message": "Feed post published successfully"
}
```

---

### **5. Hide Men Post**

**Endpoint:** `POST /admin/men-posts/{id}/hide`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Success Response (200):**
```json
{
  "message": "Men post hidden successfully"
}
```

---

### **6. Publish Men Post**

**Endpoint:** `POST /admin/men-posts/{id}/publish`

**Headers:**
```json
{
  "Accept": "application/json",
  "Authorization": "Bearer <token>"
}
```

**Success Response (200):**
```json
{
  "message": "Men post published successfully"
}
```

---

## 📊 **Error Handling**

All API responses follow a consistent error format:

**Validation Error (422):**
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["Error message 1", "Error message 2"]
  }
}
```

**Authentication Error (401):**
```json
{
  "message": "Unauthenticated."
}
```

**Authorization Error (403):**
```json
{
  "message": "This action is unauthorized."
}
```

**Not Found Error (404):**
```json
{
  "message": "Resource not found"
}
```

**Server Error (500):**
```json
{
  "message": "Error message",
  "error": "Detailed error information"
}
```

---

## 🔑 **Authentication Flow for Flutter**

### **Step 1: Register/Login**
1. Make POST request to `/auth/register` or `/auth/login`
2. Save the returned `token` securely (use `flutter_secure_storage` or similar)
3. Store user data

### **Step 2: Making Authenticated Requests**
Include the token in every request header:
```
Authorization: Bearer <token>
```

### **Step 3: Token Refresh**
If token expires (401 response), redirect user to login screen.

**Example Flutter HTTP Headers:**
```dart
final headers = {
  'Accept': 'application/json',
  'Content-Type': 'application/json',
  'Authorization': 'Bearer $token',
};
```

---

## 📸 **Image Upload Guide**

For endpoints that accept images (feed posts and men posts):

1. Use `multipart/form-data` content type
2. Image fields should be sent as file uploads
3. Supported formats: JPEG, PNG, JPG, GIF, WebP
4. Maximum size: 5MB
5. Image will be automatically processed and stored by the backend

**Example Flutter Multipart Request:**
```dart
var request = http.MultipartRequest(
  'POST',
  Uri.parse('$baseUrl/feed/posts'),
);

request.headers.addAll({
  'Authorization': 'Bearer $token',
});

request.fields['title'] = 'Post Title';
request.fields['body'] = 'Post Content';
request.files.add(
  await http.MultipartFile.fromPath('image', imagePath),
);
```

---

## 🎯 **Important Notes**

1. **Base URL**: Replace with your production URL when deploying
2. **Rate Limiting**: API has rate limiting to prevent abuse
3. **CORS**: Configured for mobile apps, ensure proper domain configuration
4. **Pagination**: Use `page` and `per_page` parameters for large datasets
5. **Dates**: All dates are in ISO 8601 format (UTC timezone)
6. **Token Security**: Store tokens securely, never in plain text
7. **Image Uploads**: Always use multipart/form-data for image uploads

---

## 📱 **Flutter Integration Tips**

1. **HTTP Package**: Use `http` or `dio` for HTTP requests
2. **State Management**: Store token and user data using Provider, Riverpod, or similar
3. **Error Handling**: Implement proper try-catch for all API calls
4. **Loading States**: Show loading indicators during API requests
5. **Offline Support**: Consider caching critical data locally
6. **Image Handling**: Use `image_picker` for image selection and `http` multipart for upload

---

## 📞 **Support**

For issues or questions:
- Check the validation errors in the response
- Verify your token is valid and not expired
- Ensure proper headers are set for each request
- Verify base URL is correct

---

**Last Updated**: January 24, 2025

