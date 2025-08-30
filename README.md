# API Starter Kit

Minimal Laravel starter kit aimed at accelerating API development with authentication, and comprehensive documentation tooling
built in.

## Whats Inside?

✨ **Authentication Ready**

- Laravel Fortify as the backend for user management
    - Create access token
    - Refresh access token
    - Revoke access token
- Laravel Sanctum integration for API token management with refresh token support
- Token-based authentication with configurable abilities and access levels

📚 **Auto-Generated Documentation**
<img width="3208" height="2072" alt="API Documentation" src="https://github.com/user-attachments/assets/c28ced8f-4475-47eb-8a88-d1a93eb6deef" />

<img width="3208" height="2072" alt="Postman Screenshot" src="https://github.com/user-attachments/assets/098d9599-3211-43b1-b587-28919d0d1e73" />

- Scribe integration with custom extensions for enhanced API docs
    - Improved Postman collection generation with pre-request script for automatic token generation for all endpoints
    - Supports environment variables in API Documentation
- Structured response documentation with reusable classes (using Attributes)
- Structured API Documentation with groups & subgroups (using Attributes)

🏗️ **Optional Action-based Architecture**

- Action-based architecture for business logic
- DTOs for type-safe data transfer

🧪 **Testing Foundation**

- Pre-configured Pest testing setup, with existing tests for authentication
- PHPStan for static analysis
- PHP CS Fixer for code style

## Quick Start

1. **Install using Laravel new command:**
   See https://laravel.com/docs/12.x/starter-kits#community-maintained-starter-kits
   ```bash
   laravel new example-api --using=creativitykills/api-starter-kit
   ```

Your API documentation will be available at `/docs` and your API endpoints will be ready for authentication and token management.

## API Endpoints

- **POST** `/api/auth/login` - User authentication and token issuance
- **POST** `/api/auth/refresh` - Refresh access tokens
- **POST** `/api/auth/logout` - Revoke access tokens

### Project Structure

The starter kit remains largely unchanged from the original Laravel application. However, here are **some of the notable
changes** that have been made to the project.

<details>
<summary>Click to expand project structure</summary>

```
api-starter-kit/
├── app/
│   ├── Actions/
│   │   ├── Fortify/                         # User management actions (Laravel Fortify)
│   │   └── Sanctum/                         # API token management actions (Laravel Sanctum)
│   │       └── IssueAccessToken.php         # This action can be used to issue API tokens 
│   ├── Contracts/                           # Interface definitions
│   │   └── SupportsDocumentation.php        # This interface is used to document API endpoints, usable in Requests & Actions
│   ├── Data/
│   │   ├── Enums/                           
│   │   │   ├── Abilities.php                # This enum is used to define the abilities of a user token
│   │   │   └── AccessLevel.php              # This enum is used to define groups of abilities as access levels
│   │   ├── IssueAccessTokenDto.php          # This class is a simple DTO for holding the data needed to issue a new access token
│   │   └── IssuedAccessTokenDto.php         # This class is a simple DTO for holding the data of an issued access token
│   ├── Extensions/
│   │   └── Scribe/                          # Here extend some parts of the Scribe package to allow for even better documentation
│   │       ├── Concerns/
│   │       │   └── ExtendableAttributeNames.php
│   │       ├── Config/
│   │       │   └── Defaults.php
│   │       ├── Extracting/
│   │       │   └── Strategies/               # Extend the strategies to allow for custom responses and documentation groups
│   │       │       ├── Metadata/
│   │       │       │   ├── GetFromDocBlocks.php
│   │       │       │   ├── GetFromMetadataAttributes.php
│   │       │       │   └── GetValidationRulesFromAction.php
│   │       │       └── Response/
│   │       │           └── UseResponseAttributes.php
│   │       └── Writing/                      # Added support for generating even better Postman collections with pre-request scripts
│   │           ├── Postman/
│   │           │   ├── PostmanCollectionWriter.php
│   │           │   ├── PostmanEndpointProcessor.php
│   │           │   └── Processors/           # Processors allow you even greater control over the Postman collection
│   │           │       ├── CreateTokenEndpointProcessor.php
│   │           │       ├── EndpointProcessor.php
│   │           │       └── RefreshTokenEndpointProcessor.php
│   │           └── Writer.php
│   ├── Http/
│   │   ├── [...]
│   │   ├── Documentation/                     # API documentation structure classes
│   │   │   ├── Groups/                        # API Documentation groups
│   │   │   │   ├── AuthenticationGroup.php    
│   │   │   │   ├── GenericGroup.php           
│   │   │   │   └── GenericSubgroup.php        
│   │   │   └── Responses/                     # API Response documentation classes
│   │   │       ├── Authentication/
│   │   │       │   └── AccessTokenResponse.php # Response documentation for access tokens
│   │   │       ├── BadRequestResponse.php
│   │   │       ├── CreatedResponse.php
│   │   │       ├── [...]
│   │   │       ├── UnauthorizedResponse.php
│   │   │       └── UnprocessableEntityResponse.php
│   │   └── [...]         
│   └── [...]           
└── [...]   
```

</details>

