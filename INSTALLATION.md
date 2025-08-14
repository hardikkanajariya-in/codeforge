# CodeForge Database Studio - Installation Guide

## 🚀 Quick Installation Guide

### Step 1: Purchase Your License

Choose the license that fits your needs:

- **🏠 Single Project License** - €79.00
  - Use on one Laravel project
  - 1 year of updates
  - Email support

- **🏢 Multiple Project License** - €129.00
  - Use on up to 5 Laravel projects  
  - Priority email support
  - 1 year of updates

- **🌐 Unlimited License** - €199.00
  - Unlimited Laravel projects
  - Priority support (24h response)
  - Lifetime updates
  - Private Discord access

### Step 2: Add Private Repository

After purchasing, add our private Composer repository to your `composer.json`:

```json
{
  "repositories": [
    {
      "type": "composer",
      "url": "https://9f9d2843-f44a-4d2a-ad42-c65ac7728bb1.composer.sh"
    }
  ]
}
```

### Step 3: Install the Package

Run the installation command:

```bash
composer require hkdevs/codeforge-database-studio
```

### Step 4: Authentication

When prompted, enter your credentials:

```
Loading composer repositories with package information
Authentication required (9f9d2843-f44a-4d2a-ad42-c65ac7728bb1.composer.sh):
Username: [your-purchase-email@example.com]
Password: [your-license-key]
```

**Important**: 
- **Username**: The email address you used for purchase
- **Password**: The license key sent to your email after purchase

### Step 5: Install the Plugin

```bash
php artisan codeforge-database-studio:install
```

### Step 6: Register with Filament

Add to your Filament panel provider:

```php
// app/Providers/Filament/AdminPanelProvider.php

use HkDevs\CodeForgeStudio\CodeForgeStudioPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugins([
            CodeForgeStudioPlugin::make(),
        ]);
}
```

## 🔧 Advanced Configuration

### Environment Configuration

Add to your `.env` file for advanced features:

```env
# CodeForge Database Studio Configuration
CODEFORGE_ENABLED=true
CODEFORGE_LICENSE_KEY=your-license-key-here
CODEFORGE_FINGERPRINT=your-domain.com
```

### Custom Configuration

Publish and customize the configuration:

```bash
php artisan vendor:publish --tag=codeforge-database-studio-config
```

### Update the Plugin

To update to the latest version:

```bash
composer update hkdevs/codeforge-database-studio
php artisan codeforge-database-studio:install --force
```

## 🆘 Troubleshooting

### Authentication Issues

**Problem**: Authentication failed
**Solution**: Verify your email and license key are correct

**Problem**: "Package not found"
**Solution**: Ensure the repository URL is added to `composer.json`

### License Issues

**Problem**: "License expired" or "License invalid"
**Solution**: Contact support at [support@hardikkanajariya.in](mailto:support@hardikkanajariya.in)

### Installation Issues

**Problem**: Migration errors
**Solution**: Run `php artisan migrate:fresh` in development

**Problem**: Plugin not appearing in Filament
**Solution**: Clear cache: `php artisan cache:clear && php artisan config:clear`

## 📞 Support

- **Email**: [support@hardikkanajariya.in](mailto:support@hardikkanajariya.in)
- **Documentation**: [https://codeforge.hardikkanajariya.in/docs](https://codeforge.hardikkanajariya.in/docs)
- **Website**: [https://hardikkanajariya.in](https://hardikkanajariya.in)

---

*This installation guide is for CodeForge Database Studio - Premium Database Management Suite for FilamentPHP*
