# Tea Backend - Brain Documentation

## 🧠 Central Intelligence System

This `/brain` folder serves as the central intelligence of the Tea Backend Laravel system. It captures everything from architecture decisions and test results to feature plans and deployment checklists.

## 📁 Folder Structure

```
/brain
  /features          # Feature-specific documentation
  /architecture      # System design and ADRs
  /qa               # Testing and quality assurance
  /security         # Security policies and threat models
  /operations       # Deployment and monitoring
  /product          # Product vision and metrics
  /design           # UX/UI and accessibility
  /engineering      # Code standards and best practices
  /retrospectives   # Monthly team reviews
  /project-memory   # Timeline and project state
```

## 🎯 Core Principles

- **Self-Documenting**: Any developer can understand the system by reading the brain
- **Multi-Perspective**: Every decision considers Product, Architecture, Design, Engineering, QA, Security, and Operations
- **Traceability**: Every code change links to brain documentation
- **Quality Gates**: No merge without proper brain updates

## 🚀 Getting Started

1. **New Features**: Start in `/brain/features/[FeatureName]/`
2. **Architecture Changes**: Document in `/brain/architecture/ADRs/`
3. **Security Updates**: Update `/brain/security/`
4. **Deployments**: Follow `/brain/operations/`

## 📊 Current Project State

- **Framework**: Laravel 11 (PHP 8.3)
- **Database**: MySQL 8
- **Authentication**: Laravel Sanctum
- **Admin Panel**: Filament 3
- **Queue System**: Redis + Laravel Horizon
- **File Storage**: AWS S3 / Cloudflare R2

## 🔗 Quick Links

- [System Overview](architecture/system-overview.md)
- [Feature List](features/)
- [Security Policies](security/)
- [Deployment Guide](operations/deployment-instructions.md)
- [Project Timeline](project-memory/timeline.md)

---
*Last Updated: 2025-01-27*
*Maintained by: Development Team*
