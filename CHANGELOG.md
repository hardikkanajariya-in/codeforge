# Changelog

All notable changes to the CodeForge Database Studio plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2025-08-27

### 🚀 Major CodeCanyon Compliance Update
- **Standalone Operation**: Removed all external license validation dependencies
- **Complete Implementations**: Fixed all TODO comments with proper functionality
- **Simplified Installation**: Standard composer installation workflow
- **Open Source Licensing**: Migrated to MIT License for CodeCanyon compatibility

### ✅ Added
- Complete authorization logic for policy methods
- Full CRUD implementations for generated controllers
- Professional code implementations replacing all placeholders
- Simplified installation documentation
- Community-friendly support references

### 🗑️ Removed
- External license validation service and middleware
- Anystack API dependencies
- Commercial pricing references from documentation
- License key requirements
- External service dependencies

### 🔧 Changed
- Updated composer.json to use MIT license
- Simplified configuration files
- Updated README with open-source friendly content
- Replaced commercial support references with GitHub-based support
- Updated installation process to standard composer workflow

### 🐛 Fixed
- All TODO method implementations completed
- Authorization logic properly implemented
- Test comments cleaned up
- External API references removed
- Commercial licensing conflicts resolved

### 💔 Breaking Changes
- License validation system completely removed
- Configuration structure simplified (license-related configs removed)
- Installation process changed to standard composer workflow

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
