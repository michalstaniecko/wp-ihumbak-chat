# Contributing to iHumbak Chat

Thank you for your interest in contributing to iHumbak Chat! We welcome contributions from the community.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Workflow](#development-workflow)
- [Branch Naming Convention](#branch-naming-convention)
- [Commit Message Format](#commit-message-format)
- [Coding Standards](#coding-standards)
- [Testing](#testing)
- [Pull Request Process](#pull-request-process)
- [Version Numbering](#version-numbering)

## Code of Conduct

This project adheres to a code of conduct. By participating, you are expected to uphold this code. Please be respectful and constructive in your communications.

## Getting Started

1. **Fork the repository** on GitHub
2. **Clone your fork** locally:
   ```bash
   git clone https://github.com/YOUR-USERNAME/wp-ihumbak-chat.git
   cd wp-ihumbak-chat
   ```

3. **Install dependencies**:
   ```bash
   npm install
   composer install
   ```

4. **Create a new branch** for your work:
   ```bash
   git checkout -b feature/your-feature-name
   ```

## Development Workflow

### Building Assets

- **Development mode** (with watch):
  ```bash
  npm run dev
  ```

- **Production build**:
  ```bash
  npm run build
  ```

### Linting

- **PHP CodeSniffer**:
  ```bash
  composer run phpcs
  ```

- **Auto-fix PHP code style**:
  ```bash
  composer run phpcbf
  ```

### Testing

- **Run PHPUnit tests**:
  ```bash
  composer run test
  ```

## Branch Naming Convention

Use descriptive branch names with the following prefixes:

- `feature/` - New features (e.g., `feature/add-widget-animation`)
- `fix/` - Bug fixes (e.g., `fix/email-validation-issue`)
- `hotfix/` - Critical fixes for production (e.g., `hotfix/security-patch`)
- `refactor/` - Code refactoring (e.g., `refactor/cleanup-admin-code`)
- `docs/` - Documentation updates (e.g., `docs/update-readme`)
- `test/` - Adding or updating tests (e.g., `test/add-rest-api-tests`)
- `chore/` - Maintenance tasks (e.g., `chore/update-dependencies`)

## Commit Message Format

Follow the conventional commits specification:

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types:

- **feat**: A new feature
- **fix**: A bug fix
- **docs**: Documentation only changes
- **style**: Code style changes (formatting, missing semi-colons, etc.)
- **refactor**: Code change that neither fixes a bug nor adds a feature
- **perf**: Performance improvement
- **test**: Adding or updating tests
- **chore**: Changes to build process or auxiliary tools

### Examples:

```
feat(widget): add pulse animation to chat bubble

Add a subtle pulse animation to the chat bubble to draw user attention
when the widget is first loaded.

Closes #42
```

```
fix(security): sanitize email input properly

The email field was not being properly sanitized before saving to
the database, which could lead to XSS vulnerabilities.

Fixes #56
```

## Coding Standards

### PHP

- Follow [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/)
- Use WordPress core functions for sanitization, validation, and escaping
- Add DocBlocks to all functions and classes
- Code must pass PHP_CodeSniffer checks

### JavaScript

- Use modern ES6+ syntax
- Follow consistent indentation (2 spaces)
- Add comments for complex logic
- Use meaningful variable and function names

### CSS

- Use TailwindCSS utility classes when possible
- Follow mobile-first responsive design
- Keep custom CSS minimal and well-organized

## Testing

- Write PHPUnit tests for new PHP functionality
- Test across different WordPress versions (minimum 5.9)
- Test across different PHP versions (minimum 7.4)
- Manually test the UI in different browsers (Chrome, Firefox, Safari, Edge)
- Test responsive design on mobile, tablet, and desktop

## Pull Request Process

1. **Update your branch** with the latest from `main`:
   ```bash
   git checkout main
   git pull origin main
   git checkout your-branch
   git rebase main
   ```

2. **Ensure all tests pass**:
   ```bash
   npm run build
   composer run phpcs
   composer run test
   ```

3. **Create a Pull Request** on GitHub with:
   - Clear title describing the change
   - Detailed description of what was changed and why
   - Reference to any related issues (e.g., "Closes #42")
   - Screenshots for UI changes

4. **Wait for review** - A maintainer will review your PR and may request changes

5. **Address feedback** - Make any requested changes and push to your branch

6. **Merge** - Once approved, a maintainer will merge your PR

## Version Numbering

We use [Semantic Versioning](https://semver.org/) (SemVer):

- **MAJOR** version (1.0.0 → 2.0.0): Incompatible API changes
- **MINOR** version (1.0.0 → 1.1.0): New features, backwards-compatible
- **PATCH** version (1.0.0 → 1.0.1): Bug fixes, backwards-compatible

### Pre-release versions:

- **Alpha**: `1.0.0-alpha.1` - Early development
- **Beta**: `1.0.0-beta.1` - Feature complete, testing phase
- **RC**: `1.0.0-rc.1` - Release candidate

## Questions?

If you have questions about contributing, please open an issue with the `question` label.

Thank you for contributing! 🎉
