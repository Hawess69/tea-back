# Storage Setup Guide

## Quick Setup

Run the following command to create the symbolic link for public storage:

```bash
php artisan storage:link
```

This creates a symbolic link from `public/storage` to `storage/app/public`, allowing images to be accessible via web URLs.

## Storage Structure

### Expected Directory Structure

```
storage/
└── app/
    └── public/
        ├── posts/
        │   ├── men/
        │   │   └── {filename}.jpg
        │   └── feed/
        │       └── {filename}.jpg
        ├── avatars/
        │   └── {filename}.jpg
        └── events/
            └── {filename}.jpg

public/
└── storage -> ../storage/app/public (symbolic link)
```

### Image URL Format

All images returned by the API follow this format:

**Development:**
```
http://localhost:8000/storage/posts/{type}/{filename}
http://localhost:8000/storage/avatars/{filename}
http://localhost:8000/storage/events/{filename}
```

**Production:**
```
https://your-production-url.com/storage/posts/{type}/{filename}
```

### Example URLs
- Men Post Image: `http://localhost:8000/storage/posts/men/0_1761614498.jpg`
- Feed Post Image: `http://localhost:8000/storage/posts/feed/1_1761614498.jpg`
- User Avatar: `http://localhost:8000/storage/avatars/123_1761614498.jpg`

## Important Notes

1. **Always run `php artisan storage:link`** after cloning the repository or setting up a new environment
2. **Ensure write permissions** on `storage/app/public` directory
3. **The ImageService** uses `Storage::disk('public')->url()` to generate URLs
4. **Flutter Integration**: Images can be directly used with `CachedNetworkImage` widget

## Troubleshooting

### Images Not Loading (404 Error)

1. Check if symbolic link exists:
   ```bash
   ls -la public/storage
   ```

2. If link doesn't exist, create it:
   ```bash
   php artisan storage:link
   ```

3. Verify directory structure:
   ```bash
   ls -la storage/app/public/posts/men/
   ```

### Permission Issues

On Linux/Unix systems, ensure proper permissions:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Images Stored in Wrong Location

If images are in `storage/app/private/public/` instead of `storage/app/public/`:

1. Create the correct directory:
   ```bash
   mkdir -p storage/app/public/posts/men
   ```

2. Move existing images:
   ```bash
   mv storage/app/private/public/posts/men/* storage/app/public/posts/men/
   ```

3. Run the storage link command:
   ```bash
   php artisan storage:link
   ```

## Configuration

The storage configuration is in `config/filesystems.php`:

```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

The symbolic link mapping is defined in the same file:

```php
'links' => [
    public_path('storage') => storage_path('app/public'),
],
```

## Related Files

- `app/Services/ImageService.php` - Image upload and URL generation
- `config/filesystems.php` - Storage configuration
- `FLUTTER_API_DOCUMENTATION.md` - API documentation with image URL format
- `brain/features/MenPosts/bugs.md` - Bug history and fixes

## Best Practices

1. **Use Environment Variables**: Set `APP_URL` in `.env` file
2. **Separate Disks**: Keep private and public storage separate
3. **Image Processing**: Implement image optimization (resize, compress)
4. **Cleanup**: Remove orphaned files when posts are deleted
5. **Security**: Validate file types and scan for malware

## Deployment Checklist

- [ ] Run `php artisan storage:link` on production server
- [ ] Set correct permissions on `storage/app/public`
- [ ] Verify `APP_URL` in `.env` is correct
- [ ] Test image uploads via API
- [ ] Verify images are accessible via URLs
- [ ] Set up image processing/optimization
- [ ] Configure CDN if needed
- [ ] Set up backup strategy for uploaded files

---

*Last Updated: 2025-10-28*

