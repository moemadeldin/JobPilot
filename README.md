# ApplyAI

> A modern, AI-powered job application platform built with Laravel that intelligently matches candidates with job opportunities using Groq's Llama models.

[![PHP Version](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://www.php.net/)
[![Laravel Version](https://img.shields.io/badge/Laravel-12.0-red.svg)](https://laravel.com/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)

## Table of Contents

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

## Overview

ApplyAI is a comprehensive job application management system that leverages artificial intelligence to revolutionize the hiring process. The platform enables job seekers to upload their resumes, apply to positions, and receive AI-powered compatibility scores and feedback. Employers can post job vacancies, manage applications, and gain insights through detailed analytics.

### What Makes ApplyAI Special?

-   **AI-Powered Resume Evaluation**: Automatically analyzes resume-to-job-description compatibility using Groq's Llama models, providing instant feedback and improvement suggestions
-   **AI Cover Letter Generator**: Generates tailored cover letters based on resume content and job descriptions
-   **AI Mock Interview Generator**: Creates mock interview Q&A pairs based on resume and job requirements
-   **Intelligent PDF Processing**: Extracts and processes text from PDF resumes using multiple parsing strategies
-   **Robust Authentication & Authorization**: Role-based access control (Admin, Owner, User) with secure token-based authentication
-   **Comprehensive Analytics**: Tracks user engagement, job performance, and application metrics
-   **Modern Architecture**: Built with clean code principles, SOLID design patterns, and scalable architecture

## Key Features

### For Job Seekers

-   **Resume Management**: Upload and manage PDF resumes with automatic text extraction
-   **Smart Job Matching**: Browse and filter job listings by category, location, employment type, and more
-   **AI Compatibility Scoring**: Get instant feedback on how well your resume matches job requirements
-   **AI Cover Letter Generator**: Generate tailored cover letters for each job application
-   **AI Mock Interview**: Practice with AI-generated interview questions based on your profile
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

## Technology Stack

### Backend

-   **Framework**: Laravel 12.0
-   **PHP**: 8.2+
-   **Database**: MySQL/PostgreSQL (configurable)
-   **Authentication**: Laravel Sanctum (API token-based)
-   **Queue System**: Laravel Queues for background job processing

### AI & Processing

-   **Groq API**: Llama 3.3-70b-versatile for AI-powered resume evaluation, cover letters, and mock interviews
-   **PDF Processing**: `smalot/pdfparser` for intelligent resume text extraction
-   **Text Truncation**: Automatic input truncation (~16,000 characters) for cost optimization

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

## Architecture Highlights

### Clean Code Principles

-   **Strict Types**: Full type declarations (`declare(strict_types=1)`)
-   **Final Classes**: Immutable classes where appropriate
-   **Readonly Properties**: Immutability where possible
-   **Interface-Based Design**: Dependency injection with interfaces
-   **Single Responsibility**: Each class has one clear purpose

### Design Patterns Implemented

-   **Action Pattern**: Encapsulated business operations (`ApplyToJobAction`, `CreateResumeAction`)
-   **DTO Pattern**: Type-safe data structures (`CreateResumeDTO`, `CreateJobVacancyDTO`)
-   **Service Pattern**: Business logic services (`EvaluateResumeWithAIService`, `GenerateCoverLetterService`)
-   **Query Object Pattern**: Reusable query builders (`FilteredJobVacancyQuery`)
-   **Factory Pattern**: Model factories for testing
-   **Observer Pattern**: Event listeners for side effects
-   **Trait Pattern**: Shared AI functionality via `HasAiPrompt` trait

### AI Service Architecture

-   **Centralized Configuration**: All AI settings in `config/ai_services.php`
-   **Shared Prompt Logic**: Reusable `HasAiPrompt` trait for truncation and prompt generation
-   **Modular Services**: Separate services for evaluation, cover letters, and mock interviews
-   **Configurable Prompts**: All prompts in `config/prompts.php` for easy updates
-   **Input Truncation**: Automatic text truncation to prevent token overuse

### Key Architectural Decisions

-   **UUID Primary Keys**: Better for distributed systems and security
-   **Soft Deletes**: Data preservation and audit trails
-   **Scoped Queries**: Reusable query filters using Laravel's query scopes
-   **Background Jobs**: Asynchronous resume text extraction for better performance
-   **API-First Design**: RESTful API with versioning (`/api/v1`)
-   **Invokable Controllers**: Single-purpose controllers for cleaner routing

## Installation

### Prerequisites

-   PHP 8.2 or higher
-   Composer
-   Node.js and npm
-   MySQL/PostgreSQL database
-   Groq API key (for AI features)

### Step 1: Clone the Repository

```bash
git clone https://github.com/yourusername/ApplyAI.git
cd ApplyAI
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
APP_NAME=ApplyAI
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=applyai
DB_USERNAME=your_username
DB_PASSWORD=your_password

GROQ_API_KEY=your_groq_api_key
GROQ_API_CHAT=https://api.groq.com/openai/v1/chat/completions

QUEUE_CONNECTION=database
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

## Configuration

### Groq AI Configuration

The AI services use Groq's Llama 3.3-70b-versatile model. Configure your API key in `.env`:

```env
GROQ_API_KEY=gsk-your-api-key-here
```

### AI Service Settings

Configure AI behavior in `config/ai_services.php`:

```php
'model' => env('AI_MODEL', 'llama-3.3-70b-versatile'),
'temperature' => env('AI_TEMPERATURE', 0.3),
'cover_letter_temperature' => env('AI_COVER_LETTER_TEMPERATURE', 0.7),
```

### Queue Configuration

Resume text extraction and AI evaluation run via queues. Ensure your queue worker is running:

```bash
php artisan queue:work
```

### Storage Configuration

Resumes are stored in `storage/app/public/resumes`. Create a symbolic link:

```bash
php artisan storage:link
```

## API Documentation

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
-   `POST /api/v1/jobs/{id}/apply` - Apply to job (requires authentication)
-   `POST /api/v1/jobs/{id}/cover-letter/{resume}` - Generate AI cover letter (requires authentication)

### Mock Interview Endpoints

-   `GET /api/v1/applications/{id}/mock` - Get mock interview status
-   `POST /api/v1/applications/{id}/mock/accept` - Accept mock interview
-   `DELETE /api/v1/applications/{id}/mock/decline` - Decline mock interview

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

## Testing

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

## Notable Achievements

### Technical Excellence

1. **AI Integration**: Successfully integrated Groq's Llama models for intelligent resume evaluation, cover letter generation, and mock interview creation
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
-   **Input Truncation**: Automatic text truncation to control AI costs

### Security Features

-   **Token-Based Authentication**: Laravel Sanctum for secure API access
-   **Role-Based Access Control**: Middleware-based authorization
-   **Input Validation**: Comprehensive form request validation
-   **Soft Deletes**: Data preservation and audit capabilities

## Project Structure

```
ApplyAI/
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
├── config/
│   ├── ai_services.php       # AI model and temperature settings
│   ├── prompts.php           # AI prompts configuration
│   └── services.php          # Third-party service credentials
├── database/
│   ├── factories/            # Model factories
│   ├── migrations/           # Database migrations
│   └── seeders/              # Database seeders
├── routes/                    # Route definitions
├── tests/                     # Test suite
└── .env.example              # Environment variables template
```

## Contributing

This is a portfolio project, but suggestions and feedback are welcome! If you'd like to contribute:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Ensure tests pass
5. Submit a pull request

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Author

Built as a showcase of modern Laravel development practices and AI integration.

---

**Note**: This project requires a Groq API key for full functionality. The AI features will not work without proper configuration.
