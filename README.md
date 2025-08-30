# API Starter Kit

### Project Structure

<details>
<summary>Click to expand project structure</summary>

The starter kit remains largely unchanged from the original Laravel application. However, here are some of the notable
changes that have been made to the project.

```
api-starter-kit/
├── app/
│   ├── Actions/
│   │   ├── Fortify/                         # User management actions (Laravel Fortify)
│   │   └── Sanctum/                         # API token management actions (Laravel Sanctum)
│   │       └── IssueAccessToken.php         # This action can be used to issue API tokens 
│   ├── Concerns/                            # Shared traits
│   │   └── PasswordValidationRules.php      # This trait is from the Laravel Fortify package
│   ├── Contracts/                           # Interface definitions
│   │   └── SupportsDocumentation.php        # This interface is used to document API endpoints, usable in Requests & Actions
│   ├── Data/
│   │   ├── Enums/                           
│   │   │   ├── Abilities.php                # This enum is used to define the abilities of a user token
│   │   │   └── AccessLevel.php              # This enum is used to define groups of abilities as access levels
│   │   ├── IssueAccessTokenDto.php          # This class is a simple DTO for holding the data needed to issue a new access token
│   │   └── IssuedAccessTokenDto.php         # This class is a simple DTO for holding the data of an issued access token
│   ├── Events/                              # Domain events
│   │   ├── IssuedAccessTokenEvent.php       # Event fired when an access token is successfully issued
│   │   └── IssuingAccessTokenEvent.php      # Event fired when an access token is about to be issued
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

</details>
