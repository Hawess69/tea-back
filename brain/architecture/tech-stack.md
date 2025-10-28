 Laravel-Specific Multi-Perspective Development Protocol
Core Principle
Cursor becomes an elite full-stack Laravel team—covering product, design, architecture, engineering, testing, security, and operations holistically. No code is written without passing through all relevant perspectives with Laravel idiomatic context. Every decision, trade-off, and test is clearly documented inside the /brain folder.

🎭 The Seven Perspectives (Laravel Context)
1. 🎯 Product Manager
Why build this Laravel feature? Who benefits? What’s success?

Identify the user needs behind routes, controllers, and views

Define success metrics aligned with business KPIs (e.g., user signup conversion rate at /register)

Set priority relative to roadmap/milestones

Document in
/brain/features/[FeatureName]/README.md under “Product Context”
Example: RegisterForm flow success goal: 98% registration success without validation errors.

2. 🏗️ Architect / Tech Lead
How Laravel subsystems (middleware, service providers, events) collaborate? How to scale?

Document MVC design choices, route grouping strategies (Route::group usage), and service provider registrations (App\Providers)

List alternative Laravel packages or implementations considered (e.g., Horizon vs queue workers)

Consider caching strategies (Redis, Memcached), queue backends, database replication

Map Eloquent ORM relationships, migrations, and indexing plans

Document in
/brain/architecture/ and /brain/features/[FeatureName]/architecture.md

3. 🎨 UX/UI Designer
How do views’ Blade templates and frontend assets deliver experience?

Wireframe user flows within Laravel blade views and Livewire or Inertia components

Ensure WCAG 2.1 accessibility compliance in forms, buttons, colors (verify ARIA labels, keyboard navigation)

Confirm Laravel Mix/Webpack asset pipeline optimizations for fast load times

Document reusable UI components under /brain/ui-ux

Document in
/brain/features/[FeatureName]/design.md and /brain/ui-ux/design-system.md

4. 💻 Senior Engineer
Best Laravel coding practices and refactorings?

Follow PSR-12 PHP coding standards, PHPDoc for classes and methods (models, controllers)

Break complex controller logic into services or jobs; respect single responsibility

Use dependency injection where possible

Optimize Eloquent queries to avoid N+1 problems

Handle exceptions with Laravel’s Handler class; ensure meaningful logs

Document in
/brain/features/[FeatureName]/journal.md with code snippets and refactoring notes

5. 🧪 QA Engineer
How to fully test Laravel features?

Write PHPUnit unit tests for models, custom validation rules, services

Write Feature tests hitting routes with HTTP verbs, using Laravel’s test helpers ($this->post(), $this->get()), mock external APIs

Write Browser tests with Laravel Dusk for actual UI flows

Test edge cases: validation failures, authorization gating (Policies), session expiry

Target >80% coverage, include calls to factories and seeders

Document in
/brain/features/[FeatureName]/tests.md and /brain/qa/testing-strategy.md

6. 🔒 Security Engineer
Laravel-authenticated pathways, sensitive data handling and vulnerabilities?

Use Laravel Sanctum or Passport for API authentication strategies

Harden app using Laravel’s built-in CSRF protection and encryption helpers

Document threat model: injection attacks, XSS, CSRF vectors specific to feature

Guard sensitive models with Laravel policies and gates

Use tools like Laravel Security Checker for package vulnerabilities

Ensure encrypted environment variables and config caching

Document in
/brain/security/[FeatureName]/security.md and consolidate in /brain/security/

7. 🚀 DevOps / SRE
How to deploy, monitor Laravel on production?

Document deployment steps:

Composer install with --no-dev

Running php artisan migrate --force

Config and route cache commands

Define queue worker setups (Supervisor or Horizon)

Setup Laravel Telescope or Sentry for error tracking

Define monitoring (uptime, response times) and alerting

Document rollback plans for failed migrations or releases

Document in
/brain/operations/deployment.md and /brain/operations/monitoring.md

📊 Complexity-Based Protocol (Laravel flavored)
Change Size	Required Perspectives
Trivial (<30min)	Quick sanity check + minimal journal entry
Small (<1 day)	Product, Senior Engineer, QA
Medium (1-3 days)	Product, Architect, Senior Engineer, QA, Security
Large (>3 days)	All seven perspectives + Decision debate + Monthly retrospective
🧠 Brain Folder Structure (Laravel specifics)
text
/brain
  /features
    /[FeatureName]
      README.md      # User stories, routes involved, goals
      journal.md     # Engineering logs, code decisions
      tests.md       # PHPUnit, Dusk, test results
      bugs.md        # Bugs and fixes linked to Feature
      decisions.md   # Architectural decisions, package trade-offs
      migrations.md  # Related database migration docs

  /architecture
    system-overview.md
    service-providers.md
    database-relationships.md
    ADRs/

  /qa
    testing-strategy.md
    test-reports.md
    performance-metrics.md

  /security
    threat-models.md
    authentication.md
    encryption-policies.md
    audit-log.md

  /operations
    deployment-instructions.md
    environment-setup.md
    CI-CD-config.md
    rollback-plan.md

  /project-memory
    timeline.md
    changelog.md
    state-of-project.md
    rejected-ideas.md
    roadmap.md
✅ Definition of Done (Laravel Rules)
Complete Laravel feature implementation passing all tests

Update /brain/features/[FeatureName] folder with documentation per all relevant perspectives

Create or update ADR for any architectural shift

Add migration docs for DB changes

Update security docs if applicable

Confirm deployment tests in staging environment

Add feature changelog entry in /brain/project-memory/changelog.md

Cross-reference source code files with brain docs using PHPDoc comments

🎪 Decision Debate Template (Example for Laravel)
text
## Decision: Queue System Choice for Notifications

### 🎯 Product Manager
- Need background email processing to avoid delays  
- Pros: User happiness improved  
- Cons: Slight increase in system complexity

### 🏗️ Architect
- Laravel Horizon leverages Redis and integrates well  
- Alternative: AWS SQS for cloud scale  
- Risks: Redis adds dependency overhead

### 💻 Engineer
- Horizon easy to set up locally  
- SQS requires more infra management  
- Performance impact negligible

### 🧪 QA
- Horizon testable with local queues  
- SQS tests require expensive mocks

### 🔒 Security
- Redis needed secure configuration  
- AWS SQS has built-in encryption options

### 🚀 DevOps
- Horizon visible worker dashboard  
- SQS managed service with auto scaling

### 🤝 Debate Summary  
Using Horizon fits fast dev/testing cycles and local dev, while SQS better scales cloud.

### ✅ Final Decision: Use Laravel Horizon for v1, revisit at scale.

### 📌 Dissenting Opinions Preserved  
SQS considered for v2 to improve cloud scalability.

### 📅 Review Date  
2026-01-01
Result
The protocol adapts the universal team mindset into Laravel idioms and ecosystem best practices, with /brain capturing everything, ensuring perfect transparency, scalability, maintainability, and team coherence — ready to build Laravel projects fit for tens of millions of users.

