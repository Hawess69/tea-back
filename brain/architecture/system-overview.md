# System Overview

## 🏗️ High-Level Architecture

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Mobile App    │    │   Admin Panel   │    │   External APIs │
│   (Expo/React)  │    │   (Filament)    │    │   (Push, S3)    │
└─────────┬───────┘    └─────────┬───────┘    └─────────┬───────┘
          │                      │                      │
          └──────────────────────┼──────────────────────┘
                                 │
                    ┌─────────────▼─────────────┐
                    │     Laravel Backend       │
                    │   (API + Admin Routes)     │
                    └─────────────┬─────────────┘
                                 │
                    ┌─────────────▼─────────────┐
                    │      Service Layer        │
                    │  (Business Logic)        │
                    └─────────────┬─────────────┘
                                 │
                    ┌─────────────▼─────────────┐
                    │      Data Layer          │
                    │  (Models + Repositories) │
                    └─────────────┬─────────────┘
                                 │
                    ┌─────────────▼─────────────┐
                    │      Infrastructure      │
                    │  (MySQL + Redis + S3)    │
                    └──────────────────────────┘
```

## 🎯 Core Components

### 1. API Layer
- **Authentication**: Laravel Sanctum (API tokens)
- **Routes**: RESTful API endpoints (`/api/v1/`)
- **Middleware**: CORS, rate limiting, authentication
- **Validation**: Form Request classes

### 2. Service Layer
- **Business Logic**: Feature-specific services
- **Queue Jobs**: Background processing
- **Event Listeners**: Decoupled logic
- **Notifications**: Push and email

### 3. Data Layer
- **Models**: Eloquent ORM with relationships
- **Repositories**: Data access abstraction
- **Migrations**: Database schema management
- **Seeders**: Test data generation

### 4. Infrastructure
- **Database**: MySQL 8 with optimized indexes
- **Cache**: Redis for sessions and feeds
- **Storage**: AWS S3 for file uploads
- **Queue**: Redis + Laravel Horizon

## 🔄 Request Flow

1. **Mobile App** → API Request with Sanctum token
2. **Middleware** → Authentication, CORS, rate limiting
3. **Controller** → Route to appropriate controller
4. **Service** → Business logic processing
5. **Model** → Database operations
6. **Response** → JSON API response

## 🏛️ Domain Boundaries

### User Management
- Registration, authentication, profiles
- Role-based access control
- User status management

### Social Features
- **Feed Posts**: Reddit-like community posts
- **Men Posts**: Review/warning system
- **Comments**: Threaded discussions
- **Voting/Flags**: User engagement

### Content Moderation
- Automated filtering
- Admin dashboard controls
- Flag abuse detection
- Content approval workflows

### Analytics & Events
- Daily metrics collection
- Event management
- Notification system
- Alert tracking

## 🔗 Key Relationships

```
Users
├── FeedPosts (1:many)
│   ├── Comments (1:many)
│   └── Votes (1:many)
├── MenPosts (1:many)
│   ├── Comments (1:many)
│   └── Flags (1:many)
├── Alerts (1:many)
└── Events (many:many)
```

## 🚀 Scalability Considerations

- **Horizontal Scaling**: Stateless API design
- **Database Optimization**: Proper indexing and query optimization
- **Caching Strategy**: Redis for hot data
- **Queue Processing**: Background job processing
- **CDN Integration**: S3 for static assets

## 🔒 Security Architecture

- **Authentication**: Sanctum API tokens
- **Authorization**: Policies and gates
- **Data Protection**: Encryption for sensitive data
- **Rate Limiting**: API abuse prevention
- **Input Validation**: XSS and injection prevention

---
*Last Updated: 2025-01-27*
*Architecture Version: 1.0*


