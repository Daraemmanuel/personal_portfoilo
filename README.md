# Personal Portfolio

A modern, full-featured portfolio website built with Laravel, Inertia.js, Vue.js, and TypeScript. This is a comprehensive portfolio solution featuring a blog system, project showcase, admin panel, and much more.

## 🚀 Features

### Core Features

- **Modern Tech Stack**: Laravel 12, Inertia.js, Vue 3, TypeScript, Tailwind CSS
- **Admin Panel**: Full CRUD interface for managing all content
- **Authentication**: Laravel Fortify with 2FA support
- **Server-Side Rendering**: SSR support for better SEO and performance
- **Responsive Design**: Mobile-first, fully responsive design with smooth animations
- **Dark Mode**: Light/dark/system theme toggle with persistence

### Portfolio Features

- **Projects Showcase**: Display your work with images, descriptions, tags, and links
- **Skills Section**: Organize and display your technical skills with icons
- **Experience Timeline**: Showcase your professional journey
- **Testimonials**: Display client/colleague testimonials with ratings
- **Blog/Articles**: Full-featured blog system with categories, tags, and series
- **Contact Form**: Functional contact form with spam protection and rate limiting
- **Newsletter**: Newsletter subscription system with export functionality
- **CV Management**: Upload and manage downloadable CV files

### Blog/Article Features

- **Full-Text Search**: Search across articles and projects with database indexes
- **Reading Time**: Automatic reading time calculation
- **Related Articles**: Smart related articles based on category and tags
- **Popular Articles**: View-based popular articles section
- **Categories & Tags**: Organize articles with categories and tags
- **Article Series**: Group related articles into series
- **Featured Articles**: Mark and highlight featured articles
- **Social Sharing**: Share buttons for Twitter, LinkedIn, Facebook, and copy link
- **Comments System**: Nested comments with reactions (like/helpful) and moderation
- **Scheduled Publishing**: Schedule articles for future publication
- **Draft Support**: Save articles as drafts before publishing

### Admin Panel Features

- **Activity Logging**: Track all changes with comprehensive activity logs
- **Toast Notifications**: Beautiful toast notification system
- **Confirmation Dialogs**: Reusable confirmation dialogs for destructive actions
- **Bulk Operations**: Bulk delete for articles
- **Export Functionality**: Export articles and newsletter subscribers as CSV
- **Rich Text Editor**: WYSIWYG editor for article content
- **Auto-Save**: Automatic saving for article editor
- **Image Management**: Upload and manage images for articles and projects
- **Comment Moderation**: Approve, reject, or delete comments
- **Analytics Dashboard**: View statistics and analytics
- **Contact Messages**: View and manage contact form submissions

### UX Enhancements

- **Smooth Animations**: Fade-in, scroll-triggered, and hover animations throughout
- **Loading Skeletons**: Skeleton loaders for better perceived performance
- **Toast Notifications**: User-friendly feedback system
- **Confirmation Dialogs**: Prevent accidental deletions
- **Auto-Save**: Prevent data loss during editing
- **Scroll Animations**: Elements animate as they come into view
- **Hover Effects**: Enhanced hover states on interactive elements

### SEO & Performance

- **SEO Optimized**: Open Graph tags, Twitter Cards, structured data (JSON-LD)
- **Meta Descriptions**: Dynamic meta descriptions for all pages
- **Canonical URLs**: Proper canonical URL implementation
- **Breadcrumbs**: Breadcrumb navigation with structured data
- **Sitemap**: Dynamic XML sitemap generation
- **RSS Feed**: RSS feed for blog articles
- **Image Optimization**: Lazy loading and responsive images
- **Caching**: Database query caching for improved performance
- **Full-Text Indexes**: Database indexes for fast search
- **Analytics**: Google Analytics integration

### Security

- **Security Headers**: XSS protection, frame options, content type options
- **Rate Limiting**: Built-in rate limiting on routes
- **CSRF Protection**: Laravel's built-in CSRF protection
- **Honeypot**: Spam protection on contact form
- **Input Validation**: Comprehensive validation on all forms
- **Two-Factor Authentication**: 2FA support via Laravel Fortify
- **Activity Monitoring**: Track all admin activities

### Developer Experience

- **TypeScript**: Full TypeScript support with proper type definitions
- **Error Pages**: Custom 404 and 500 error pages
- **Testing**: Pest PHP testing framework setup
- **Code Quality**: ESLint, Prettier, and PHP CS Fixer configured
- **API Endpoints**: RESTful API for portfolio data
- **Database Seeders**: Sample data seeders for development

## 📋 Requirements

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- SQLite (default) or MySQL/PostgreSQL

## 🛠️ Installation

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

7. **Seed database (optional)**

    ```bash
    php artisan db:seed
    ```

8. **Create storage link**

    ```bash
    php artisan storage:link
    ```

9. **Build assets**

    ```bash
    npm run build
    ```

10. **Create admin user**

    ```bash
    php artisan tinker
    # Then create a user:
    User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => Hash::make('password')])
    ```

## 🚦 Development

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

### Watch for changes

```bash
# Watch assets
npm run dev

# Watch PHP files (requires Laravel Herd or similar)
```

## ⚙️ Configuration

### Environment Variables

Key environment variables to configure:

```env
# Application
APP_NAME="Your Portfolio"
APP_URL=http://localhost:8000
APP_ENV=local
APP_DEBUG=true

# Database
DB_CONNECTION=sqlite
# Or for MySQL/PostgreSQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=portfolio
# DB_USERNAME=root
# DB_PASSWORD=

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Google Analytics (optional)
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
```

See `.env.example` for all available options.

### Mail Configuration

For detailed mail setup, see `EMAIL_SETUP.md`.

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

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/              # Admin controllers
│   │   │   ├── ArticleController.php
│   │   │   ├── ProjectController.php
│   │   │   ├── CommentController.php
│   │   │   ├── NewsletterController.php
│   │   │   └── AnalyticsController.php
│   │   ├── Api/                # API controllers
│   │   │   └── PortfolioController.php
│   │   ├── ArticleController.php
│   │   ├── CommentController.php
│   │   ├── ContactController.php
│   │   ├── NewsletterController.php
│   │   └── SearchController.php
│   ├── Middleware/             # Custom middleware
│   └── Requests/               # Form request classes
├── Models/                     # Eloquent models
│   ├── Article.php
│   ├── ArticleComment.php
│   ├── ActivityLog.php
│   ├── NewsletterSubscriber.php
│   └── ...
├── Traits/                     # Reusable traits
│   └── LogsActivity.php
└── Actions/                    # Fortify actions
    └── Fortify/

resources/
├── js/
│   ├── components/             # Vue components
│   │   ├── landing/           # Landing page components
│   │   │   ├── HeroSection.vue
│   │   │   ├── WorkSection.vue
│   │   │   ├── SkillsSection.vue
│   │   │   └── ...
│   │   ├── articles/          # Article components
│   │   │   └── CommentsSection.vue
│   │   ├── admin/             # Admin components
│   │   │   └── RichTextEditor.vue
│   │   └── ui/                # UI components
│   │       ├── Toast.vue
│   │       ├── ConfirmDialog.vue
│   │       └── ...
│   ├── pages/                  # Inertia pages
│   │   ├── Admin/              # Admin pages
│   │   │   ├── Articles/
│   │   │   ├── Projects/
│   │   │   └── Dashboard.vue
│   │   ├── Articles/           # Article pages
│   │   │   ├── Index.vue
│   │   │   ├── Show.vue
│   │   │   └── Category.vue
│   │   ├── Projects/           # Project pages
│   │   └── Errors/             # Error pages
│   ├── composables/            # Vue composables
│   │   ├── useToast.ts
│   │   ├── useAutoSave.ts
│   │   └── useAppearance.ts
│   ├── types/                  # TypeScript types
│   └── app.ts                  # Main app file
├── css/
│   └── app.css                 # Global styles and animations
└── views/
    └── app.blade.php           # Main blade template

database/
├── migrations/                  # Database migrations
└── seeders/                     # Database seeders
    └── DatabaseSeeder.php

routes/
├── web.php                      # Main route file
├── public.php                   # Public routes
├── admin.php                    # Admin routes
├── api.php                      # API routes
├── settings.php                 # Settings routes
└── feeds.php                    # Feed routes (sitemap, RSS)
```

## 🎨 Admin Panel

Access the admin panel at `/dashboard` after logging in.

### Features:

- **Dashboard**: Overview with statistics and recent activity
- **Projects Management**: Create, edit, delete projects with images and archive support
- **Skills Management**: Organize technical skills with icons
- **Experiences Management**: Manage work experience timeline
- **Testimonials Management**: Add and manage testimonials with ratings
- **Articles Management**: Full blog management system with:
    - Rich text editor
    - Auto-save functionality
    - Featured articles
    - Series support
    - Bulk operations
    - CSV export
- **Comments Management**: Moderate comments (approve/reject/delete)
- **Contact Messages**: View and manage contact form submissions
- **Newsletter**: Manage subscribers and export lists
- **CV Management**: Upload and manage downloadable CV files
- **Activity Logs**: View all admin activities
- **Analytics**: View portfolio statistics and performance metrics

## 🌐 Routes

### Public Routes

- `GET /` - Homepage
- `GET /archive` - Project archive
- `GET /projects/{id}` - Project detail page
- `GET /articles` - Articles listing
- `GET /articles/categories` - Categories listing
- `GET /articles/tags` - Tags listing
- `GET /articles/category/{category}` - Articles by category
- `GET /articles/tag/{tag}` - Articles by tag
- `GET /articles/{slug}` - Article detail
- `GET /search?q={query}&type={all|articles|projects}` - Search results
- `POST /contact` - Submit contact form
- `POST /newsletter/subscribe` - Subscribe to newsletter
- `POST /newsletter/unsubscribe/{email}` - Unsubscribe from newsletter
- `POST /articles/{article}/comments` - Submit comment
- `POST /comments/{comment}/reactions` - Add reaction to comment
- `GET /cv/download` - Download CV
- `GET /sitemap.xml` - XML sitemap
- `GET /feed` - RSS feed

### Admin Routes (Requires Authentication)

- `GET /dashboard` - Admin dashboard
- `GET /admin/projects` - Projects list
- `GET /admin/projects/create` - Create project
- `GET /admin/projects/{id}/edit` - Edit project
- `DELETE /admin/projects/{id}` - Delete project
- `GET /admin/articles` - Articles list
- `GET /admin/articles/create` - Create article
- `GET /admin/articles/{id}/edit` - Edit article
- `POST /admin/articles/bulk-delete` - Bulk delete articles
- `GET /admin/articles/export` - Export articles CSV
- `GET /admin/comments` - Comments list
- `POST /admin/comments/{id}/approve` - Approve comment
- `POST /admin/comments/{id}/reject` - Reject comment
- `GET /admin/contact-messages` - Contact messages list
- `GET /admin/newsletter` - Newsletter subscribers
- `GET /admin/newsletter/export` - Export subscribers CSV
- `GET /admin/cv` - CV management
- `GET /admin/activity-logs` - Activity logs

### Settings Routes (Requires Authentication)

- `GET /settings/profile` - Profile settings
- `PATCH /settings/profile` - Update profile
- `DELETE /settings/profile` - Delete account
- `GET /settings/password` - Password settings
- `PUT /settings/password` - Update password
- `GET /settings/appearance` - Appearance settings
- `GET /settings/two-factor` - Two-factor authentication

## 🔌 API Endpoints

All API endpoints are prefixed with `/api` and return JSON responses.

### Public API

- `GET /api/projects` - Get all projects
- `GET /api/articles` - Get all published articles
- `GET /api/articles/{slug}` - Get article by slug
- `GET /api/skills` - Get all skills
- `GET /api/experiences` - Get all experiences

### Example API Response

```json
{
    "data": [
        {
            "id": 1,
            "title": "Project Title",
            "description": "Project description",
            "image_url": "https://example.com/image.jpg",
            "link": "https://example.com",
            "tags": ["Laravel", "Vue.js"],
            "created_at": "2024-01-01T00:00:00.000000Z"
        }
    ]
}
```

## 🎭 Animations

The project includes comprehensive animations throughout:

- **Fade-in animations**: Elements fade in on page load
- **Scroll-triggered animations**: Elements animate as they come into view
- **Hover effects**: Enhanced hover states on interactive elements
- **Staggered animations**: Sequential animations for lists and grids
- **Smooth transitions**: All state changes are smoothly animated

### Animation Classes

- `fade-in-up` - Fade in from bottom
- `fade-in-left` - Fade in from left
- `fade-in-right` - Fade in from right
- `scale-in` - Scale in animation
- `reveal` - Scroll-triggered reveal animation
- `animate-in` - Base animation class
- Delay classes: `delay-100`, `delay-150`, `delay-200`, etc.

## 🧪 Testing

### Run tests

```bash
php artisan test
```

### Test coverage

```bash
php artisan test --coverage
```

## 📦 Deployment

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
11. Set up cron job for scheduled tasks:
    ```bash
    * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
    ```

### Server Requirements

- PHP 8.2+
- Composer
- Node.js 18+ (for building assets)
- Web server (Nginx/Apache)
- Database (SQLite/MySQL/PostgreSQL)
- Redis (optional, for caching)

### Nginx Configuration Example

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 🔒 Security

### Security Features

- CSRF protection on all forms
- XSS protection via Laravel's built-in escaping
- SQL injection protection via Eloquent ORM
- Rate limiting on sensitive routes
- Honeypot spam protection
- Input validation on all forms
- Secure password hashing
- Two-factor authentication support
- Activity logging for security auditing

### Security Headers

The application includes security headers:

- X-Frame-Options
- X-Content-Type-Options
- X-XSS-Protection
- Referrer-Policy

## 📝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run tests and linting
5. Commit your changes (`git commit -m 'Add some amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

### Code Style

- PHP: Follow PSR-12 coding standards
- JavaScript/TypeScript: Follow ESLint and Prettier rules
- Run `composer pint` for PHP formatting
- Run `npm run format` for JavaScript formatting

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🆘 Support

For issues, questions, or contributions, please open an issue on the repository.

## 👤 Author

**DaraEmmanuel Akinyode**

- GitHub: [@Daraemmanuel](https://github.com/Daraemmanuel)
- LinkedIn: [daraemmanuel](https://www.linkedin.com/in/daraemmanuel/)
- Twitter: [@\_DaraEmmanuel](https://x.com/_DaraEmmanuel)
- Email: immanuelatwork@gmail.com

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com)
- Frontend powered by [Vue.js](https://vuejs.org) and [Inertia.js](https://inertiajs.com)
- Styled with [Tailwind CSS](https://tailwindcss.com)
- Icons by [Lucide](https://lucide.dev)

## Assigning roles to users

- `php artisan tinker`
- `$user = User::where('email', 'newuser@example.com')->first();`
- `$user->assignRole('editor');`
- `// or 'article manager', 'admin'`
