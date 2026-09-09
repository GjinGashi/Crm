# CRM System

A modern full-stack Customer Relationship Management (CRM) system for managing clients, projects, tasks, users, and their relationships in one place.

The application provides a professional user interface with all the required functions, including authentication, client and project management, task management, search and filtering, validation, user permissions, and relationship management.

## Technologies Used

- Laravel / PHP
- Vue.js
- TypeScript
- Inertia.js
- shadcn/ui
- Tailwind CSS
- Laravel Sanctum
- MySQL / SQLite
- Pest
- PHPStan
- Laravel Pint
- Vite

## Requirements

When installing the project, make sure you have:

- PHP 8.2 or newer
- Composer
- Node.js and npm
- MySQL or another supported database
- Git

## Installation

Clone the repository and enter the project directory:

```bash
git clone https://github.com/GjinGashi/Crm.git
cd Crm
```
## Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
php artisan key:generate
```

Configure the database settings in the `.env` file.

## Database Setup

Run the database migrations:

```bash
php artisan migrate
```
## Running the Backend

Start the Laravel development server:

```bash
php artisan serve
```
## Running the Frontend

Start the Vite development server:

```bash
npm run dev
```
## Testing

Run the automated tests:

```bash

php artisan test
```
## Complete Quality  Check
```bash
composer ci:check

