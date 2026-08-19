# Database Health Monitoring - PHPUnit Test Cases

This directory contains comprehensive PHPUnit test cases for the Database Health Monitoring functionality of the HkDevs CodeForge Database Studio plugin. These tests implement all scenarios described in the Comprehensive Test Cases Documentation.

## 📋 Test Coverage Overview

### Core Test Suites

1. **ComprehensiveDatabaseHealthMonitoringTest.php**
   - TC-HEALTH-001: Real-time Query Performance Tracking
   - TC-HEALTH-002: Slow Query Detection & Analysis
   - TC-HEALTH-003: Health Metrics Collection Command
   - TC-HEALTH-004: Connection Status & Health Checks
   - TC-HEALTH-005: Performance Alerts & Thresholds
   - TC-HEALTH-006: Health Report Generation
   - TC-HEALTH-007: Query Performance Analysis

2. **HealthMonitoringCommandsTest.php**
   - TC-CMD-003: Health Monitoring Commands Testing
   - `database-manager:collect-metrics` command testing
   - `database-manager:toggle-query-logging` command testing
   - `database-manager:cleanup-logs` command testing

3. **HealthMonitoringWidgetsTest.php**
   - TC-WID-001: Database Stats Widget Testing
   - TC-WID-002: Database Health Widget Testing
   - Widget integration and functionality testing

4. **HealthMonitoringPerformanceTest.php**
   - TC-PERF-001: Large Database Handling
   - TC-PERF-002: Concurrent User Testing
   - TC-PERF-003: Memory Usage Optimization
   - Performance and scalability testing

5. **HealthMonitoringTestSuite.php**
   - Test suite runner and overview
   - Integration testing
   - Documentation compliance verification

## 🧪 Test Case Implementation Details

### TC-HEALTH-001: Real-time Query Performance Tracking
**File**: `ComprehensiveDatabaseHealthMonitoringTest.php`
**Method**: `test_real_time_query_performance_tracking()`

**Purpose**: Test continuous query performance monitoring system

**Test Steps**:
1. Enable query performance tracking
2. Execute various types of database queries
3. Verify real-time performance metrics collection
4. Test query execution time tracking accuracy
5. Verify performance data aggregation and storage

**Assertions**:
- Query logs are created in database
- Execution times are accurately recorded
- Query hashes and types are properly set
- Performance metrics are correctly aggregated

### TC-HEALTH-002: Slow Query Detection & Analysis
**File**: `ComprehensiveDatabaseHealthMonitoringTest.php`
**Method**: `test_slow_query_detection_and_analysis()`

**Purpose**: Test automatic identification and logging of performance bottlenecks

**Test Steps**:
1. Configure slow query threshold (default: 1000ms)
2. Execute queries that exceed the threshold
3. Verify automatic slow query detection
4. Test slow query logging and categorization
5. Verify performance bottleneck identification

**Assertions**:
- Slow queries are automatically detected
- Query categorization works correctly
- Performance metrics show accurate slow query counts
- Bottleneck identification functions properly

### TC-HEALTH-003: Health Metrics Collection Command
**File**: `HealthMonitoringCommandsTest.php`
**Method**: `test_collect_metrics_command_execution()`

**Purpose**: Test automated health data collection via `database-manager:collect-metrics`

**Test Steps**:
1. Execute command manually
2. Test with specific connection option
3. Verify metrics are collected and stored properly
4. Test automated collection simulation
5. Verify metric data accuracy and completeness

**Assertions**:
- Command executes successfully (exit code 0)
- Health metrics are stored in database
- Command output contains expected messages
- Metrics have all required fields populated

### TC-HEALTH-004: Connection Status & Health Checks
**File**: `ComprehensiveDatabaseHealthMonitoringTest.php`
**Method**: `test_connection_status_and_health_checks()`

**Purpose**: Test real-time database connection health monitoring

**Test Steps**:
1. Monitor active database connections
2. Test connection failure detection
3. Verify connection timeout handling
4. Test connection recovery monitoring
5. Verify health check interval functionality

**Assertions**:
- Connection status is accurately reported
- Response times are measured correctly
- Health summary contains required data
- Connection testing works for all database types

### TC-HEALTH-005: Performance Alerts & Thresholds
**File**: `ComprehensiveDatabaseHealthMonitoringTest.php`
**Method**: `test_performance_alerts_and_thresholds()`

**Purpose**: Test configurable performance warning system

**Test Steps**:
1. Configure performance alert thresholds
2. Trigger conditions that should generate alerts
3. Verify alert notifications are sent appropriately
4. Test different alert types and escalation
5. Test alert suppression and management

**Assertions**:
- Alert thresholds are properly configured
- Alert conditions trigger correctly
- Warning and critical alerts are generated
- Alert data is stored with proper status levels

## 🚀 Running the Tests

### Prerequisites
- PHP 8.3+ with PHPUnit
- Laravel 12.x or 13.x
- CodeForge Database Studio plugin installed
- Test database configured

### Running All Health Monitoring Tests
```bash
# Run all health monitoring tests
php artisan test --filter="HealthMonitoring"

# Run specific test suite
php artisan test tests/Feature/HealthMonitoring/ComprehensiveDatabaseHealthMonitoringTest.php

# Run with coverage
php artisan test --filter="HealthMonitoring" --coverage
```

### Running Individual Test Cases
```bash
# TC-HEALTH-001: Real-time Query Performance Tracking
php artisan test --filter="test_real_time_query_performance_tracking"

# TC-HEALTH-002: Slow Query Detection
php artisan test --filter="test_slow_query_detection_and_analysis"

# TC-CMD-003: Health Monitoring Commands
php artisan test --filter="HealthMonitoringCommandsTest"

# Widget Testing
php artisan test --filter="HealthMonitoringWidgetsTest"

# Performance Testing
php artisan test --filter="HealthMonitoringPerformanceTest"
```

### Environment Configuration for Testing
```php
// In your test environment (.env.testing or phpunit.xml)
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

// Plugin Configuration
CODEFORGE_ENABLE_QUERY_LOGGING=true
CODEFORGE_SLOW_QUERY_THRESHOLD=1000
CODEFORGE_HEALTH_MONITORING=true
```

## 📊 Test Coverage Metrics

### Feature Coverage
- ✅ Real-time Query Performance Tracking
- ✅ Slow Query Detection & Analysis
- ✅ Health Metrics Collection
- ✅ Connection Status Monitoring
- ✅ Performance Alerts & Thresholds
- ✅ Health Report Generation
- ✅ Query Performance Analysis
- ✅ Artisan Commands
- ✅ Widget Integration
- ✅ Performance & Load Testing

### Test Types
- **Unit Tests**: 15+ test methods
- **Integration Tests**: 8+ test methods
- **Performance Tests**: 10+ test methods
- **Widget Tests**: 12+ test methods
- **Command Tests**: 10+ test methods

### Code Coverage Areas
- DatabaseHealthService class
- QueryPerformanceListener class
- Health monitoring commands
- Dashboard widgets
- Performance optimization
- Error handling
- Memory usage
- Concurrent operations

## 🔧 Test Configuration

### Database Schema
The tests automatically create the required database tables:
- `database_health_metrics` - Health monitoring data
- `query_performance_logs` - Query execution logs
- `database_manager_logs` - General activity logs

### Test Data Generation
Tests use factories and seeders to generate realistic test data:
- Query performance logs with various execution times
- Health metrics with different status levels
- Simulated user activities and concurrent operations

### Performance Testing
Performance tests are designed to:
- Test with large datasets (1000+ records)
- Simulate concurrent user access
- Monitor memory usage
- Verify query optimization
- Test widget performance under load

## 📈 Expected Results

### Performance Benchmarks
- Query logging: < 10ms per query
- Health metrics collection: < 5 seconds for full scan
- Widget loading: < 2 seconds with large datasets
- Memory usage: < 100MB for large operations
- Concurrent operations: < 2 seconds average response time

### Quality Metrics
- All tests should pass with 100% success rate
- No memory leaks during extended operations
- Proper error handling for all edge cases
- Accurate data collection and reporting
- Responsive widget performance

## 🐛 Troubleshooting

### Common Issues

1. **Migration Errors**
   ```bash
   # Ensure test migrations run
   php artisan migrate --env=testing
   ```

2. **Memory Issues**
   ```bash
   # Increase memory limit for performance tests
   php -d memory_limit=512M artisan test --filter="PerformanceTest"
   ```

3. **Database Connection Issues**
   ```bash
   # Verify test database configuration
   php artisan config:show database.connections.testing
   ```

4. **Missing Dependencies**
   ```bash
   # Install test dependencies
   composer install --dev
   ```

### Test Environment Setup
```bash
# Set up test environment
cp .env.example .env.testing
php artisan key:generate --env=testing
php artisan migrate --env=testing

# Run plugin installation for testing
php artisan codeforge-database-studio:install --env=testing
```

## 📝 Contributing

When adding new health monitoring features:

1. **Create corresponding tests** in the appropriate test file
2. **Follow naming conventions**: `test_feature_name_description()`
3. **Include comprehensive assertions** for all expected behaviors
4. **Add performance tests** for operations that may impact performance
5. **Update this documentation** with new test cases

### Test Naming Convention
```php
// Format: test_{feature}_{specific_functionality}
public function test_real_time_query_performance_tracking(): void
public function test_slow_query_detection_and_analysis(): void
public function test_health_metrics_collection_command(): void
```

### Assertion Guidelines
- Use specific assertions: `assertCount()`, `assertArrayHasKey()`, etc.
- Include meaningful failure messages
- Test both positive and negative cases
- Verify data integrity and consistency

## 📞 Support

For questions about these test cases:
- **Documentation**: See `COMPREHENSIVE_TEST_CASES_FOR_USER.md`
- **Issues**: Report bugs with test reproduction steps
- **Features**: Request new test coverage in issues
- **Support**: contact contact@hardikkanajariya.in

## 📄 License

These test cases are part of CodeForge Database Studio, released under the MIT License.

---

**Generated Test Cases**: 70+ comprehensive test methods  
**Coverage Areas**: 13 major test scenarios  
**Test Types**: Unit, Integration, Performance, Widget, Command  
**Last Updated**: August 2025  
**Author**: HkDevs (hardikkanajariya.in)
