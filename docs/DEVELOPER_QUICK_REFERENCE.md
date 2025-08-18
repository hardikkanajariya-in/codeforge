# Developer Quick Reference - Plugin Directory

## ⚠️ Plugin Directory Environment

**This is a PLUGIN PACKAGE directory - NOT a Laravel application!**

### 🚫 What NOT to do:

- ❌ `php artisan serve`
- ❌ `php artisan migrate`
- ❌ `php artisan vendor:publish`
- ❌ Any Laravel artisan commands

### ✅ What you CAN do:

- ✅ Modify source code in `/src`
- ✅ Update resources in `/resources`
- ✅ Run tests with `./tests/run-tests.php`
- ✅ Manually copy assets for testing
- ✅ Update documentation in `/docs`

## 🔧 Asset Management

### Manual Asset Copying

```powershell
# CSS files
copy "resources\css\schema-designer-v2.css" "public\vendor\codeforge\css\schema-designer-v2.css"

# JavaScript files  
copy "resources\js\schema-designer-v2.js" "public\vendor\codeforge\js\schema-designer-v2.js"
```

### Using the Publish Script

```bash
php publish-assets.php
```

### Directory Structure for Assets

```
public/vendor/codeforge/
├── css/
│   └── schema-designer-v2.css
└── js/
    └── schema-designer-v2.js
```

## 🧪 Testing Workflow

### For Plugin Development:

1. **Modify plugin code** in this directory
2. **Copy assets manually** using commands above
3. **Set up test Laravel app** with plugin installed
4. **Test features** in the Laravel application
5. **Run unit tests** with `./tests/run-tests.php`

### For Laravel Application Testing:

```bash
# In Laravel app directory (NOT here!)
php artisan codeforge:install
php artisan vendor:publish --tag=codeforge-studio-assets --force
```

## 📝 File Locations

### Core Plugin Files:
- **Pages**: `/src/Pages/SchemaDesigner.php`
- **Services**: `/src/Services/`
- **Resources**: `/src/Resources/`
- **Commands**: `/src/Commands/InstallCommand.php`

### Assets:
- **CSS**: `/resources/css/schema-designer-v2.css`
- **JavaScript**: `/resources/js/schema-designer-v2.js`
- **Views**: `/resources/views/pages/schema-designer.blade.php`

### Configuration:
- **Service Provider**: `/src/CodeForgeStudioServiceProvider.php`
- **Plugin Class**: `/src/CodeForgeStudioPlugin.php`
- **Config**: `/config/codeforge-database-studio.php`

## 🐛 Common Issues

### "No hint path defined for [codeforge-studio]"
- **Cause**: View namespace not registered properly
- **Fix**: Check ServiceProvider `loadViewsFrom()` configuration

### "404 Asset Not Found"
- **Cause**: Assets not published to correct location
- **Fix**: Run manual asset copying commands above

### "Class not found" errors
- **Cause**: Namespace issues or autoloading problems
- **Fix**: Check namespace consistency in all files

## 📚 Documentation Updates

After making changes, check these files:
- `/docs/ANYSTACK_PURCHASE_INSTALLATION.md`
- `/docs/DEV_DOCS_CONFIGURATION.md`
- `/README.md`
- `/CHANGELOG.md`

## 🏗️ Architecture Notes

- **Namespace**: `HkDevs\CodeForgeStudio`
- **Filament Version**: v3
- **CSS Framework**: Tailwind + Filament patterns
- **JavaScript**: Alpine.js + D3.js
- **License**: Commercial (€79/€129/€199 tiers)
- **Branding**: hardikkanajariya.in

---

**Remember**: Always work in the context of a Laravel application for final testing, but develop in this plugin directory with manual asset management.
