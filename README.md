# Fast Auto Clinic

One stop solution for all your auto clinic needs.

## Requirements

- PHP 8.2 or higher
- Composer
- Node.js & NPM (optional)

## Quick Start

1. Clone the repository:
```bash
git clone https://github.com/steadfast-it/auto-clinic.git
cd [project-name]
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Environment setup:
```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database in `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. Run migrations:
```bash
php artisan migrate
```

6. Build assets:
```bash
npm run dev
```

7. Start the development server:
```bash
php artisan serve
```