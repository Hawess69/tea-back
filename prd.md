Overview

The Laravel backend will power:

API for mobile app (Expo frontend)

Secure authentication (Sanctum)

Two social systems:

Feed Posts (Reddit-like posts & discussions)

Men Posts (reviews/warnings about men)

Comments, votes, alerts, and notifications

Admin dashboard for moderation, events, and analytics.

⚙️ Core Stack
Layer	Tech
Framework	Laravel 11 (PHP 8.3)
Database	MySQL 8
Auth	Laravel Sanctum (API tokens)
Admin Panel	Filament 3 (preferred)
File Storage	AWS S3 / Cloudflare R2
Queue	Redis + Laravel Horizon
Image Processing	Intervention Image / Glide
Notifications	Expo Push API + Email (Mailgun/SES)
Caching	Redis for feeds, trending posts
API Docs	OpenAPI / Postman Collection
🧩 Main Entities
1️⃣ Users
Column	Type	Notes
id	BIGINT	Primary
name	VARCHAR(100)	Display name
email	VARCHAR(150)	Nullable
phone	VARCHAR(20)	Nullable
password	VARCHAR	Hashed
avatar	VARCHAR	S3 URL
role	ENUM('user','moderator','admin')	Access control
status	ENUM('active','banned','pending')	
created_at / updated_at	TIMESTAMP	

Relationships

Has many FeedPosts, MenPosts, Comments, Votes, Alerts

2️⃣ Feed Posts (Community / Reddit-style)
Column	Type	Notes
id	BIGINT	Primary
user_id	BIGINT	FK → users
title	VARCHAR(200)	Subject line
body	TEXT	Post content
image_url	VARCHAR	Optional
upvotes	INT	Default 0
downvotes	INT	Default 0
comments_count	INT	Cached count
created_at / updated_at	TIMESTAMP	

Relationships

belongsTo(User)

hasMany(Comment)

hasMany(Vote)

Logic

Voting system (like Reddit):

+1 upvote

-1 downvote

Trending feed = sort by (upvotes - downvotes) / (time decay)

Endpoints

GET /feed/posts

POST /feed/posts

POST /feed/posts/{id}/vote

GET /feed/posts/{id}/comments

POST /feed/posts/{id}/comments

3️⃣ Men Posts (Reports / Reviews)
Column	Type	Notes
id	BIGINT	Primary
user_id	BIGINT	FK → users
full_name	VARCHAR(150)	Name of man
city	VARCHAR(100)	
tags	JSON	e.g. ["gym","instagram","work"]
caption	TEXT	Description or story
photo_url	VARCHAR	Blurred image on S3
flag_counts	JSON	{"red":12,"green":3,"neutral":1}
created_at / updated_at	TIMESTAMP	

Relationships

belongsTo(User)

hasMany(Comment)

hasMany(Flag)

Endpoints

GET /men/posts

POST /men/posts

POST /men/posts/{id}/flag

GET /men/posts/{id}

GET /men/posts/{id}/comments

POST /men/posts/{id}/comments

4️⃣ Votes & Flags

For FeedPosts and MenPosts respectively.

Feed Votes (Up/Down)
Column	Type	Notes
id	BIGINT	Primary
post_id	BIGINT	FK → FeedPosts
user_id	BIGINT	FK → Users
vote_type	ENUM('up','down')	
created_at	TIMESTAMP	
Men Flags
Column	Type	Notes
id	BIGINT	Primary
post_id	BIGINT	FK → MenPosts
user_id	BIGINT	FK → Users
flag_type	ENUM('red','green','neutral')	
created_at	TIMESTAMP	
5️⃣ Comments
Column	Type	Notes
id	BIGINT	Primary
user_id	BIGINT	FK → Users
post_id	BIGINT	FK → FeedPosts or MenPosts
post_type	ENUM('feed','men')	Polymorphic
body	TEXT	Comment body
created_at	TIMESTAMP	
6️⃣ Alerts
Column	Type	Notes
id	BIGINT	Primary
user_id	BIGINT	FK → Users
name_to_track	VARCHAR(150)	“Jake Caldwell”
is_active	BOOLEAN	
created_at	TIMESTAMP	

Logic

When a new MenPost is created, background job checks if any alert name matches (case-insensitive).

Send notification to alert owners.

7️⃣ Events
Column	Type	Notes
id	BIGINT	Primary
title	VARCHAR(150)	
description	TEXT	
location	VARCHAR(200)	
event_date	DATETIME	
image	VARCHAR	S3
created_by	BIGINT	FK → Users
created_at	TIMESTAMP	
8️⃣ Notifications
Column	Type	Notes
id	BIGINT	Primary
user_id	BIGINT	FK → Users
title	VARCHAR(150)	
body	TEXT	
type	ENUM('alert','feed','men','event','comment')	
sent_via	ENUM('expo','email')	
created_at	TIMESTAMP	
🧮 Entity Overview
Users
 ├── FeedPosts
 │    ├── Comments
 │    └── Votes
 ├── MenPosts
 │    ├── Comments
 │    └── Flags
 ├── Alerts
 └── Events

🧰 API Routes Summary
Route	Method	Purpose
/api/v1/auth/register	POST	Register
/api/v1/auth/login	POST	Login
/api/v1/feed/posts	GET	List feed posts
/api/v1/feed/posts	POST	Create feed post
/api/v1/feed/posts/{id}/vote	POST	Vote up/down
/api/v1/feed/posts/{id}/comments	GET/POST	Manage feed comments
/api/v1/men/posts	GET/POST	Manage men posts
/api/v1/men/posts/{id}/flag	POST	Add flag
/api/v1/men/posts/{id}/comments	GET/POST	Manage comments
/api/v1/alerts	GET/POST/DELETE	Manage alerts
/api/v1/events	GET	List events
/api/v1/profile	GET/PUT	User profile
/api/v1/notifications	GET	Notifications list

All secured with Sanctum tokens.

🧮 Database Highlights

Use polymorphic comments to avoid duplicate comment tables.

Use enum columns for clear post type.

Cache top/trending FeedPosts in Redis.

Store photos in S3 with automatic blur processing (can be via queued job).

🛡 Admin Dashboard (Filament)
Sections

Dashboard

Total Users, Active Posts, Flags, Top Trending

Graphs: Feed activity, Red/Green ratio

Users

CRUD, Ban/Unban

Feed Posts

View, Approve/Delete, Pin to top

Men Posts

View, Moderate (approve/reject)

See flag breakdown

Comments

Search by content/user

Delete offensive comments

Alerts

View user alerts, deactivate suspicious ones

Events

Create/Edit/Delete

Notifications

Compose message to all users or a group

Settings

Push API keys, moderation thresholds

Logs

Failed jobs, API errors, moderation history

⚙️ Queue Jobs
Job	Trigger	Action
ProcessAlertJob	New MenPost	Match names, send notifications
SendEventReminderJob	Cron (every 5 min)	Notify attendees before events
RecalculateFeedTrendingJob	Nightly	Update hot/trending score
SendEmailNotificationJob	Admin broadcast	Send email push
🧠 Moderation Tools

Profanity filter (bad words list) for posts/comments

Spam detector (rate limit + content length)

Flag abuse detection (too many flags from same user)

📊 Analytics

Daily metrics stored in analytics_daily

new_users

feed_posts

men_posts

red_flags / green_flags ratio

top cities

total comments

Displayed in Filament dashboard via charts.

✅ Deliverables

Full Laravel API (/api/v1/…)

MySQL schema + migrations

Filament admin dashboard

Queue jobs + workers

API docs (Swagger or Postman)

Seeder for dummy users/posts/events

Integration ready for Expo front-end (JSON REST format)