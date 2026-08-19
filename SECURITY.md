# Security Policy

## Supported versions

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |

## Reporting a vulnerability

If you discover a security vulnerability, please **do not** open a public GitHub issue.

Email [contact@hardikkanajariya.in](mailto:contact@hardikkanajariya.in) with:

- A description of the vulnerability
- Steps to reproduce
- Impact assessment if known
- Your contact information (optional)

You should receive a response within 72 hours. We will work with you to understand and address the issue before any public disclosure.

## Security practices

When deploying CodeForge Database Studio in production:

- Restrict Filament admin access with authentication and authorization policies
- Keep PHP, Laravel, Filament, and this package updated
- Use environment variables for database credentials
- Review `config/codeforge-database-studio.php` security settings before enabling destructive operations
