# ADR-004: Queue System Architecture

**Date:** 2025-01-27  
**Status:** Accepted  
**Context:** We need a reliable queue system for background processing of notifications, image processing, analytics, and other time-consuming tasks. The system must handle high volume and provide monitoring capabilities.  
**Decision:** Use Redis with Laravel Horizon for queue management and monitoring.  
**Alternatives:** 
- Database queues (simple but not scalable)
- Amazon SQS (external dependency, costs)
- RabbitMQ (complex setup, overkill)
- Synchronous processing (poor user experience)

**Consequences:** 
**Positive:**
- High performance and low latency
- Built-in Laravel integration
- Excellent monitoring with Horizon dashboard
- Automatic retry and failure handling
- Job prioritization and rate limiting
- Easy scaling with multiple workers

**Negative:**
- Redis dependency (single point of failure)
- Memory usage for queue storage
- Requires Redis server management
- Job data size limitations

**Implementation:**
- Configure Redis as queue driver
- Install and configure Laravel Horizon
- Create job classes for different tasks
- Implement proper error handling and retries
- Set up monitoring and alerting
- Configure worker processes

**Queue Jobs:**
- `ProcessAlertJob`: Match names in men posts
- `SendNotificationJob`: Push and email notifications
- `ProcessImageJob`: Image upload and processing
- `RecalculateTrendingJob`: Update trending scores
- `SendEventReminderJob`: Event notifications

**Monitoring:**
- Horizon dashboard for job monitoring
- Failed job tracking and retry
- Queue metrics and performance
- Worker health checks
- Job timeout and memory limits

**Scaling Strategy:**
- Multiple worker processes
- Queue prioritization (high, normal, low)
- Rate limiting for external APIs
- Dead letter queue for failed jobs
- Horizontal scaling with multiple servers

**Review Date:** 2025-04-27 (3 months)


