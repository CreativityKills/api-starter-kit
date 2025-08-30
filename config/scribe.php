<?php

declare(strict_types=1);

use Knuckles\Scribe\Config\AuthIn;
use App\Extensions\Scribe\Config\Defaults;
use App\Extensions\Scribe\Writing\Postman;
use App\Http\Middleware\AuthenticateIfNotLocal;
use App\Extensions\Scribe\Extracting\Strategies;

use function Knuckles\Scribe\Config\removeStrategies;
use function Knuckles\Scribe\Config\configureStrategy;

use Knuckles\Scribe\Extracting\Strategies as KnucklesStrategies;

/**
 * @see https://scribe.knuckles.wtf/laravel/reference/config for all available options
 */
return [

    /*
    |--------------------------------------------------------------------------
    | Documentation Metadata
    |--------------------------------------------------------------------------
    |
    | These settings control the basic information displayed in your API
    | documentation, including the title, description, and introduction text
    | that appears at the top of your generated documentation.
    |
    */

    'title' => sprintf('%s API Documentation', config('app.name')),

    'description' => sprintf('API documentation for %s. Please contact if you have any questions.', config('app.name')),

    'intro_text' => <<<'INTRO'
        This documentation aims to provide all the information you need to work with our API.
        <aside>
            As you scroll, you'll see code examples for working with the API in different programming languages.
            You can switch the language used with the tabs at the top right (or from the nav menu at the top left on mobile).
        </aside>
    INTRO,

    'base_url' => config('app.url'),

    /*
    |--------------------------------------------------------------------------
    | Route Selection
    |--------------------------------------------------------------------------
    |
    | Configure which routes should be included in your API documentation.
    | You can match routes by prefixes and domains, and explicitly include
    | or exclude specific routes as needed.
    |
    */

    'routes' => [
        [
            'match' => [
                'prefixes' => ['v1/*'],
                'domains' => ['*'],
            ],

            'include' => [
                // 'users.index', 'POST /new', '/auth/*'
            ],

            'exclude' => [
                // 'GET /health', 'admin.*'
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Documentation Output Type
    |--------------------------------------------------------------------------
    |
    | Determines how your documentation will be generated and served.
    | - "static": generates static HTML files in /public/docs
    | - "laravel": generates as Blade views with routing/authentication
    | - "external_static"/"external_laravel": uses external UI templates
    |
    */

    'type' => 'laravel',

    'theme' => 'custom',

    /*
    |--------------------------------------------------------------------------
    | Static Documentation Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for static HTML documentation generation.
    | These settings only apply when using the "static" output type.
    |
    */

    'static' => [
        'output_path' => 'public/docs',
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel Documentation Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for Laravel-integrated documentation. These settings
    | control routing, asset storage, and middleware for the documentation
    | when using "laravel" output type.
    |
    */

    'laravel' => [
        'add_routes' => true,

        // By default, `/docs` opens the HTML page, `/docs.postman` opens the Postman collection, and `/docs.openapi` the OpenAPI spec.
        'docs_url' => '/docs',

        // By default, assets are stored in `public/vendor/scribe`. If set, assets will be stored in `public/{{assets_directory}}`
        'assets_directory' => null,

        'middleware' => [
            'web',
            AuthenticateIfNotLocal::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | External Documentation Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for external documentation templates. Used when the
    | output type is set to "external_static" or "external_laravel".
    |
    */

    'external' => [
        'html_attributes' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Try It Out Feature
    |--------------------------------------------------------------------------
    |
    | Configure the interactive "Try It Out" button that allows users to
    | test API endpoints directly from the documentation. Ensure CORS
    | headers are properly configured for your endpoints when enabled.
    |
    */

    'try_it_out' => [
        'enabled' => true,
        'base_url' => null,
        'use_csrf' => true,
        'csrf_url' => '/sanctum/csrf-cookie',
    ],

    /*
    |--------------------------------------------------------------------------
    | API Authentication
    |--------------------------------------------------------------------------
    |
    | Configuration for API authentication documentation. This information
    | will be displayed in the docs, used in generated examples, and for
    | making response calls during documentation generation.
    |
    */

    'auth' => [
        'enabled' => true,
        'default' => true,
        'in' => AuthIn::BEARER->value,
        'name' => 'Authorization',
        'use_value' => env('SCRIBE_AUTH_KEY'),
        'placeholder' => '{YOUR_AUTH_KEY}',
        'extra_info' => 'You can retrieve your token by visiting your dashboard and clicking <b>Generate API token</b>.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Code Examples
    |--------------------------------------------------------------------------
    |
    | Configure which programming languages to show in the code examples
    | for each endpoint. Supported options: bash, javascript, php, python.
    | Note: This does not work for "external" documentation types.
    |
    */

    'example_languages' => [
        'bash',
        'javascript',
    ],

    /*
    |--------------------------------------------------------------------------
    | Export Formats
    |--------------------------------------------------------------------------
    |
    | Configure generation of additional export formats like Postman
    | collections and OpenAPI specifications alongside the main
    | documentation.
    |
    */

    'postman' => [
        'enabled' => true,
        'overrides' => [
            'info.name' => sprintf('%s API', config('app.name')),
            // 'info.version' => '2.0.0',
        ],
        'writer' => [
            'endpoint_callbacks' => [
                '{{baseUrl}}/v1/auth/token/create' => [
                    Postman\Processors\CreateTokenEndpointProcessor::class,
                ],
                '{{baseUrl}}/v1/auth/token/refresh' => [
                    Postman\Processors\RefreshTokenEndpointProcessor::class,
                ],
            ],
        ],
    ],

    'openapi' => [
        'enabled' => true,
        'overrides' => [
            'info.name' => sprintf('%s API', config('app.name')),
            // 'info.version' => '2.0.0',
        ],
        'generators' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Documentation Organization
    |--------------------------------------------------------------------------
    |
    | Settings for organizing and presenting your API documentation,
    | including endpoint grouping, custom logos, and last updated
    | timestamp formatting.
    |
    */

    'groups' => [
        'default' => 'Miscellaneous',
        'order' => [],
    ],

    'logo' => false,

    'last_updated' => 'Last updated: {date:F j, Y}',

    /*
    |--------------------------------------------------------------------------
    | Example Data Generation
    |--------------------------------------------------------------------------
    |
    | Configure how Scribe generates example data for your API responses.
    | This includes faker seed for consistent examples and model source
    | strategies for generating example models.
    |
    */

    'examples' => [
        'faker_seed' => 1234,
        'models_source' => ['factoryCreate', 'factoryMake', 'databaseFirst'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Extraction Strategies
    |--------------------------------------------------------------------------
    |
    | Define the strategies Scribe uses to extract information about your
    | routes. Each stage uses different strategies to gather metadata,
    | parameters, responses, and other documentation data.
    |
    */

    'strategies' => [
        'metadata' => [
            ...Defaults::METADATA_STRATEGIES,
        ],
        'headers' => [
            ...Defaults::HEADERS_STRATEGIES,
            KnucklesStrategies\StaticData::withSettings(data: [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]),
        ],
        'urlParameters' => [
            ...Defaults::URL_PARAMETERS_STRATEGIES,
        ],
        'queryParameters' => [
            ...Defaults::QUERY_PARAMETERS_STRATEGIES,
        ],
        'bodyParameters' => [
            ...Defaults::BODY_PARAMETERS_STRATEGIES,
        ],
        'responses' => configureStrategy(
            removeStrategies(Defaults::RESPONSES_STRATEGIES, [
                KnucklesStrategies\Responses\UseResponseAttributes::class,
                KnucklesStrategies\Responses\ResponseCalls::class,
            ]),
            KnucklesStrategies\Responses\ResponseCalls::withSettings(
                only: ['GET *'],
                config: ['app.debug' => false]
            )
        ),
        'responseFields' => [
            ...Defaults::RESPONSE_FIELDS_STRATEGIES,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Configuration
    |--------------------------------------------------------------------------
    |
    | Database settings for response calls and example generation. Scribe
    | uses transactions to prevent changes from being persisted during
    | documentation generation.
    |
    */

    'database_connections_to_transact' => [config('database.default')],

    /*
    |--------------------------------------------------------------------------
    | Fractal Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for League Fractal integration. Specify a custom
    | serializer if you're using one with league/fractal in your
    | application.
    |
    */

    'fractal' => [
        'serializer' => null,
    ],
];
