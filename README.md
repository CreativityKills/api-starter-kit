# API Starter Kit

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
``
