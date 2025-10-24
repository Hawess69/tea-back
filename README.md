# Tea Backend - Laravel 11 API

A comprehensive Laravel 11 backend API for a social platform with feed posts and men posts (reviews/warnings), built for XAMPP environment.

## Features

- **Authentication**: Laravel Sanctum API token authentication
- **Feed Posts**: Reddit-style posts with voting system
- **Men Posts**: Reviews/warnings about men with flagging system
- **Comments**: Polymorphic commenting system for both post types
- **Alerts**: Name tracking system for notifications
- **Events**: Community events management
- **Notifications**: Multi-channel notification system
- **Analytics**: Daily metrics tracking

## Tech Stack

- **Framework**: Laravel 11
- **Database**: MySQL 8 (XAMPP)
- **Authentication**: Laravel Sanctum
- **PHP Version**: 8.2+

## Installation

### Prerequisites

- XAMPP with PHP 8.2+
- MySQL 8
- Composer

### Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd tea-backend
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   - Start XAMPP (Apache + MySQL)
   - Create database: `tea_backend`
   - Update `.env` with your database credentials:
     ```
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=tea_backend
     DB_USERNAME=root
     DB_PASSWORD=
     ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start the development server**
   ```bash
   php artisan serve
   ```

## API Endpoints

### Authentication
- `POST /api/v1/auth/register` - Register new user
- `POST /api/v1/auth/login` - Login user
- `GET /api/v1/profile` - Get user profile (protected)
- `PUT /api/v1/profile` - Update user profile (protected)

### Feed Posts
- `GET /api/v1/feed/posts` - List feed posts (protected)
- `POST /api/v1/feed/posts` - Create feed post (protected)
- `GET /api/v1/feed/posts/{id}` - Get specific feed post (protected)
- `POST /api/v1/feed/posts/{id}/vote` - Vote on feed post (protected)
- `GET /api/v1/feed/posts/{id}/comments` - Get comments (protected)
- `POST /api/v1/feed/posts/{id}/comments` - Add comment (protected)

### Men Posts
- `GET /api/v1/men/posts` - List men posts (protected)
- `POST /api/v1/men/posts` - Create men post (protected)
- `GET /api/v1/men/posts/{id}` - Get specific men post (protected)
- `POST /api/v1/men/posts/{id}/flag` - Flag men post (protected)
- `GET /api/v1/men/posts/{id}/comments` - Get comments (protected)
- `POST /api/v1/men/posts/{id}/comments` - Add comment (protected)

### Alerts
- `GET /api/v1/alerts` - List user alerts (protected)
- `POST /api/v1/alerts` - Create alert (protected)
- `DELETE /api/v1/alerts/{id}` - Delete alert (protected)

### Events
- `GET /api/v1/events` - List events (protected)

### Notifications
- `GET /api/v1/notifications` - List user notifications (protected)

## Database Schema

### Core Entities

1. **Users** - User accounts with roles (user, moderator, admin)
2. **FeedPosts** - Reddit-style community posts
3. **MenPosts** - Reviews/warnings about men
4. **Votes** - Up/down votes for feed posts
5. **Flags** - Red/green/neutral flags for men posts
6. **Comments** - Polymorphic comments for both post types
7. **Alerts** - Name tracking for notifications
8. **Events** - Community events
9. **Notifications** - User notifications
10. **AnalyticsDaily** - Daily metrics

## Test Data

The database is seeded with:
- 10 users (1 admin, 1 moderator, 7 regular users, 1 banned)
- Sample feed posts with voting data
- Sample men posts with flagging data
- Comments across both post types
- User alerts for name tracking
- Community events
- Notification examples

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
php artisan pint
```

### Database Reset
```bash
php artisan migrate:fresh --seed
```

## API Authentication

All protected endpoints require a Bearer token in the Authorization header:

```
Authorization: Bearer {your-token}
```

Get a token by calling the login endpoint with valid credentials.

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests
5. Submit a pull request

## License

This project is licensed under the MIT License.