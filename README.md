# 🚀 JobPilot

> A modern, AI-powered job application platform built with Laravel that intelligently matches candidates with job opportunities using OpenAI's GPT models.

[![PHP Version](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.0-red.svg)](https://laravel.com/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

## 📋 Table of Contents

-   [Overview](#overview)
-   [Key Features](#key-features)
-   [Technology Stack](#technology-stack)
-   [Architecture Highlights](#architecture-highlights)
-   [Installation](#installation)
-   [Configuration](#configuration)
-   [API Documentation](#api-documentation)
-   [Testing](#testing)
-   [Notable Achievements](#notable-achievements)
-   [Project Structure](#project-structure)

## 🎯 Overview

JobPilot is a comprehensive job application management system that leverages artificial intelligence to revolutionize the hiring process. The platform enables job seekers to upload their resumes, apply to positions, and receive AI-powered compatibility scores and feedback. Employers can post job vacancies, manage applications, and gain insights through detailed analytics.

### What Makes JobPilot Special?

-   **🤖 AI-Powered Resume Evaluation**: Automatically analyzes resume-to-job-description compatibility using OpenAI's GPT-4o-mini, providing instant feedback and improvement suggestions
-   **📄 Intelligent PDF Processing**: Extracts and processes text from PDF resumes using multiple parsing strategies
-   **🔐 Robust Authentication & Authorization**: Role-based access control (Admin, Owner, User) with secure token-based authentication
-   **📊 Comprehensive Analytics**: Tracks user engagement, job performance, and application metrics
-   **⚡ Modern Architecture**: Built with clean code principles, SOLID design patterns, and scalable architecture

## ✨ Key Features

### For Job Seekers

-   **Resume Management**: Upload and manage PDF resumes with automatic text extraction
-   **Smart Job Matching**: Browse and filter job listings by category, location, employment type, and more
-   **AI Compatibility Scoring**: Get instant feedback on how well your resume matches job requirements
-   **Application Tracking**: Monitor application status and receive detailed compatibility reports
-   **Profile Management**: Create and maintain professional profiles

### For Employers

-   **Company Management**: Create and manage company profiles
-   **Job Posting**: Post detailed job vacancies with rich descriptions, requirements, and benefits
-   **Application Management**: Review applications with AI-generated compatibility scores
-   **Analytics Dashboard**: Track job performance, application metrics, and user engagement
-   **Advanced Filtering**: Powerful query system for finding the right candidates

### For Administrators

-   **User Management**: Comprehensive user administration
-   **Category Management**: Organize jobs by categories
-   **System Analytics**: Monitor platform-wide metrics and performance

## 🛠 Technology Stack

### Backend

-   **Framework**: Laravel 12.0
-   **PHP**: 8.2+
-   **Database**: MySQL/PostgreSQL (configurable)
-   **Authentication**: Laravel Sanctum (API token-based)
-   **Queue System**: Laravel Queues for background job processing

### AI & Processing

-   **OpenAI Integration**: `openai-php/laravel` for GPT-4o-mini powered resume evaluation
-   **PDF Processing**: `smalot/pdfparser` for intelligent resume text extraction

### Development Tools

-   **Testing**: Pest PHP (modern PHP testing framework)
-   **Code Quality**: Laravel Pint (PSR-12 code style)
-   **Type Safety**: Strict types throughout the codebase
-   **Frontend Build**: Vite with Tailwind CSS

### Architecture Patterns

-   **Action Classes**: Single-responsibility action handlers
-   **DTOs**: Data Transfer Objects for type-safe data handling
-   **Service Layer**: Business logic abstraction
-   **Repository Pattern**: Data access abstraction
-   **Query Objects**: Reusable query builders
-   **Event-Driven**: Laravel events for decoupled functionality

## 🏗 Architecture Highlights

### Clean Code Principles

-   **Strict Types**: Full type declarations (`declare(strict_types=1)`)
-   **Final Classes**: Immutable classes where appropriate
-   **Readonly Properties**: Immutability where possible
-   **Interface-Based Design**: Dependency injection with interfaces
-   **Single Responsibility**: Each class has one clear purpose

### Design Patterns Implemented

-   **Action Pattern**: Encapsulated business operations (`ApplyToJobAction`, `CreateResumeAction`)
-   **DTO Pattern**: Type-safe data structures (`CreateResumeDTO`, `CreateJobVacancyDTO`)
-   **Service Pattern**: Business logic services (`EvaluateResumeWithAIService`, `ResumeTextExtractor`)
-   **Query Object Pattern**: Reusable query builders (`FilteredJobVacancyQuery`)
-   **Factory Pattern**: Model factories for testing
-   **Observer Pattern**: Event listeners for side effects

### Key Architectural Decisions

-   **UUID Primary Keys**: Better for distributed systems and security
-   **Soft Deletes**: Data preservation and audit trails
-   **Scoped Queries**: Reusable query filters using Laravel's query scopes
-   **Background Jobs**: Asynchronous resume text extraction for better performance
-   **API-First Design**: RESTful API with versioning (`/api/v1`)

## 📦 Installation

### Prerequisites

-   PHP 8.2 or higher
-   Composer
-   Node.js and npm
-   MySQL/PostgreSQL database
-   OpenAI API key (for AI features)

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/JobPilot.git
cd JobPilot
```

### Step 2: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Step 4: Configure Environment Variables

Edit `.env` file and configure:

```env
APP_NAME=JobPilot
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jobpilot
DB_USERNAME=your_username
DB_PASSWORD=your_password

OPENAI_API_KEY=your_openai_api_key
OPENAI_ORGANIZATION=your_org_id  # Optional

QUEUE_CONNECTION=database  # or redis for production
```

### Step 5: Run Database Migrations

```bash
php artisan migrate
```

### Step 6: Seed Database (Optional)

```bash
php artisan db:seed
```

### Step 7: Build Frontend Assets

```bash
npm run build
# or for development
npm run dev
```

### Step 8: Start Development Server

```bash
# Start Laravel server, queue worker, and Vite in parallel
composer dev

# Or individually:
php artisan serve
php artisan queue:work
npm run dev
```

## ⚙️ Configuration

### OpenAI Configuration

The AI evaluation service uses OpenAI's GPT-4o-mini model. Configure your API key in `.env`:

```env
OPENAI_API_KEY=sk-your-api-key-here
```

### Queue Configuration

Resume text extraction runs asynchronously. Ensure your queue worker is running:

```bash
php artisan queue:work
```

### Storage Configuration

Resumes are stored in `storage/app/public/resumes`. Create a symbolic link:

```bash
php artisan storage:link
```

## 📚 API Documentation

### Authentication Endpoints

-   `POST /api/v1/login` - User login
-   `POST /api/v1/register` - User registration
-   `POST /api/v1/forgot-password` - Request password reset
-   `POST /api/v1/verify-code` - Verify reset code
-   `POST /api/v1/reset-password` - Reset password
-   `DELETE /api/v1/logout` - Logout (requires authentication)
-   `GET /api/v1/me` - Get current user (requires authentication)

### Job Endpoints

-   `GET /api/v1/jobs` - List jobs (with filtering)
-   `GET /api/v1/jobs/{id}` - Get job details
-   `POST /api/v1/jobs/{id}` - Apply to job (requires authentication)

### Resume Endpoints

-   `POST /api/v1/resumes` - Upload resume (requires authentication)

### Admin Endpoints

-   Company management
-   Job vacancy management
-   Category management
-   User management

### Owner Endpoints

-   Company management (own companies)
-   Job vacancy management (own companies)
-   Application management

### Filtering & Query Parameters

Jobs can be filtered by:

-   `job_category_id` - Filter by category
-   `employment_type` - Filter by employment type
-   `location` - Filter by location (partial match)
-   `status` - Filter by active status

## 🧪 Testing

The project uses [Pest PHP](https://pestphp.com/) for testing, providing a modern and expressive testing experience.

### Run Tests

```bash
# Run all tests
composer test

# Or using Pest directly
php artisan test

# Run with coverage
php artisan test --coverage
```

### Test Structure

-   **Feature Tests**: HTTP endpoint testing, authentication flows
-   **Unit Tests**: Model tests, service tests, enum tests, middleware tests
-   **Factories**: Comprehensive model factories for test data generation

### Test Coverage

The project includes tests for:

-   Authentication and authorization
-   Job application workflows
-   Resume processing
-   Model relationships and scopes
-   Middleware functionality
-   Service layer logic

## 🎖 Notable Achievements

### Technical Excellence

1. **AI Integration**: Successfully integrated OpenAI's GPT-4o-mini for intelligent resume evaluation with structured JSON responses
2. **PDF Processing**: Implemented robust PDF text extraction with fallback strategies (PDF Parser library + pdftotext command)
3. **Type Safety**: Maintained strict type declarations throughout the entire codebase
4. **Clean Architecture**: Applied SOLID principles, design patterns, and clean code practices consistently
5. **Scalable Design**: Built with scalability in mind using queues, efficient queries, and proper indexing

### Code Quality

-   **100% Strict Types**: Every PHP file uses `declare(strict_types=1)`
-   **PSR-12 Compliant**: Code formatted with Laravel Pint
-   **Comprehensive Testing**: Feature and unit tests covering critical functionality
-   **Documentation**: Well-documented code with clear naming conventions

### Performance Optimizations

-   **Eager Loading**: Strategic use of `with()` to prevent N+1 queries
-   **Query Scopes**: Reusable, efficient query filters
-   **Background Jobs**: Asynchronous processing for time-consuming operations
-   **Database Indexing**: Proper indexes on frequently queried columns

### Security Features

-   **Token-Based Authentication**: Laravel Sanctum for secure API access
-   **Role-Based Access Control**: Middleware-based authorization
-   **Input Validation**: Comprehensive form request validation
-   **Soft Deletes**: Data preservation and audit capabilities

## 📁 Project Structure

```
JobPilot/
├── app/
│   ├── Actions/              # Business action handlers
│   ├── Console/              # Artisan commands
│   ├── DTOs/                 # Data Transfer Objects
│   ├── Enums/                # Type-safe enumerations
│   ├── Events/               # Event classes
│   ├── Exceptions/           # Custom exceptions
│   ├── Http/
│   │   ├── Controllers/      # API controllers
│   │   ├── Middleware/       # Custom middleware
│   │   ├── Requests/         # Form request validation
│   │   └── Resources/        # API resources
│   ├── Interfaces/           # Service interfaces
│   ├── Jobs/                 # Queue jobs
│   ├── Listeners/            # Event listeners
│   ├── Mail/                 # Email classes
│   ├── Models/               # Eloquent models
│   ├── Providers/            # Service providers
│   ├── Queries/              # Query objects
│   ├── Services/             # Business logic services
│   └── Traits/               # Reusable traits
├── database/
│   ├── factories/            # Model factories
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── routes/                    # Route definitions
├── tests/                     # Test suite
└── config/                    # Configuration files
```

## 🤝 Contributing

This is a portfolio project, but suggestions and feedback are welcome! If you'd like to contribute:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Ensure tests pass
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👤 Author

Built with ❤️ as a showcase of modern Laravel development practices and AI integration.

---

**Note**: This project requires an OpenAI API key for full functionality. The AI evaluation features will not work without proper configuration.
