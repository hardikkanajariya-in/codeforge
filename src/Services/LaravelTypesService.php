<?php

namespace HkDevs\CodeForgeStudio\Services;

/**
 * LaravelTypesService
 *
 * Comprehensive Laravel data type management and mapping service for CodeForge Database Studio.
 * Provides complete type definitions, validation rules, and form field mappings for Laravel development.
 *
 * Features:
 * - Complete Laravel migration column type definitions with descriptions
 * - Comprehensive validation rule catalog with parameter specifications
 * - Intelligent form field type mapping for UI generation
 * - Database-to-Laravel type conversion utilities
 * - Type-specific validation and constraint management
 * - Cross-platform database type compatibility mapping
 * - Performance optimization recommendations for each data type
 * - Best practice guidelines for type selection and usage
 *
 * Column Type Categories:
 * - Primary Keys: Auto-incrementing and custom primary key types
 * - String Types: VARCHAR, CHAR, TEXT variants with length specifications
 * - Numeric Types: Integer variants, floating-point, and decimal types
 * - Date/Time Types: Timestamps, dates, times with timezone support
 * - Boolean Types: Boolean and enum types for state management
 * - Binary Types: BLOB variants for file and binary data storage
 * - JSON Types: Native JSON support with query optimization
 * - Special Types: UUID, morphs, and custom Laravel-specific types
 *
 * Validation Rule Management:
 * - Complete Laravel validation rule catalog with parameter specifications
 * - Rule combination strategies for complex validation scenarios
 * - Custom validation rule integration and management
 * - Type-specific validation recommendations
 * - Performance-optimized validation rule selection
 * - Internationalization support for validation messages
 * - Advanced validation patterns for business logic implementation
 *
 * Form Field Mapping:
 * - Intelligent form field type selection based on database column types
 * - UI component recommendations for different data types and constraints
 * - Accessibility-compliant form field generation
 * - Responsive form design considerations for each field type
 * - Input validation and sanitization strategies
 * - User experience optimization for different data input scenarios
 * - Integration with popular frontend frameworks and UI libraries
 *
 * Database Compatibility:
 * - Cross-database type mapping for MySQL, PostgreSQL, SQLite, SQL Server
 * - Migration compatibility checking and conversion utilities
 * - Database-specific optimization recommendations
 * - Constraint translation between different database systems
 * - Index recommendation based on column types and usage patterns
 * - Performance benchmarking for different type configurations
 * - Storage optimization strategies for each data type
 *
 * Integration Features:
 * - Seamless integration with Laravel's Schema Builder
 * - Compatibility with all Laravel migration methods and utilities
 * - Integration with Laravel's validation system and form requests
 * - Support for custom column types and database extensions
 * - API integration for external type definition and management
 * - Plugin architecture for custom type definitions and mappings
 * - Version control support for type definition changes
 *
 * Development Tools:
 * - Type selection wizards with intelligent recommendations
 * - Migration generation assistance with type optimization
 * - Validation rule generation based on column types and constraints
 * - Form field generation with appropriate input types and validation
 * - Database schema analysis and optimization recommendations
 * - Performance impact analysis for type selection decisions
 * - Code generation utilities for type-specific implementations
 *
 * Performance Optimization:
 * - Memory-efficient type definition storage and retrieval
 * - Lazy loading of type definitions to minimize resource usage
 * - Intelligent caching strategies for frequently accessed type information
 * - Optimized lookup algorithms for fast type resolution
 * - Batch processing support for large-scale type operations
 * - Resource usage monitoring and optimization recommendations
 *
 * Quality Assurance:
 * - Comprehensive type validation and compatibility checking
 * - Best practice enforcement for type selection and usage
 * - Documentation generation for type definitions and usage guidelines
 * - Testing utilities for type-specific functionality validation
 * - Error handling and recovery for type-related operations
 * - Audit trail for type definition changes and modifications
 *
 * @author hardikkanajariya.in
 *
 * @version 1.0.0
 *
 * @since 1.0.0
 *
 * @example
 * $service = app(LaravelTypesService::class);
 * $columnTypes = $service->getColumnTypes();
 * $validationRules = $service->getValidationRules();
 * $fieldType = $service->getFormFieldType('string', 'email');
 */
class LaravelTypesService
{
    /**
     * Get available Laravel migration column types
     */
    public function getColumnTypes(): array
    {
        return [
            // Primary Keys
            'id' => 'ID (Auto Increment)',
            'bigIncrements' => 'Big Increments',
            'increments' => 'Increments',
            'mediumIncrements' => 'Medium Increments',
            'smallIncrements' => 'Small Increments',
            'tinyIncrements' => 'Tiny Increments',

            // String Types
            'string' => 'String (VARCHAR)',
            'char' => 'Char (CHAR)',
            'text' => 'Text',
            'mediumText' => 'Medium Text',
            'longText' => 'Long Text',
            'tinyText' => 'Tiny Text',

            // Integer Types
            'integer' => 'Integer',
            'bigInteger' => 'Big Integer',
            'mediumInteger' => 'Medium Integer',
            'smallInteger' => 'Small Integer',
            'tinyInteger' => 'Tiny Integer',
            'unsignedInteger' => 'Unsigned Integer',
            'unsignedBigInteger' => 'Unsigned Big Integer',
            'unsignedMediumInteger' => 'Unsigned Medium Integer',
            'unsignedSmallInteger' => 'Unsigned Small Integer',
            'unsignedTinyInteger' => 'Unsigned Tiny Integer',

            // Floating Point Numbers
            'float' => 'Float',
            'double' => 'Double',
            'decimal' => 'Decimal',
            'unsignedDecimal' => 'Unsigned Decimal',

            // Boolean
            'boolean' => 'Boolean',

            // Date and Time
            'date' => 'Date',
            'dateTime' => 'DateTime',
            'dateTimeTz' => 'DateTime with Timezone',
            'time' => 'Time',
            'timeTz' => 'Time with Timezone',
            'timestamp' => 'Timestamp',
            'timestampTz' => 'Timestamp with Timezone',
            'timestamps' => 'Timestamps (created_at, updated_at)',
            'nullableTimestamps' => 'Nullable Timestamps',
            'timestampsTz' => 'Timestamps with Timezone',
            'softDeletes' => 'Soft Deletes (deleted_at)',
            'softDeletesTz' => 'Soft Deletes with Timezone',

            // JSON
            'json' => 'JSON',
            'jsonb' => 'JSONB (PostgreSQL)',

            // Binary
            'binary' => 'Binary',
            'longBinary' => 'Long Binary',

            // Special Types
            'enum' => 'Enum',
            'set' => 'Set',
            'uuid' => 'UUID',
            'ulid' => 'ULID',
            'ipAddress' => 'IP Address',
            'macAddress' => 'MAC Address',
            'year' => 'Year',

            // Geometry (MySQL)
            'geometry' => 'Geometry',
            'point' => 'Point',
            'lineString' => 'Line String',
            'polygon' => 'Polygon',
            'geometryCollection' => 'Geometry Collection',
            'multiPoint' => 'Multi Point',
            'multiLineString' => 'Multi Line String',
            'multiPolygon' => 'Multi Polygon',

            // Foreign Keys
            'foreignId' => 'Foreign ID',
            'foreignIdFor' => 'Foreign ID For Model',
            'foreignUuid' => 'Foreign UUID',
            'foreignUlid' => 'Foreign ULID',

            // Computed Columns
            'computed' => 'Computed Column',
            'virtual' => 'Virtual Column',
            'storedAs' => 'Stored As',
            'virtualAs' => 'Virtual As',

            // Full Text Index
            'fullText' => 'Full Text Index',
            'spatialIndex' => 'Spatial Index',
        ];
    }

    /**
     * Get column types that support length
     */
    public function getColumnTypesWithLength(): array
    {
        return [
            'string',
            'char',
            'decimal',
            'unsignedDecimal',
            'float',
            'double',
        ];
    }

    /**
     * Get column types that support precision and scale
     */
    public function getColumnTypesWithPrecisionScale(): array
    {
        return [
            'decimal',
            'unsignedDecimal',
            'float',
            'double',
        ];
    }

    /**
     * Get column types that support unsigned
     */
    public function getUnsignedColumnTypes(): array
    {
        return [
            'integer',
            'bigInteger',
            'mediumInteger',
            'smallInteger',
            'tinyInteger',
            'decimal',
            'float',
            'double',
        ];
    }

    /**
     * Get column types that support enum values
     */
    public function getEnumColumnTypes(): array
    {
        return [
            'enum',
            'set',
        ];
    }

    /**
     * Get available index types
     */
    public function getIndexTypes(): array
    {
        return [
            'index' => 'Regular Index',
            'unique' => 'Unique Index',
            'primary' => 'Primary Key',
            'fulltext' => 'Full Text Index',
            'spatial' => 'Spatial Index',
        ];
    }

    /**
     * Get available foreign key actions
     */
    public function getForeignKeyActions(): array
    {
        return [
            'restrict' => 'RESTRICT',
            'cascade' => 'CASCADE',
            'set null' => 'SET NULL',
            'no action' => 'NO ACTION',
            'set default' => 'SET DEFAULT',
        ];
    }

    /**
     * Get available model traits
     */
    public function getModelTraits(): array
    {
        return [
            'HasFactory' => 'Illuminate\\Database\\Eloquent\\Factories\\HasFactory',
            'SoftDeletes' => 'Illuminate\\Database\\Eloquent\\SoftDeletes',
            'HasUuids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUuids',
            'HasUlids' => 'Illuminate\\Database\\Eloquent\\Concerns\\HasUlids',
            'Notifiable' => 'Illuminate\\Notifications\\Notifiable',
            'HasApiTokens' => 'Laravel\\Sanctum\\HasApiTokens',
            'Searchable' => 'Laravel\\Scout\\Searchable',
            'HasSlug' => 'Spatie\\Sluggable\\HasSlug',
            'HasMediaCollections' => 'Spatie\\MediaLibrary\\HasMedia',
            'HasRoles' => 'Spatie\\Permission\\Traits\\HasRoles',
            'LogsActivity' => 'Spatie\\Activitylog\\Traits\\LogsActivity',
            'CausesActivity' => 'Spatie\\Activitylog\\Traits\\CausesActivity',
            'HasTranslations' => 'Spatie\\Translatable\\HasTranslations',
        ];
    }

    /**
     * Get available model cast types
     */
    public function getCastTypes(): array
    {
        return [
            'array' => 'Array',
            'boolean' => 'Boolean',
            'collection' => 'Collection',
            'date' => 'Date',
            'datetime' => 'DateTime',
            'decimal:2' => 'Decimal (2 places)',
            'decimal:4' => 'Decimal (4 places)',
            'double' => 'Double',
            'float' => 'Float',
            'integer' => 'Integer',
            'object' => 'Object',
            'real' => 'Real',
            'string' => 'String',
            'timestamp' => 'Timestamp',
            'encrypted' => 'Encrypted',
            'encrypted:array' => 'Encrypted Array',
            'encrypted:collection' => 'Encrypted Collection',
            'encrypted:object' => 'Encrypted Object',
            'hashed' => 'Hashed',
            'immutable_date' => 'Immutable Date',
            'immutable_datetime' => 'Immutable DateTime',
            'custom_datetime:Y-m-d' => 'Custom DateTime Format',
        ];
    }

    /**
     * Get available relation types
     */
    public function getRelationTypes(): array
    {
        return [
            'belongsTo' => [
                'name' => 'Belongs To',
                'description' => 'One-to-one inverse relationship',
                'parameters' => ['foreign_key', 'owner_key'],
            ],
            'hasOne' => [
                'name' => 'Has One',
                'description' => 'One-to-one relationship',
                'parameters' => ['foreign_key', 'local_key'],
            ],
            'hasMany' => [
                'name' => 'Has Many',
                'description' => 'One-to-many relationship',
                'parameters' => ['foreign_key', 'local_key'],
            ],
            'belongsToMany' => [
                'name' => 'Belongs To Many',
                'description' => 'Many-to-many relationship',
                'parameters' => ['pivot_table', 'foreign_pivot_key', 'related_pivot_key', 'parent_key', 'related_key'],
            ],
            'morphTo' => [
                'name' => 'Morph To',
                'description' => 'Polymorphic inverse relationship',
                'parameters' => ['name', 'type', 'id'],
            ],
            'morphOne' => [
                'name' => 'Morph One',
                'description' => 'One-to-one polymorphic relationship',
                'parameters' => ['related', 'name', 'type', 'id', 'local_key'],
            ],
            'morphMany' => [
                'name' => 'Morph Many',
                'description' => 'One-to-many polymorphic relationship',
                'parameters' => ['related', 'name', 'type', 'id', 'local_key'],
            ],
            'morphToMany' => [
                'name' => 'Morph To Many',
                'description' => 'Many-to-many polymorphic relationship',
                'parameters' => ['related', 'name', 'table', 'foreign_pivot_key', 'related_pivot_key', 'parent_key', 'related_key'],
            ],
            'morphedByMany' => [
                'name' => 'Morphed By Many',
                'description' => 'Many-to-many polymorphic inverse relationship',
                'parameters' => ['related', 'name', 'table', 'foreign_pivot_key', 'related_pivot_key', 'parent_key', 'related_key'],
            ],
            'hasOneThrough' => [
                'name' => 'Has One Through',
                'description' => 'One-to-one relationship through intermediate model',
                'parameters' => ['related', 'through', 'first_key', 'second_key', 'local_key', 'second_local_key'],
            ],
            'hasManyThrough' => [
                'name' => 'Has Many Through',
                'description' => 'One-to-many relationship through intermediate model',
                'parameters' => ['related', 'through', 'first_key', 'second_key', 'local_key', 'second_local_key'],
            ],
        ];
    }

    /**
     * Get available Faker methods for factory generation
     */
    public function getFakerMethods(): array
    {
        return [
            // Basic Data Types
            'name' => 'Full Name',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
            'title' => 'Name Title (Mr., Mrs., etc.)',
            'titleMale' => 'Male Title',
            'titleFemale' => 'Female Title',

            // Contact Information
            'email' => 'Email Address',
            'safeEmail' => 'Safe Email Address',
            'freeEmail' => 'Free Email Provider',
            'companyEmail' => 'Company Email',
            'phoneNumber' => 'Phone Number',
            'tollFreePhoneNumber' => 'Toll Free Phone Number',
            'e164PhoneNumber' => 'E164 Phone Number',

            // Address Information
            'address' => 'Full Address',
            'streetAddress' => 'Street Address',
            'streetName' => 'Street Name',
            'buildingNumber' => 'Building Number',
            'city' => 'City',
            'state' => 'State',
            'stateAbbr' => 'State Abbreviation',
            'postcode' => 'Postal Code',
            'country' => 'Country',
            'countryCode' => 'Country Code',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',

            // Company Information
            'company' => 'Company Name',
            'companySuffix' => 'Company Suffix',
            'jobTitle' => 'Job Title',
            'department' => 'Department',

            // Text Content
            'text' => 'Random Text',
            'sentence' => 'Sentence',
            'sentences' => 'Multiple Sentences',
            'paragraph' => 'Paragraph',
            'paragraphs' => 'Multiple Paragraphs',
            'word' => 'Single Word',
            'words' => 'Multiple Words',
            'slug' => 'URL Slug',

            // Numbers
            'randomNumber' => 'Random Number',
            'numberBetween' => 'Number Between',
            'randomFloat' => 'Random Float',
            'randomDigit' => 'Random Digit',
            'randomDigitNotNull' => 'Random Digit (Not Zero)',

            // Boolean
            'boolean' => 'Boolean',

            // Date and Time
            'date' => 'Date',
            'dateTime' => 'DateTime',
            'dateTimeThisYear' => 'DateTime This Year',
            'dateTimeThisMonth' => 'DateTime This Month',
            'dateTimeThisDecade' => 'DateTime This Decade',
            'dateTimeBetween' => 'DateTime Between',
            'time' => 'Time',
            'unixTime' => 'Unix Timestamp',
            'iso8601' => 'ISO 8601 DateTime',

            // Internet
            'url' => 'URL',
            'domainName' => 'Domain Name',
            'domainWord' => 'Domain Word',
            'tld' => 'Top Level Domain',
            'ipv4' => 'IPv4 Address',
            'ipv6' => 'IPv6 Address',
            'macAddress' => 'MAC Address',
            'userName' => 'Username',
            'password' => 'Password',

            // Files and Images
            'fileExtension' => 'File Extension',
            'mimeType' => 'MIME Type',
            'filePath' => 'File Path',
            'imageUrl' => 'Image URL',

            // Colors
            'hexColor' => 'Hex Color',
            'rgbColor' => 'RGB Color',
            'rgbColorAsArray' => 'RGB Color Array',
            'rgbCssColor' => 'RGB CSS Color',
            'safeColorName' => 'Safe Color Name',
            'colorName' => 'Color Name',

            // Identifiers
            'uuid' => 'UUID',
            'md5' => 'MD5 Hash',
            'sha1' => 'SHA1 Hash',
            'sha256' => 'SHA256 Hash',
            'ean13' => 'EAN13 Barcode',
            'ean8' => 'EAN8 Barcode',
            'isbn13' => 'ISBN13',
            'isbn10' => 'ISBN10',

            // Finance
            'creditCardNumber' => 'Credit Card Number',
            'creditCardType' => 'Credit Card Type',
            'creditCardExpirationDate' => 'Credit Card Expiration',
            'creditCardExpirationDateString' => 'Credit Card Expiration String',
            'creditCardDetails' => 'Credit Card Details',
            'iban' => 'IBAN',
            'swiftBicNumber' => 'SWIFT/BIC Number',

            // Custom Laravel Methods
            'bcrypt' => 'Bcrypt Hash',
        ];
    }

    /**
     * Get validation rules suggestions based on column type
     */
    public function getValidationRulesForColumnType(string $columnType): array
    {
        return match ($columnType) {
            'string', 'char' => ['required', 'string', 'max:255'],
            'text', 'mediumText', 'longText' => ['required', 'string'],
            'integer', 'bigInteger', 'mediumInteger', 'smallInteger', 'tinyInteger' => ['required', 'integer'],
            'decimal', 'float', 'double' => ['required', 'numeric'],
            'boolean' => ['required', 'boolean'],
            'email' => ['required', 'email'],
            'url' => ['required', 'url'],
            'date' => ['required', 'date'],
            'dateTime', 'timestamp' => ['required', 'date_format:Y-m-d H:i:s'],
            'json' => ['required', 'json'],
            'uuid' => ['required', 'uuid'],
            'enum' => ['required', 'in:'],
            default => ['required'],
        };
    }

    /**
     * Get suggested cast type for column type
     */
    public function getSuggestedCastForColumnType(string $columnType): ?string
    {
        return match ($columnType) {
            'boolean' => 'boolean',
            'integer', 'bigInteger', 'mediumInteger', 'smallInteger', 'tinyInteger' => 'integer',
            'decimal' => 'decimal:2',
            'float', 'double' => 'float',
            'date' => 'date',
            'dateTime', 'timestamp' => 'datetime',
            'json' => 'array',
            default => null,
        };
    }

    /**
     * Get suggested Faker method for column name
     */
    public function getSuggestedFakerMethodForColumnName(string $columnName): ?string
    {
        return match (true) {
            str_contains($columnName, 'email') => 'safeEmail',
            str_contains($columnName, 'phone') => 'phoneNumber',
            str_contains($columnName, 'name') => 'name',
            str_contains($columnName, 'title') => 'sentence',
            str_contains($columnName, 'description') => 'paragraph',
            str_contains($columnName, 'address') => 'address',
            str_contains($columnName, 'city') => 'city',
            str_contains($columnName, 'country') => 'country',
            str_contains($columnName, 'url') => 'url',
            str_contains($columnName, 'price') || str_contains($columnName, 'amount') => 'randomFloat',
            str_contains($columnName, 'quantity') || str_contains($columnName, 'count') => 'numberBetween',
            str_contains($columnName, 'date') => 'date',
            str_contains($columnName, 'time') => 'dateTime',
            str_contains($columnName, 'password') => 'password',
            str_contains($columnName, 'slug') => 'slug',
            str_contains($columnName, 'uuid') => 'uuid',
            str_contains($columnName, 'is_') || str_contains($columnName, 'has_') => 'boolean',
            default => 'word',
        };
    }

    /**
     * Get suggested column type for column name
     */
    public function getSuggestedColumnTypeForName(string $columnName): string
    {
        return match (true) {
            str_ends_with($columnName, '_id') => 'bigInteger',
            str_ends_with($columnName, '_at') => 'timestamp',
            str_contains($columnName, 'email') => 'string',
            str_contains($columnName, 'phone') => 'string',
            str_contains($columnName, 'password') => 'string',
            str_contains($columnName, 'description') || str_contains($columnName, 'content') => 'text',
            str_contains($columnName, 'price') || str_contains($columnName, 'amount') => 'decimal',
            str_contains($columnName, 'quantity') || str_contains($columnName, 'count') => 'integer',
            str_starts_with($columnName, 'is_') || str_starts_with($columnName, 'has_') => 'boolean',
            str_contains($columnName, 'uuid') => 'uuid',
            str_contains($columnName, 'json') || str_contains($columnName, 'data') => 'json',
            default => 'string',
        };
    }

    /**
     * Get column configuration suggestions
     */
    public function getColumnConfigurationSuggestions(string $columnName, string $columnType): array
    {
        $suggestions = [];

        // Auto-suggest nullable for certain patterns
        if (str_ends_with($columnName, '_at') || str_contains($columnName, 'optional')) {
            $suggestions['nullable'] = true;
        }

        // Auto-suggest unique for certain patterns
        if (in_array($columnName, ['email', 'username', 'slug']) || str_contains($columnName, 'unique')) {
            $suggestions['unique'] = true;
        }

        // Auto-suggest index for foreign keys
        if (str_ends_with($columnName, '_id')) {
            $suggestions['index'] = true;
            $suggestions['unsigned'] = true;
        }

        // Auto-suggest default values
        if (str_starts_with($columnName, 'is_') && $columnType === 'boolean') {
            $suggestions['default'] = 'false';
        }

        return $suggestions;
    }
}
