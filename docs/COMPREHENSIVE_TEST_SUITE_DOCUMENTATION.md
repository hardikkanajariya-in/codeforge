# Comprehensive PHPUnit Test Suite Documentation

## Overview

This document provides a complete overview of the PHPUnit test suite for the CodeForge Database Studio plugin. The test suite covers all aspects of the plugin from environment setup to advanced feature integration.

## Test Structure

### Environment Tests (`tests/Unit/Environment/`)

#### 1. EnvironmentRequirementsTest.php
- **Test Case ID**: TC-ENV-001
- **Purpose**: Verify plugin works with minimum system requirements
- **Coverage**: PHP version compatibility, required extensions, framework availability

#### 2. InstallationProcessTest.php
- **Test Case ID**: TC-ENV-002
- **Purpose**: Verify complete installation workflow
- **Coverage**: Service provider registration, configuration publishing, migration execution

#### 3. PluginRegistrationTest.php
- **Test Case ID**: TC-ENV-003
- **Purpose**: Verify plugin registers correctly with Filament panels
- **Coverage**: Plugin instantiation, feature toggles, panel registration

#### 4. ConfigurationValidationTest.php
- **Test Case ID**: TC-ENV-004
- **Purpose**: Verify plugin configuration is properly set up
- **Coverage**: Configuration structure, validation, caching, environment overrides

#### 5. DatabaseMigrationsTest.php
- **Test Case ID**: TC-ENV-005
- **Purpose**: Test all plugin migrations execute successfully
- **Coverage**: Table creation, constraints, indexes, rollback capability

### Core Feature Tests (`tests/Feature/`)

#### 1. SchemaDesigner/SchemaDesignerCoreTest.php
- **Test Case ID**: TC-SCHEMA-001
- **Purpose**: Test core schema designer features
- **Coverage**: Table listing, schema inspection, relationship mapping, export capability

#### 2. MigrationManager/MigrationManagerCoreTest.php
- **Test Case ID**: TC-MIGRATION-001
- **Purpose**: Test migration manager features
- **Coverage**: History tracking, batch management, status tracking, rollback, performance

#### 3. HealthMonitoring/DatabaseHealthMonitoringTest.php
- **Test Case ID**: TC-HEALTH-001
- **Purpose**: Test database health monitoring features
- **Coverage**: Connection health, performance monitoring, threshold alerts, automated checks

#### 4. SmartSeeding/SmartDataSeedingTest.php
- **Test Case ID**: TC-SEEDING-001
- **Purpose**: Test smart data seeding features
- **Coverage**: Template generation, relationship seeding, conditional data, validation

### Integration Tests (`tests/Integration/`)

#### 1. PluginIntegrationTest.php
- **Test Case ID**: TC-INTEGRATION-001
- **Purpose**: Test complete plugin functionality and feature integration
- **Coverage**: End-to-end workflows, feature interoperability, performance under load

### Core Dependency Tests (`tests/Unit/Core/`)

#### 1. DependencyManagementTest.php
- **Test Case ID**: TC-ENV-006
- **Purpose**: Verify proper dependency resolution
- **Coverage**: Composer integration, package discovery, version constraints

## Test Execution

### Running All Tests
```bash
cd packages/codeforge-database-studio
vendor/bin/phpunit
```

### Running Specific Test Suites
```bash
# Environment tests only
vendor/bin/phpunit tests/Unit/Environment/

# Feature tests only
vendor/bin/phpunit tests/Feature/

# Integration tests only
vendor/bin/phpunit tests/Integration/

# Specific test class
vendor/bin/phpunit tests/Unit/Environment/EnvironmentRequirementsTest.php
```

### Running Tests with Coverage
```bash
vendor/bin/phpunit --coverage-html coverage-report/
```

### Running Tests with Verbose Output
```bash
vendor/bin/phpunit --verbose
```

## Test Configuration

### PHPUnit Configuration (phpunit.xml)
The test suite uses a custom PHPUnit configuration that:
- Uses SQLite in-memory database for testing
- Configures test environment variables
- Sets up proper autoloading
- Configures coverage reporting

### Test Environment Setup
Each test class extends the base `TestCase` which:
- Sets up the testing environment
- Configures database connections
- Provides helper methods for assertions
- Handles test data cleanup

## Test Categories

### 1. Unit Tests
- Test individual components in isolation
- Mock external dependencies
- Fast execution
- High coverage of edge cases

### 2. Feature Tests
- Test complete features with real database
- Test user workflows
- Integration between related components
- Real data scenarios

### 3. Integration Tests
- Test multiple features working together
- End-to-end scenarios
- Performance under load
- Error handling across features

## Test Data Management

### Database Setup
- Uses in-memory SQLite for fast testing
- Creates fresh database for each test
- Automatic cleanup after tests
- Migration testing with real schemas

### Test Factories
- Generates realistic test data
- Supports relationship creation
- Configurable data patterns
- Consistent data across tests

### Test Helpers
- Custom assertion methods
- Database state verification
- Performance measurement utilities
- Error simulation helpers

## Coverage Goals

### Minimum Coverage Targets
- **Unit Tests**: 90% line coverage
- **Feature Tests**: 80% functionality coverage
- **Integration Tests**: 70% workflow coverage
- **Overall**: 85% combined coverage

### Coverage Areas
- All public methods
- Error handling paths
- Configuration scenarios
- Database operations
- User interactions

## Continuous Integration

### Automated Testing
- Runs on every commit
- Tests multiple PHP versions (8.1, 8.2, 8.3)
- Tests multiple Laravel versions (10.x, 11.x)
- Tests multiple database drivers (MySQL, PostgreSQL, SQLite)

### Performance Benchmarks
- Test execution time monitoring
- Memory usage tracking
- Database query optimization
- Load testing scenarios

## Test Maintenance

### Adding New Tests
1. Identify the test category (Unit/Feature/Integration)
2. Create test file in appropriate directory
3. Follow naming conventions
4. Include proper documentation
5. Update this documentation

### Test Naming Conventions
- Test files: `*Test.php`
- Test methods: `test_*`
- Use descriptive names
- Include test case IDs in comments

### Best Practices
- One assertion per test when possible
- Use descriptive test names
- Clean up test data
- Mock external services
- Test both success and failure scenarios

## Troubleshooting

### Common Issues

#### Database Connection Errors
```bash
# Ensure SQLite extension is loaded
php -m | grep sqlite

# Check database configuration
cat phpunit.xml
```

#### Memory Limit Issues
```bash
# Increase memory limit for tests
php -dmemory_limit=512M vendor/bin/phpunit
```

#### Test Isolation Problems
```bash
# Run tests in separate processes
vendor/bin/phpunit --process-isolation
```

### Debug Mode
```bash
# Run with debug output
vendor/bin/phpunit --debug

# Run specific failing test
vendor/bin/phpunit --filter test_specific_failing_test
```

## Performance Considerations

### Test Optimization
- Use in-memory database for speed
- Minimize database operations
- Efficient test data generation
- Parallel test execution where possible

### Resource Management
- Proper cleanup after tests
- Memory usage monitoring
- Database connection pooling
- Temporary file management

## Security Testing

### Security Test Coverage
- SQL injection prevention
- CSRF protection validation
- Input sanitization
- Access control verification
- Configuration security

### Vulnerability Testing
- Dependency scanning
- Code security analysis
- Configuration validation
- Permission verification

## Deployment Testing

### Pre-deployment Checks
- All tests must pass
- Performance benchmarks met
- Security validation complete
- Documentation updated

### Production Simulation
- Test with production-like data
- Load testing scenarios
- Error handling validation
- Backup and recovery testing

## Reporting

### Test Reports
- HTML coverage reports
- JUnit XML for CI/CD
- Performance metrics
- Security scan results

### Metrics Tracking
- Test execution time trends
- Coverage percentage trends
- Failure rate monitoring
- Performance regression detection

---

## Test Case Reference

| Test ID | Test Name | Category | Priority | Status |
|---------|-----------|----------|----------|--------|
| TC-ENV-001 | Environment Requirements | Unit | High | ✅ |
| TC-ENV-002 | Installation Process | Unit | High | ✅ |
| TC-ENV-003 | Plugin Registration | Unit | High | ✅ |
| TC-ENV-004 | Configuration Validation | Unit | High | ✅ |
| TC-ENV-005 | Database Migrations | Unit | High | ✅ |
| TC-ENV-006 | Dependency Management | Unit | Medium | ✅ |
| TC-SCHEMA-001 | Schema Designer Core | Feature | High | ✅ |
| TC-MIGRATION-001 | Migration Manager Core | Feature | High | ✅ |
| TC-HEALTH-001 | Health Monitoring | Feature | High | ✅ |
| TC-SEEDING-001 | Smart Data Seeding | Feature | High | ✅ |
| TC-INTEGRATION-001 | Plugin Integration | Integration | High | ✅ |

## Conclusion

This comprehensive test suite ensures the CodeForge Database Studio plugin meets all quality, performance, and security requirements. Regular execution of these tests during development and deployment helps maintain plugin reliability and user satisfaction.

For questions or issues with the test suite, please refer to the troubleshooting section or contact the plugin developer.
