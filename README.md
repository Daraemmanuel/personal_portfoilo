# Personal Portfolio

A modern, full-featured portfolio website built with Laravel, Inertia.js, Vue.js, and TypeScript.

## Features

### Core Features

- **Modern Tech Stack**: Laravel 12, Inertia.js, Vue 3, TypeScript, Tailwind CSS
- **Admin Panel**: Full CRUD interface for managing projects, skills, experiences, testimonials, and articles
- **Authentication**: Laravel Fortify with 2FA support
- **Server-Side Rendering**: SSR support for better SEO and performance
- **Responsive Design**: Mobile-first, fully responsive design

### Portfolio Features

- **Projects Showcase**: Display your work with images, descriptions, tags, and links
- **Skills Section**: Organize and display your technical skills
- **Experience Timeline**: Showcase your professional journey
- **Testimonials**: Display client/colleague testimonials
- **Blog/Articles**: Full-featured blog system with categories and tags
- **Contact Form**: Functional contact form with spam protection and rate limiting

### SEO & Performance

- **SEO Optimized**: Open Graph tags, Twitter Cards, structured data (JSON-LD)
- **Sitemap**: Dynamic XML sitemap generation
- **RSS Feed**: RSS feed for blog articles
- **Image Optimization**: Lazy loading and responsive images
- **Caching**: Database query caching for improved performance
- **Analytics**: Google Analytics integration

### Security

- **Security Headers**: XSS protection, frame options, content type options
- **Rate Limiting**: Built-in rate limiting on routes
- **CSRF Protection**: Laravel's built-in CSRF protection
- **Honeypot**: Spam protection on contact form
- **Input Validation**: Comprehensive validation on all forms

### Developer Experience

- **TypeScript**: Full TypeScript support with proper type definitions
- **Error Pages**: Custom 404 and 500 error pages
- **Testing**: Pest PHP testing framework setup
- **Code Quality**: ESLint, Prettier, and PHP CS Fixer configured

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (default) or MySQL/PostgreSQL

## Installation

1. **Clone the repository**

    ```bash
    git clone <repository-url>
    cd personal_portfoilo
    ```

2. **Install PHP dependencies**

    ```bash
    composer install
    ```

3. **Install Node dependencies**

    ```bash
    npm install
    ```

4. **Environment setup**

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

5. **Database setup**

    ```bash
    # For SQLite (default)
    touch database/database.sqlite

    # Or configure MySQL/PostgreSQL in .env
    ```

6. **Run migrations**

    ```bash
    php artisan migrate
    ```

7. **Create storage link**

    ```bash
    php artisan storage:link
    ```

8. **Build assets**
    ```bash
    npm run build
    ```

## Development

### Start development server

```bash
composer run dev
```

This will start:

- Laravel development server (http://localhost:8000)
- Queue worker
- Vite dev server

### Start with SSR

```bash
composer run dev:ssr
```

### Run tests

```bash
composer test
# or
php artisan test
```

### Code formatting

```bash
# PHP
composer pint

# JavaScript/TypeScript
npm run format
npm run lint
```

## Configuration

### Environment Variables

Key environment variables to configure:

- `APP_NAME`: Application name
- `APP_URL`: Your application URL
- `APP_ENV`: Environment (local, production)
- `APP_DEBUG`: Debug mode (false in production)
- `DB_CONNECTION`: Database driver (sqlite, mysql, pgsql)
- `MAIL_MAILER`: Mail driver (log, smtp, etc.)
- `GOOGLE_ANALYTICS_ID`: Google Analytics tracking ID (optional)

See `.env.example` for all available options.

### Mail Configuration

Configure your mail settings in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Google Analytics

Add your Google Analytics ID to `.env`:

```env
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin controllers
│   │   └── ContactController.php
│   └── Middleware/          # Custom middleware
├── Models/                  # Eloquent models
resources/
├── js/
│   ├── components/          # Vue components
│   │   └── landing/        # Landing page components
│   ├── pages/               # Inertia pages
│   │   ├── Admin/           # Admin pages
│   │   ├── Articles/        # Article pages
│   │   └── Errors/          # Error pages
│   └── types/               # TypeScript types
database/
├── migrations/              # Database migrations
└── seeders/                 # Database seeders
routes/
└── web.php                  # Web routes
```

## Admin Panel

Access the admin panel at `/dashboard` after logging in.

### Features:

- **Projects Management**: Create, edit, delete projects with images
- **Skills Management**: Organize technical skills
- **Experiences Management**: Manage work experience
- **Testimonials Management**: Add and manage testimonials
- **Articles Management**: Full blog management system
- **Contact Messages**: View and manage contact form submissions

## API Routes

The application includes the following public routes:

- `GET /` - Homepage
- `GET /projects/{id}` - Project detail page
- `GET /articles` - Articles listing
- `GET /articles/{slug}` - Article detail
- `POST /contact` - Submit contact form
- `GET /sitemap.xml` - XML sitemap
- `GET /feed` - RSS feed

## Deployment

### Production Checklist

1. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Run `php artisan view:cache`
5. Build assets: `npm run build`
6. Set up proper database (MySQL/PostgreSQL recommended)
7. Configure mail service
8. Set up SSL certificate
9. Configure web server (Nginx/Apache)
10. Set up queue worker: `php artisan queue:work`

### Server Requirements

- PHP 8.2+
- Composer
- Node.js 18+ (for building assets)
- Web server (Nginx/Apache)
- Database (SQLite/MySQL/PostgreSQL)

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Run tests and linting
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For issues, questions, or contributions, please open an issue on the repository.

## Author

**DaraEmmanuel Akinyode**

- GitHub: [@Daraemmanuel](https://github.com/Daraemmanuel)
- LinkedIn: [daraemmanuel](https://www.linkedin.com/in/daraemmanuel/)
- Twitter: [@\_DaraEmmanuel](https://x.com/_DaraEmmanuel)

---

Built with ❤️ using Laravel, Vue.js, and TypeScript
