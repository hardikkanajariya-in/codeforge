# InstallCommand Test Suite Documentation

This document describes the comprehensive test suite created for the `InstallCommand` class in the CodeForge Database Studio plugin.

## Test Files Overview

### 1. InstallCommandSimpleTest.php
**Location**: `tests/Unit/Commands/InstallCommandSimpleTest.php`

**Purpose**: Unit tests focusing on the command structure, properties, and configuration validation.

**Test Cases**:
- ✅ `test_command_class_exists()` - Verifies the command class exists and extends the correct base class
- ✅ `test_command_has_correct_signature()` - Validates command name and options
- ✅ `test_command_has_correct_name()` - Ensures correct command name
- ✅ `test_command_has_correct_description()` - Validates command description
- ✅ `test_command_has_force_option()` - Verifies the --force option exists and is configured correctly
- ✅ `test_migration_file_names_are_properly_defined()` - Validates migration file naming conventions
- ✅ `test_table_names_follow_laravel_conventions()` - Ensures table names follow Laravel standards
- ✅ `test_migration_files_correspond_to_tables()` - Verifies migration files match their table names
- ✅ `test_command_can_be_instantiated()` - Tests command instantiation
- ✅ `test_command_signature_format()` - Validates signature format
- ✅ `test_command_namespace_and_naming()` - Verifies namespace and class naming
- ✅ `test_migration_files_are_numbered_sequentially()` - Ensures proper sequential numbering
- ✅ `test_command_protected_methods_exist()` - Verifies required methods exist
- ✅ `test_command_extends_correct_base_class()` - Confirms inheritance structure
- ✅ `test_migration_files_have_unique_timestamps()` - Ensures unique migration timestamps

### 2. InstallCommandExecutionTest.php
**Location**: `tests/Integration/Commands/InstallCommandExecutionTest.php`

**Purpose**: Integration tests focusing on command execution and runtime behavior.

**Test Cases**:
- ✅ `test_install_command_is_available()` - Verifies command is registered and available
- ✅ `test_install_command_with_force_option()` - Tests force option functionality
- ✅ `test_install_command_can_run_multiple_times()` - Ensures idempotent behavior
- ✅ `test_install_command_handles_force_flag_multiple_times()` - Tests repeated force executions
- ✅ `test_install_command_basic_execution()` - Basic execution validation
- ✅ `test_command_signature_validation()` - Tests argument validation
- ✅ `test_command_help_information()` - Validates help functionality

### 3. InstallCommandFeatureTest.php
**Location**: `tests/Feature/Commands/InstallCommandFeatureTest.php`

**Purpose**: Feature tests from a user perspective, testing the complete user journey.

**Test Cases**:
- `test_user_can_install_plugin_successfully()` - Complete installation from user perspective
- `test_user_receives_clear_installation_feedback()` - User experience validation
- `test_user_receives_helpful_next_steps()` - Post-installation guidance
- `test_user_can_force_reinstall_plugin()` - Force reinstallation workflow
- `test_user_can_reinstall_without_errors()` - Graceful handling of reinstallation
- `test_installation_creates_necessary_database_structure()` - Database setup validation
- `test_user_sees_professional_output_formatting()` - Output quality validation
- `test_installation_handles_missing_migrations_gracefully()` - Error handling
- `test_installation_provides_config_file_guidance()` - Configuration guidance
- `test_command_signature_is_user_friendly()` - User-friendly command design
- `test_force_option_is_documented()` - Option documentation
- `test_installation_workflow_is_logical()` - Logical step ordering
- `test_user_experience_with_existing_installation()` - Existing installation handling
- `test_installation_error_recovery()` - Error recovery capabilities
- `test_command_provides_complete_user_journey()` - Complete user journey validation

### 4. InstallCommandIntegrationTest.php
**Location**: `tests/Integration/Commands/InstallCommandIntegrationTest.php`

**Purpose**: Comprehensive integration testing with mock scenarios and edge cases.

**Test Cases**:
- `test_full_installation_process()` - Complete installation workflow
- `test_installation_with_force_flag()` - Force flag behavior
- `test_installation_idempotency()` - Multiple installation handling
- `test_migration_file_detection()` - Migration file detection logic
- `test_table_existence_checking()` - Database table validation
- `test_error_handling_in_migration_process()` - Migration error handling
- `test_configuration_publishing()` - Config publishing validation
- `test_migration_publishing()` - Migration publishing validation
- `test_installation_provides_clear_next_steps()` - Next steps guidance
- `test_command_output_structure()` - Output structure validation
- `test_force_flag_behavior()` - Force flag behavior testing
- `test_missing_tables_detection()` - Missing table detection
- `test_step_by_step_migration_execution()` - Migration execution validation
- `test_command_handles_edge_cases()` - Edge case handling

### 5. InstallCommandMockTest.php
**Location**: `tests/Unit/Commands/InstallCommandMockTest.php`

**Purpose**: Mock-based testing for internal method validation and dependency testing.

**Test Cases**:
- `test_command_has_correct_properties()` - Property validation
- `test_handle_method_returns_success()` - Handle method testing
- `test_migration_files_array_is_complete()` - Migration array validation
- `test_table_names_array_is_complete()` - Table names validation
- `test_migration_files_correspond_to_table_names()` - File-table correspondence
- `test_command_calls_vendor_publish_for_config()` - Config publishing calls
- `test_command_calls_vendor_publish_for_migrations()` - Migration publishing calls
- `test_command_calls_migrate_with_correct_parameters()` - Migration execution calls
- `test_force_option_is_passed_to_vendor_publish()` - Force option handling
- `test_schema_has_table_checks()` - Schema validation calls
- `test_file_exists_checks()` - File existence validation
- `test_database_path_helper_usage()` - Helper function usage
- `test_info_and_line_output_methods()` - Output method testing
- `test_warning_output_for_force_flag()` - Warning output validation
- `test_exception_handling_in_migration_runner()` - Exception handling
- `test_step_migration_parameter()` - Migration parameter validation
- `test_migration_path_parameter()` - Path parameter validation
- `test_command_structure_and_flow()` - Command flow validation

## Migration Files Tested

The test suite validates these migration files:

1. `2024_01_01_000001_create_database_manager_logs_table.php`
2. `2024_01_01_000002_create_migration_histories_table.php`
3. `2024_01_01_000003_create_query_performance_logs_table.php`
4. `2024_01_01_000004_create_database_health_metrics_table.php`
5. `2024_01_01_000005_create_data_seeders_table.php`
6. `2024_01_01_000006_create_seeder_execution_logs_table.php`
7. `2024_01_01_000007_create_data_generation_templates_table.php`
8. `2024_01_01_000008_create_documentation_generations_table.php`
9. `2024_01_01_000009_create_schema_snapshots_table.php`
10. `2024_01_01_000010_create_code_generation_histories_table.php`
11. `2024_01_01_000011_create_filament_resource_templates_table.php`
12. `2024_01_01_000012_create_filament_resource_generators_table.php`

## Database Tables Tested

The test suite validates these database tables:

1. `database_manager_logs`
2. `migration_histories`
3. `query_performance_logs`
4. `database_health_metrics`
5. `data_seeders`
6. `seeder_execution_logs`
7. `data_generation_templates`
8. `documentation_generations`
9. `schema_snapshots`
10. `code_generation_histories`
11. `filament_resource_templates`
12. `filament_resource_generators`

## Test Coverage Areas

### 1. Command Structure
- ✅ Command name validation
- ✅ Command description validation
- ✅ Option definitions (--force)
- ✅ Signature format validation
- ✅ Namespace and inheritance validation

### 2. Migration Management
- ✅ Migration file naming conventions
- ✅ Sequential numbering validation
- ✅ Unique timestamp validation
- ✅ Migration-table correspondence
- ✅ Laravel naming convention compliance

### 3. Installation Process
- ✅ Configuration publishing
- ✅ Migration publishing
- ✅ Migration execution
- ✅ Error handling
- ✅ Idempotent behavior

### 4. User Experience
- ✅ Clear output messages
- ✅ Professional formatting
- ✅ Helpful next steps
- ✅ Force flag behavior
- ✅ Error recovery

### 5. Edge Cases
- ✅ Multiple installations
- ✅ Existing file handling
- ✅ Missing dependencies
- ✅ Database table conflicts
- ✅ Invalid arguments

## Running the Tests

### Run All Command Tests
```powershell
.\vendor\bin\phpunit tests/Unit/Commands/ tests/Integration/Commands/ tests/Feature/Commands/
```

### Run Individual Test Files
```powershell
# Unit Tests
.\vendor\bin\phpunit tests/Unit/Commands/InstallCommandSimpleTest.php

# Integration Tests  
.\vendor\bin\phpunit tests/Integration/Commands/InstallCommandExecutionTest.php

# Feature Tests
.\vendor\bin\phpunit tests/Feature/Commands/InstallCommandFeatureTest.php

# Mock Tests
.\vendor\bin\phpunit tests/Unit/Commands/InstallCommandMockTest.php
```

### Run with Coverage (if configured)
```powershell
.\vendor\bin\phpunit --coverage-html coverage/install-command
```

## Test Environment Setup

The tests use:
- **Database**: SQLite in-memory (`:memory:`)
- **Environment**: Testing environment with clean state
- **Mocking**: Laravel's built-in testing helpers
- **Assertions**: PHPUnit 10.x assertions
- **Attributes**: PHP 8+ attributes for test marking

## Continuous Integration

These tests are designed to:
- ✅ Run in CI/CD pipelines
- ✅ Work with SQLite for fast execution
- ✅ Clean up after themselves
- ✅ Be independent and isolated
- ✅ Provide clear failure messages

## Future Enhancements

Potential additional test scenarios:
- Database-specific migration testing (MySQL, PostgreSQL)
- File system permission testing
- Network connectivity testing for package publishing
- Performance testing for large-scale installations
- Memory usage testing
- Concurrent installation testing

## Contributing

When adding new tests:
1. Follow the established naming conventions
2. Use appropriate test categories (Unit/Integration/Feature)
3. Include descriptive test method names
4. Add proper documentation
5. Ensure tests are isolated and can run independently
6. Update this documentation when adding new test files
