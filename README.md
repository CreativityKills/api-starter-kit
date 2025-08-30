# API Starter Kit

## Project Structure

```
api-starter-kit/
├── app/
│   ├── Actions/
│   │   ├── Fortify/          # User management actions
│   │   └── Sanctum/          # API token management
│   ├── Concerns/             # Shared traits and concerns
│   ├── Contracts/            # Interface definitions
│   ├── Data/
│   │   ├── Enums/           # Application enumerations
│   │   ├── IssueAccessTokenDto.php
│   │   └── IssuedAccessTokenDto.php
│   ├── Events/              # Domain events
│   ├── Extensions/
│   │   └── Scribe/          # API documentation extensions
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Authentication/  # Auth controllers
│   │   ├── Documentation/       # API docs structure
│   │   ├── Middleware/         # HTTP middleware
│   │   ├── Requests/           # Form request validation
│   │   └── Responses/          # Structured responses
│   ├── Models/              # Eloquent models
│   └── Providers/           # Service providers
├── bootstrap/               # Application bootstrapping
├── config/                  # Configuration files
├── database/
│   ├── factories/          # Model factories
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
├── public/
│   ├── docs/              # Generated API documentation
│   └── index.php          # Application entry point
├── resources/
│   └── views/             # Blade templates
├── routes/                # Route definitions
├── storage/               # Application storage
├── tests/
│   ├── Feature/           # Integration tests
│   └── Unit/              # Unit tests
└── vendor/                # Composer dependencies
```
