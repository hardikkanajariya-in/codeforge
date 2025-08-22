# CodeForge Database Studio - Installation Guide

## 🚀 Quick Installation (Recommended)

### Method 1: Automated Installation
1. Upload the plugin files to your Laravel project
2. Run the automated installer:
   ```bash
   php install.php
   ```
3. Add the plugin to your Filament panel:
   ```php
   // app/Providers/Filament/AdminPanelProvider.php
   use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;
   
   ->plugins([
       CodeForgeStudioPlugin::make(),
   ])
   ```

### Method 2: Manual Installation
```bash
# 1. Publish configuration
php artisan vendor:publish --tag="codeforge-database-studio-config"

# 2. Publish migrations
php artisan vendor:publish --tag="codeforge-database-studio-migrations"

# 3. Run migrations
php artisan migrate

# 4. Clear cache
php artisan optimize:clear
```

## 📋 System Requirements
- **PHP:** 8.1 or higher
- **Laravel:** 10.x or higher  
- **FilamentPHP:** 3.x
- **Database:** MySQL 5.7+, PostgreSQL 11+, SQLite 3.8+

## 🎯 Quick Start
1. Access your Filament admin panel
2. Look for "Database Overview" in navigation
3. Explore the comprehensive database management features

## 🛠️ Troubleshooting

### Common Issues:
**Issue:** Plugin not visible in Filament panel
**Solution:** Ensure plugin is registered in your panel provider

**Issue:** Migration errors
**Solution:** Check database connection and permissions

**Issue:** Permission errors
**Solution:** Set proper file permissions:
```bash
chmod -R 755 storage/ bootstrap/cache/
```

## 📞 Support
- **Email:** contact@hardikkanajariya.in
- **Documentation:** Comprehensive guides included
- **Response Time:** Within 24 hours
