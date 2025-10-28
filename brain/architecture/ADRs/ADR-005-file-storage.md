# ADR-005: File Storage Strategy

**Date:** 2025-01-27  
**Status:** Accepted  
**Context:** We need a scalable file storage solution for user uploads (images, avatars) with global CDN access, image processing capabilities, and cost-effective storage.  
**Decision:** Use AWS S3 as primary storage with Cloudflare R2 as backup option, implementing automatic image processing and CDN delivery.  
**Alternatives:** 
- Local file storage (not scalable, no CDN)
- Google Cloud Storage (vendor lock-in)
- Azure Blob Storage (less Laravel integration)
- Self-hosted MinIO (maintenance overhead)

**Consequences:** 
**Positive:**
- Unlimited scalability
- Global CDN with CloudFront
- Built-in Laravel integration
- Automatic image processing
- Cost-effective storage classes
- High availability and durability

**Negative:**
- External dependency
- Data transfer costs
- Vendor lock-in concerns
- Requires AWS knowledge

**Implementation:**
- Configure Laravel filesystem for S3
- Implement image upload endpoints
- Add automatic image processing (resize, blur)
- Set up CloudFront CDN distribution
- Implement file cleanup for deleted posts
- Add backup strategy with R2

**File Types:**
- **User Avatars**: Profile pictures (optimized, resized)
- **Post Images**: Feed and men post images
- **Event Images**: Event cover photos
- **Processed Images**: Blurred versions for men posts

**Security Measures:**
- Pre-signed URLs for secure uploads
- File type validation and scanning
- Virus scanning for uploads
- Access control and permissions
- Automatic cleanup of orphaned files

**Performance Optimization:**
- Image compression and optimization
- Multiple image sizes (thumbnails, medium, full)
- Lazy loading for mobile apps
- CDN caching strategies
- Progressive image loading

**Cost Management:**
- Lifecycle policies for old files
- Storage class optimization
- Data transfer monitoring
- Backup to cheaper storage (R2)

**Review Date:** 2025-04-27 (3 months)


