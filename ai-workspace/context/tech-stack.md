# 🛠️ Tech Stack - Maroof Project

**Version:** 1.0  
**Last Updated:** February 7, 2026  
**Maintained By:** Claude Desktop

---

## Backend Stack

### Framework
- **Laravel 11** (latest stable)
- **PHP 8.2+**
- **Composer** for dependency management

### Database
- **MySQL 8.0+**
- **Laravel Migrations** for schema management
- **Eloquent ORM** for models and relationships

### Authentication & Authorization
- **Laravel Sanctum** (API token authentication)
- **Spatie Laravel Permission** (roles & permissions)
- **2FA (Two-Factor Authentication)** - optional for sensitive operations

### Payment Processing
- **Stripe** (international payments - Phase 2)
- **HyperPay** (local Saudi payment processor - Phase 1)
- **Apple Pay / Google Pay** support

### File Storage
- **AWS S3** (production)
- **Local storage** (development)
- **Disk management** via Laravel Storage facade

### Notifications
- **Database notifications**
- **Email notifications** (via Mailer)
- **SMS notifications** (optional - via Twilio)
- **WebSocket support** (Laravel Echo + Pusher for real-time updates)

### Search & Analytics
- **Elasticsearch** (optional, Phase 2+)
- **Laravel Analytics** custom implementation
- **Simple tracking** for Phase 1

---

## Frontend Stack

### Web Interface
- **Laravel Blade** (server-side templating)
- **Tailwind CSS** (styling)
- **Alpine.js** (lightweight interactivity)
- **Vite** (asset bundler)

### Mobile (Phase 2+)
- **Flutter** or **React Native** (for reseller app)
- **NFC reading library** for card programming

### API
- **RESTful API** (Laravel routes)
- **JSON responses**
- **CORS enabled** for cross-origin requests

---

## DevOps & Infrastructure

### Hosting (Planned)
- **AWS EC2** or **Digital Ocean** (VPS)
- **Docker** containers (optional)
- **Nginx** web server

### CI/CD
- **GitHub Actions** (automated testing)
- **Laravel Dusk** (browser testing)
- **PHPUnit** (unit testing)

### Monitoring
- **Sentry** (error tracking)
- **New Relic** (performance monitoring)
- **Laravel Telescope** (development debugging)

---

## Development Tools

### Local Development
- **Valet** or **Docker Compose** (local environment)
- **Laravel Tinker** (REPL for debugging)
- **Postman/Insomnia** (API testing)

### Code Quality
- **PHP_CodeSniffer** (code standards)
- **PHPStan** (static analysis)
- **Laravel Pint** (auto-formatting)

### Documentation
- **API Documentation** (via Swagger/OpenAPI)
- **Database Schema Diagrams**
- **Markdown documentation** (in `/docs`)

---

## Third-Party Integrations

### Email Service
- **Mailtrap** (development)
- **SendGrid** or **AWS SES** (production)

### Image Processing
- **Intervention Image** (image manipulation)
- **ImageMagick/GD** backend

### QR Code Generation
- **Bacon/Bacon QR Code** (PHP library)

### NFC Card Programming (Phase 2+)
- **NDEF Library** (for card data encoding)
- **Mobile app integration** required

---

## Security Stack

- **Laravel Security Headers** (HTTPS, CSP, etc.)
- **Password hashing** (bcrypt)
- **API rate limiting** (via middleware)
- **CSRF protection** (built-in)
- **SQL injection prevention** (parameterized queries)
- **XSS prevention** (Blade escaping)

---

## Summary Table

| Category | Tool | Purpose |
|----------|------|---------|
| **Framework** | Laravel 11 | Web application |
| **Database** | MySQL 8.0 | Data persistence |
| **Auth** | Sanctum + Spatie | User management |
| **Payments** | HyperPay + Stripe | Transaction processing |
| **Frontend** | Blade + Tailwind | Web UI |
| **API** | RESTful JSON | Data exchange |
| **Storage** | S3 + Local | File management |
| **Testing** | PHPUnit + Dusk | Code quality |
| **Monitoring** | Sentry + NR | Performance tracking |
