# Releasing

Releases are automated via GitHub Actions when you push a semver tag.

## Prerequisites

- All changes are merged to `master`
- [CHANGELOG.md](CHANGELOG.md) has a section for the new version (e.g. `## [1.0.1] - 2026-08-19`)
- CI on `master` is green

## Create a release

```bash
# 1. Update CHANGELOG.md (move items from [Unreleased] to the new version section)

# 2. Commit and push
git add CHANGELOG.md
git commit -m "Prepare release v1.0.1"
git push origin master

# 3. Tag and push (triggers the Release workflow)
git tag -a v1.0.1 -m "Release v1.0.1"
git push origin v1.0.1
```

## What the workflow does

1. **Test before release** — full matrix (Laravel 12/13 × Filament 4/5), Pint, PHPUnit
2. **Publish GitHub release** — creates a release with notes from `CHANGELOG.md`
3. **Packagist** — updates automatically if your [Packagist GitHub hook](https://packagist.org/about#how-to-update-packages) is enabled

## Tag format

| Tag | Type |
|-----|------|
| `v1.0.0` | Stable release |
| `v1.0.0-beta.1` | Pre-release (marked as prerelease on GitHub) |

## Install a specific version

```bash
composer require hkdevs/codeforge-database-studio:1.0.1
```

## Manual release (fallback)

If Actions fails, create a release manually at:
https://github.com/hardikkanajariya-in/codeforge/releases/new
