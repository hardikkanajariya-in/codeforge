# Changelog

All notable changes to the HkDevs CodeForge Database Studio plugin will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### 🐛 Bug Fixes
- **Fixed Filament resource redirect issue**: Fixed bug where creating records in Filament resources redirected to non-existent random IDs instead of the actual created record
- **Improved record creation stability**: Added custom `getRedirectUrl()` methods to all create pages to handle Laravel's `lastInsertId()` inconsistencies
- **Enhanced data integrity**: Implemented fallback logic to find actual records by name when primary key lookup fails

## [1.0.0-alpha.1] - 2025-08-14

### 🚀 Initial Prerelease
- **Alpha prerelease** for early testing and feedback
- All core features implemented and tested
- Ready for community testing before stable release

## [1.0.0] - TBD

### 🚀 Initial Commercial Release
- **Production-ready release** for anystack.sh and commercial distribution
