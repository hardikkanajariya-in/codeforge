# Changelog

All notable changes to the HkDevs CodeForge Database Studio plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-08-22

### 🚀 Initial Commercial Release
- **Production-ready release** for commercial distribution
- Complete database management and code generation suite for FilamentPHP
- Advanced schema visualization and migration management
- Intelligent data seeding with realistic test data generation
- Automated documentation generator with multiple export formats
- Comprehensive code generation for Laravel models, factories, and Filament resources

### 🐛 Bug Fixes
- **Fixed Filament resource redirect issue**: Fixed bug where creating records in Filament resources redirected to non-existent random IDs instead of the actual created record
- **Improved record creation stability**: Added custom `getRedirectUrl()` methods to all create pages to handle Laravel's `lastInsertId()` inconsistencies
- **Enhanced data integrity**: Implemented fallback logic to find actual records by name when primary key lookup fails

### ✨ Core Features
- Database Overview with real-time analytics
- Advanced Migration Management with history tracking
- Visual Schema Designer with drag-and-drop interface
- Database Health Monitoring with performance alerts
- Smart Data Seeding with context-aware generation
- Documentation Generator with multiple export formats
- Code Generation Suite for Laravel and Filament
