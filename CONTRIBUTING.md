# Contributing to CodeForge Database Studio

Thank you for your interest in contributing! This project is free and open source under the MIT License, maintained by the community with [Hardik Kanajariya](https://hardikkanajariya.in) as the active maintainer.

## How to contribute

1. **Fork** the repository on GitHub.
2. **Create a branch** from `master` for your change (`feature/my-feature` or `fix/my-bugfix`).
3. **Make your changes** with clear commits and focused diffs.
4. **Run tests** before opening a pull request:
   ```bash
   composer install
   vendor/bin/phpunit
   ```
5. **Open a pull request** against `master` with a clear description of what changed and why.

## Development setup

```bash
git clone https://github.com/hardikkanajariya-in/codeforge.git
cd codeforge
composer install
vendor/bin/phpunit
```

For local integration testing, install the package into a Laravel + Filament application via a path repository:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "../codeforge"
        }
    ],
    "require": {
        "hkdevs/codeforge-database-studio": "@dev"
    }
}
```

## Code guidelines

- Follow existing naming, structure, and Filament/Laravel conventions in this repository.
- Keep changes scoped to the problem you are solving.
- Add or update tests when behavior changes.
- Run `vendor/bin/pint` if you have Laravel Pint available in your environment.

## Reporting issues

Use [GitHub Issues](https://github.com/hardikkanajariya-in/codeforge/issues) for bug reports and feature requests. Include:

- PHP, Laravel, and Filament versions
- Steps to reproduce
- Expected vs actual behavior
- Relevant logs or screenshots

## Security issues

Please do **not** open public issues for security vulnerabilities. See [SECURITY.md](SECURITY.md).

## License

By contributing, you agree that your contributions will be licensed under the MIT License.
