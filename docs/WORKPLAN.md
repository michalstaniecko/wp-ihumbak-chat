# Plan Prac - iHumbak Chat Plugin

**Ostatnia aktualizacja**: 14 listopada 2025  
**Wersja**: 1.0  
**Szacunkowy czas realizacji**: 4-5 tygodni

---

## 📋 Spis Treści

1. [Fazy Projektu](#fazy-projektu)
2. [Zadania - Faza 1: Strukturyzacja](#faza-1-strukturyzacja)
3. [Zadania - Faza 2: Backend](#faza-2-backend)
4. [Zadania - Faza 3: Frontend](#faza-3-frontend)
5. [Zadania - Faza 4: Admin Panel](#faza-4-admin-panel)
6. [Zadania - Faza 5: Deployment & CI/CD](#faza-5-deployment--cicd)
7. [Zadania - Faza 6: Testing & QA](#faza-6-testing--qa)
8. [Zmienne Środowiskowe & Konfiguracja](#zmienne-środowiskowe--konfiguracja)

---

## 🎯 Fazy Projektu

```
┌─────────────────────────────────────────────────────────┐
│ FAZA 1: STRUKTURYZACJA (2-3 dni)                        │
│ ├─ Struktura katalogów                                  │
│ ├─ Main plugin file                                     │
│ ├─ Composer & Build config                              │
│ └─ GitHub Actions setup                                 │
├─────────────────────────────────────────────────────────┤
│ FAZA 2: BACKEND (8-10 dni)                              │
│ ├─ Security & Validation classes                        │
│ ├─ Custom Post Type (CPT)                               │
│ ├─ REST API endpoints                                   │
│ ├─ Email notifications                                  │
│ └─ Settings & Options management                        │
├─────────────────────────────────────────────────────────┤
│ FAZA 3: FRONTEND (6-8 dni)                              │
│ ├─ TailwindCSS setup & build                            │
│ ├─ Widget HTML template                                 │
│ ├─ JavaScript logic (AJAX, validation)                  │
│ ├─ CSS animation & responsiveness                       │
│ └─ SVG icons                                            │
├─────────────────────────────────────────────────────────┤
│ FAZA 4: ADMIN PANEL (5-7 dni)                           │
│ ├─ Admin menu registration                              │
│ ├─ Messages list page                                   │
│ ├─ Message view page                                    │
│ ├─ Settings page                                        │
│ └─ Admin CSS                                            │
├─────────────────────────────────────────────────────────┤
│ FAZA 5: DEPLOYMENT & CI/CD (3-4 dni)                    │
│ ├─ GitHub Actions workflows                             │
│ ├─ Automated release generation                         │
│ ├─ Asset compression/optimization                       │
│ └─ Versioning & tagging strategy                        │
├─────────────────────────────────────────────────────────┤
│ FAZA 6: TESTING & QA (5-7 dni)                          │
│ ├─ Unit tests (PHPUnit)                                 │
│ ├─ Integration tests                                    │
│ ├─ Browser/frontend tests                               │
│ ├─ Security audit                                       │
│ └─ Documentation & README                               │
└─────────────────────────────────────────────────────────┘

Całkowicie: ~4-5 tygodni
```

---

## FAZA 1: STRUKTURYZACJA

### 1.1 Struktura katalogów i plików bazowych
**Czas**: 1 dzień  
**Priorytet**: 🔴 KRYTYCZNY

- [ ] Utworzyć katalogi:
  - `includes/` - klasy backendu
  - `admin/pages/` - strony panelu admina
  - `admin/css/` - style admina
  - `public/js/` - JavaScript frontend
  - `public/css/` - CSS frontend
  - `public/templates/` - szablony HTML
  - `assets/icons/` - ikony SVG
  - `languages/` - pliki tłumaczeń (i18n)
  - `.github/workflows/` - GitHub Actions

- [ ] Utworzyć plik główny `ihumbak-chat.php`
  - Plugin header (name, version, author, license)
  - Text domain i domain path
  - Activation/deactivation hooks
  - Main plugin class loader

- [ ] Utworzyć `includes/class-ihumbak-chat.php`
  - Main plugin orchestrator class
  - Init hooks i filters
  - Load all dependencies

- [ ] Utworzyć `.gitignore`
  - Exclude: `node_modules/`, `vendor/`, `dist/`, `.env`, `*.log`

- [ ] Utworzyć `composer.json`
  - PHP dependencies (opcjonalnie dla libraries)
  - Autoloader

### 1.2 Build & Development Setup
**Czas**: 1 dzień  
**Priorytet**: 🔴 KRYTYCZNY

- [ ] Utworzyć `package.json`
  - Scripts: `dev`, `build`, `watch`, `test`
  - Dependencies: TailwindCSS, PostCSS, Autoprefixer
  - DevDependencies: npm-run-all, clean-css-cli

- [ ] Utworzyć `tailwind.config.js`
  - Content paths (PHP templates, JS)
  - Extend: colors, spacing
  - Optimize dla produkcji (purge)

- [ ] Utworzyć `postcss.config.js`
  - Konfiguracja PostCSS
  - Autoprefixer dla compatibility

- [ ] Utworzyć `webpack.config.js` (opcjonalnie)
  - Bundle JavaScript (jeśli jest potrzeba)
  - Output do `public/js/`

### 1.3 GitHub Repository Setup
**Czas**: 1 dzień  
**Priorytet**: 🔴 KRYTYCZNY

- [ ] Skonfigurować `.github/workflows/`:
  - Lint workflow (PHP_CodeSniffer)
  - Test workflow (PHPUnit)
  - Release workflow (na tag push)
  - Build workflow (TailwindCSS compile)

- [ ] Utworzyć `CONTRIBUTING.md`
  - Wytyczne dla contributors
  - Branch naming convention
  - Commit message format
  - Version numbering (semver)

- [ ] Utworzyć `README.md` (wersja inicjalna)

---

## FAZA 2: BACKEND

### 2.1 Security & Validation Classes
**Czas**: 2 dni  
**Priorytet**: 🔴 KRYTYCZNY

**Plik**: `includes/class-ihumbak-security.php`

- [ ] Klasa `Ihumbak_Security`:
  - `validate_email($email)` - walidacja formatu email
  - `sanitize_message($message)` - sanityzacja treści
  - `verify_recaptcha($token)` - weryfikacja reCAPTCHA v3
  - `check_rate_limit($ip)` - sprawdzenie rate limitingu
  - `get_client_ip()` - pobranie IP klienta
  - `generate_nonce()` - generowanie nonce
  - `verify_nonce($nonce)` - weryfikacja nonce

- [ ] Implementacja rate limitingu:
  - Transientu WP dla IP + timestamp
  - Konfigurowalny limit (domyślnie 5/godzina)
  - Zwrot błędu 429 przy przekroczeniu

- [ ] Integracja reCAPTCHA v3:
  - cURL request do Google API
  - Timeout 5 sekund
  - Score validation (min 0.5)
  - Error handling

### 2.2 Custom Post Type Registration
**Czas**: 1 dzień  
**Priorytet**: 🟠 WYSOKI

**Plik**: `includes/class-ihumbak-messages.php`

- [ ] Klasa `Ihumbak_Messages`:
  - `register_cpt()` - rejestracja CPT `ihumbak_message`
  - `register_post_meta()` - rejestracja meta fieldów:
    - `ihumbak_email`
    - `ihumbak_ip`
    - `ihumbak_user_agent`
    - `ihumbak_referer`
    - `ihumbak_read`
    - `ihumbak_responded`
  - `create_message($data)` - helper do tworzenia postu
  - `get_messages($args)` - query wiadomości z filtrowaniem
  - `mark_as_read($post_id)` - oznaczenie jako przeczytane

### 2.3 REST API Endpoints
**Czas**: 2 dni  
**Priorytet**: 🔴 KRYTYCZNY

**Plik**: `includes/class-ihumbak-rest-api.php`

- [ ] Klasa `Ihumbak_REST_API`:
  - `register_routes()` - rejestracja wszystkich endpointów

  **Endpoint 1: Send Message**
  ```
  POST /wp-json/ihumbak-chat/v1/send-message
  ```
  - Parametry: email, message, recaptcha_token
  - Walidacja (nonce, email, message length, reCAPTCHA)
  - Rate limit check
  - Zapis do DB
  - Wysłanie emaila
  - Response: JSON z statusem

  **Endpoint 2: Get Settings (PUBLIC)**
  ```
  GET /wp-json/ihumbak-chat/v1/settings
  ```
  - Zwrot publicznie dostępnych ustawień
  - reCAPTCHA site key, widget color, position
  - Wyłączone dla niezalogowanych (jeśli wymagane)

- [ ] Implementacja error handling:
  - WP_Error dla błędów walidacji
  - Właściwe HTTP status codes (400, 429, 500)
  - Secure error messages

### 2.4 Email Notifications
**Czas**: 1 dzień  
**Priorytet**: 🟠 WYSOKI

**Plik**: `includes/class-ihumbak-email.php`

- [ ] Klasa `Ihumbak_Email`:
  - `send_admin_notification($message_data)` - email do admina
  - `get_email_template($data)` - HTML template emaila
  - `get_admin_email()` - pobierz email admina
  - Hook: `ihumbak_email_sent` - akcja po wysłaniu

- [ ] Szablon emaila:
  - Nagłówek z branding pluginu
  - Dane wiadomości (od kogo, treść)
  - Link do panelu admina (przeczytaj wiadomość)
  - Footer z linkami

### 2.5 Settings & Options Management
**Czas**: 1,5 dnia  
**Priorytet**: 🟠 WYSOKI

**Plik**: `includes/class-ihumbak-settings.php`

- [ ] Klasa `Ihumbak_Settings`:
  - `get_option($key, $default)` - helper do pobrania opcji
  - `update_option($key, $value)` - helper do aktualizacji
  - `get_all_options()` - pobierz wszystkie ustawienia
  - `get_defaults()` - domyślne wartości

- [ ] Obsługiwane opcje:
  ```php
  ihumbak_admin_email
  ihumbak_recaptcha_site_key
  ihumbak_recaptcha_secret_key
  ihumbak_spam_limit (default: 5)
  ihumbak_enable_notifications (default: 1)
  ihumbak_widget_position (default: bottom-right)
  ihumbak_widget_color (default: #007bff)
  ihumbak_widget_title (default: Get in Touch)
  ```

- [ ] Walidacja wartości:
  - Email format
  - Liczby (całkowite, dodatnie)
  - Kolory (hex format)
  - Select values

---

## FAZA 3: FRONTEND

### 3.1 TailwindCSS Setup & Build
**Czas**: 1,5 dnia  
**Priorytet**: 🟠 WYSOKI

- [ ] Zainstalować TailwindCSS via npm:
  - `npm install -D tailwindcss postcss autoprefixer`
  - `npx tailwindcss init -p`

- [ ] Konfiguracja `tailwind.config.js`:
  - Content paths: `public/templates/**/*.html`, `public/js/**/*.js`, `includes/**/*.php`
  - Extend: custom colors (primary, secondary)
  - SafeList dla dynamicznych klas (widget color)
  - Production purge

- [ ] Setup npm scripts w `package.json`:
  ```json
  "scripts": {
    "dev": "tailwindcss -i public/css/input.css -o public/css/ihumbak-widget.css --watch",
    "build": "tailwindcss -i public/css/input.css -o public/css/ihumbak-widget.css --minify"
  }
  ```

- [ ] Utworzyć `public/css/input.css`:
  - @tailwind directives
  - Custom CSS layers
  - Custom animations

- [ ] Utworzyć `public/css/ihumbak-widget.css` (output)
  - Generated by TailwindCSS

### 3.2 Widget HTML Template
**Czas**: 1 dzień  
**Priorytet**: 🔴 KRYTYCZNY

**Plik**: `public/templates/widget.html`

- [ ] HTML struktura:
  - **Chat bubble icon** (container)
    - SVG icon
    - Data attributes (nonce, site-key, admin-email)
  
  - **Formularz widget** (hidden by default)
    - **Header**
      - Tytuł "Get in Touch" (z settings)
      - Przycisk zamknięcia
    
    - **Body**
      - Email input field
      - Message textarea
      - Character counter
      - Validation error messages
    
    - **Footer**
      - Send button
      - Loading spinner
      - reCAPTCHA badge
    
    - **Notifications**
      - Success toast
      - Error toast

- [ ] CSS classes: Tailwind utility classes
- [ ] Data attributes dla JS hooków

### 3.3 JavaScript Logic
**Czas**: 2 dni  
**Priorytet**: 🔴 KRYTYCZNY

**Plik**: `public/js/ihumbak-widget.js`

- [ ] Moduł inicjalizacji:
  - `init()` - inicjacja widgetu na DOMContentLoaded
  - Load settings z API `/wp-json/ihumbak-chat/v1/settings`
  - Inject reCAPTCHA script

- [ ] Logika widgetu:
  - `toggleWidget()` - otwieranie/zamykanie
  - Event listeners na wszystkie elementy

- [ ] Walidacja formularza:
  - `validateEmail(email)` - real-time validation
  - `validateMessage(message)` - length check (min 5 chars)
  - Display error messages pod polami
  - Disable submit button jeśli błędy

- [ ] Wysyłanie AJAX:
  - `sendMessage()` - POST do `/wp-json/ihumbak-chat/v1/send-message`
  - Headers: Content-Type, X-WP-Nonce
  - Get reCAPTCHA token: `grecaptcha.execute()`
  - Loading state (disable button, show spinner)
  - Error/success handling

- [ ] Komunikaty użytkownika:
  - Success toast (zielony) - "Message sent! 🎉"
  - Error toast (czerwony) - error message z API
  - Validation messages (żółty) - pod polami

- [ ] Animacje:
  - Widget expand/collapse (fade + slide)
  - Toast appear/disappear
  - Loading spinner
  - Pulse animation na bubble

- [ ] Responsiveness:
  - Detect mobile/tablet
  - Adjust widget position based on viewport
  - Mobile: full width z paddingiem
  - Tablet: medium width
  - Desktop: standard width

### 3.4 SVG Icons
**Czas**: 0,5 dnia  
**Priorytet**: 🟡 ŚREDNI

**Plik**: `assets/icons/chat-icon.svg`

- [ ] Crear chat/message SVG icon:
  - Rozmiar: 24x24 px (scalable)
  - Linia stroke
  - Kolor: biały (dla dark bubble)
  - Simplified design

- [ ] Ikony dodatkowe:
  - Close icon (X)
  - Loading spinner
  - Success checkmark
  - Error icon

### 3.5 CSS Animations & Responsive
**Czas**: 1 dzień  
**Priorytet**: 🟠 WYSOKI

**Plik**: `public/css/input.css`

- [ ] Custom Tailwind layers:
  ```css
  @layer components {
    .widget-bubble { /* base styles */ }
    .widget-form { /* form styles */ }
    .toast { /* toast styles */ }
  }
  
  @layer utilities {
    .animate-pulse-subtle { /* pulse animation */ }
    .animate-slide-up { /* slide animation */ }
  }
  ```

- [ ] Responsive breakpoints:
  - sm (640px): mobile adjustments
  - md (768px): tablet adjustments
  - lg (1024px): desktop adjustments

- [ ] Z-index management:
  - Bubble: 40
  - Widget form: 50
  - Toasts: 60
  - reCAPTCHA badge: 30

---

## FAZA 4: ADMIN PANEL

### 4.1 Admin Menu Registration
**Czas**: 1 dzień  
**Priorytet**: 🟠 WYSOKI

**Plik**: `includes/class-ihumbak-admin.php`

- [ ] Klasa `Ihumbak_Admin`:
  - `add_admin_menu()` - hook `admin_menu`
  - Main menu: "iHumbak Chat"
  - Submenu: "Messages", "Settings"
  - Capability: `manage_options`
  - Icons: Dashicon lub SVG

- [ ] Load admin CSS i JS w `admin_enqueue_scripts` hook

### 4.2 Messages List Page
**Czas**: 2 dni  
**Priorytet**: 🟠 WYSOKI

**Plik**: `admin/pages/messages-list.php`

- [ ] Tabela wiadomości:
  - WP_List_Table subclass OR custom HTML table
  - Kolumny:
    - Email nadawcy
    - Skrót treści (max 100 chars)
    - Data wysłania (formatowana)
    - Status (przeczytano: ✓/○)
    - Akcje (View, Mark as read, Delete)

- [ ] Filtry:
  - Status filter (dropdown)
  - Date filter (7 dni, miesiąc, custom range)
  - Email search field

- [ ] Bulk actions:
  - Mark as read
  - Delete

- [ ] Pagination:
  - 20 wiadomości na stronę
  - Links do stron

- [ ] Sortowanie:
  - Po dacie (default DESC)
  - Po statusie
  - Po emailu

### 4.3 Message View Page
**Czas**: 1 dzień  
**Priorytet**: 🟠 WYSOKI

**Plik**: `admin/pages/message-view.php`

- [ ] Podgląd pełnej wiadomości:
  - Od: email nadawcy
  - Data: timestamp
  - Treść: full message content (escaped)
  - Metadane: IP, user-agent, referer

- [ ] Akcje:
  - Mark as read button (jeśli nieprzeczytana)
  - Delete button
  - Back link
  - Reply/Forward template (opcjonalnie)

- [ ] Security:
  - Weryfikacja nonce
  - Capability check
  - Post ID sanitization

### 4.4 Settings Page
**Czas**: 2 dni  
**Priorytet**: 🔴 KRYTYCZNY

**Plik**: `admin/pages/settings.php`

- [ ] Formularz ustawień:
  - Email administratora (text input, walidacja email)
  - reCAPTCHA Site Key (text)
  - reCAPTCHA Secret Key (password, hidden)
  - Rate limit (number input)
  - Widget position (select: bottom-right, bottom-left, top-right, top-left)
  - Widget color (color picker)
  - Widget title (text input)
  - Enable email notifications (checkbox)
  - Test reCAPTCHA button

- [ ] Walidacja na save:
  - Sanitize values
  - Validate formats
  - Error messages

- [ ] Success message:
  - "Settings saved successfully"

- [ ] Test reCAPTCHA endpoint:
  - AJAX request
  - Weryfikacja test tokena
  - Display result (success/error)

### 4.5 Admin CSS
**Czas**: 0,5 dnia  
**Priorytet**: 🟡 ŚREDNI

**Plik**: `admin/css/admin-style.css`

- [ ] Styling dla:
  - Messages list table
  - Message view page
  - Settings form
  - Custom inputs (color picker, selects)
  - Buttons i akcje

---

## FAZA 5: DEPLOYMENT & CI/CD

### 5.1 GitHub Actions Workflows
**Czas**: 2 dni  
**Priorytet**: 🔴 KRYTYCZNY

**Plik**: `.github/workflows/`

#### 5.1.1 Lint Workflow (PHP_CodeSniffer)
**Plik**: `.github/workflows/lint.yml`

```yaml
name: Lint PHP
on: [push, pull_request]

jobs:
  lint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Install PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '7.4'
      - name: Install dependencies
        run: composer install
      - name: Run PHPCS
        run: vendor/bin/phpcs --standard=WordPress includes/ admin/ public/
```

#### 5.1.2 Build CSS Workflow
**Plik**: `.github/workflows/build-css.yml`

```yaml
name: Build CSS
on: [push]

jobs:
  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - uses: actions/setup-node@v3
        with:
          node-version: '18'
      - run: npm install
      - run: npm run build
      - name: Commit built files
        uses: stefanzweifel/git-auto-commit-action@v4
        with:
          commit_message: 'Build: Compile TailwindCSS'
```

#### 5.1.3 Release Workflow (Na TAG)
**Plik**: `.github/workflows/release.yml`

```yaml
name: Create Release
on:
  push:
    tags:
      - 'v*'

jobs:
  release:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      
      - name: Setup Node
        uses: actions/setup-node@v3
        with:
          node-version: '18'
      
      - name: Install dependencies
        run: npm install && composer install
      
      - name: Build CSS
        run: npm run build
      
      - name: Build release package
        run: |
          mkdir -p build
          cp -r includes/ admin/ public/ assets/ languages/ build/
          cp ihumbak-chat.php build/
          cp README.md build/
          cp LICENSE build/
          cd build && zip -r ../ihumbak-chat-${GITHUB_REF#refs/tags/}.zip .
      
      - name: Create GitHub Release
        uses: actions/create-release@v1
        env:
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}
        with:
          tag_name: ${{ github.ref }}
          release_name: Release ${{ github.ref }}
          draft: false
          prerelease: false
      
      - name: Upload Release Asset
        uses: actions/upload-release-asset@v1
        env:
          GITHUB_TOKEN: ${{ secrets.GITHUB_TOKEN }}
        with:
          upload_url: ${{ steps.create_release.outputs.upload_url }}
          asset_path: ./ihumbak-chat-${{ github.ref_name }}.zip
          asset_name: ihumbak-chat-${{ github.ref_name }}.zip
          asset_content_type: application/zip
```

#### 5.1.4 Tests Workflow
**Plik**: `.github/workflows/test.yml`

```yaml
name: PHPUnit Tests
on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    services:
      mysql:
        image: mysql:5.7
        env:
          MYSQL_ROOT_PASSWORD: root
        options: >-
          --health-cmd="mysqladmin ping"
          --health-interval=10s
          --health-timeout=5s
          --health-retries=3
    
    steps:
      - uses: actions/checkout@v3
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '7.4'
          extensions: mysql
      - run: composer install
      - run: vendor/bin/phpunit
```

### 5.2 Versioning & Release Strategy
**Czas**: 1 dzień  
**Priorytet**: 🟠 WYSOKI

- [ ] Wersjonowanie (Semantic Versioning):
  - Format: `v1.0.0`, `v1.1.0-beta`, etc.
  - Tag naming: `v*` (e.g., `v1.0.0`)

- [ ] Branch strategy:
  - `main` - stabilny kod, release ready
  - `develop` - development branch
  - Feature branches: `feature/feature-name`

- [ ] Release checklist:
  - [ ] Update version w `ihumbak-chat.php`
  - [ ] Update `CHANGELOG.md`
  - [ ] Merge do `main`
  - [ ] Create tag: `git tag -a v1.0.0 -m "Version 1.0.0"`
  - [ ] Push tag: `git push origin v1.0.0`
  - [ ] GitHub Actions automatycznie generuje release + ZIP

### 5.3 Asset Compression & Optimization
**Czas**: 1 dzień  
**Priorytet**: 🟡 ŚREDNI

- [ ] CSS minifikacja (via TailwindCSS `--minify`)
- [ ] JavaScript minifikacja (opcjonalnie via terser)
- [ ] SVG optimization (SVGO)
- [ ] Build script w `package.json`:
  ```json
  "build": "npm run build:css && npm run build:js",
  "build:css": "tailwindcss -i public/css/input.css -o public/css/ihumbak-widget.css --minify",
  "build:js": "echo 'JS build (if needed)'"
  ```

---

## FAZA 6: TESTING & QA

### 6.1 Unit Tests (PHPUnit)
**Czas**: 2 dni  
**Priorytet**: 🟡 ŚREDNI

**Katalog**: `tests/`

- [ ] Setup PHPUnit:
  - `composer require --dev phpunit/phpunit`
  - `phpunit.xml` configuration

- [ ] Test cases:
  - `tests/test-security.php` - Security class methods
  - `tests/test-messages.php` - CPT & message creation
  - `tests/test-rest-api.php` - API endpoints
  - `tests/test-settings.php` - Settings management

- [ ] Przykładowe testy:
  ```php
  public function test_validate_email_success() { }
  public function test_validate_email_failure() { }
  public function test_rate_limit_blocking() { }
  public function test_create_message() { }
  public function test_rest_api_send_message() { }
  ```

### 6.2 Integration Tests
**Czas**: 1 dzień  
**Priorytet**: 🟡 ŚREDNI

- [ ] Test całych flow'ów:
  - Wysłanie wiadomości → zapis w DB → email
  - Rate limiting dla tego samego IP
  - reCAPTCHA flow

- [ ] WordPress integration:
  - CPT registration
  - Hooks firing
  - Meta data saving

### 6.3 Browser/Frontend Tests
**Czas**: 2 dni  
**Priorytet**: 🟡 ŚREDNI

- [ ] Manual testing checklist:
  - [ ] Widget opens/closes smoothly
  - [ ] Form validation works
  - [ ] AJAX submission succeeds
  - [ ] Success/error messages display
  - [ ] Responsive on mobile/tablet/desktop
  - [ ] reCAPTCHA badge visible

- [ ] Opcjonalnie: Cypress/Playwright tests
  ```javascript
  describe('iHumbak Widget', () => {
    it('should open and close widget', () => { })
    it('should validate email', () => { })
    it('should submit message via AJAX', () => { })
  })
  ```

### 6.4 Security Audit
**Czas**: 1 dzień  
**Priorytet**: 🔴 KRYTYCZNY

- [ ] Checklist:
  - [ ] Nonce verification na wszystkich endpoints
  - [ ] Sanitization (sanitize_*, wp_kses_*)
  - [ ] Escaping (esc_html, esc_attr, esc_url)
  - [ ] Capability checks (`current_user_can()`)
  - [ ] SQL injection protection (use wpdb prepared statements)
  - [ ] XSS protection
  - [ ] CSRF token validation
  - [ ] Rate limiting active
  - [ ] reCAPTCHA properly configured
  - [ ] No hardcoded secrets/keys

- [ ] Tools:
  - WPCS (WordPress Coding Standards)
  - Manual code review

### 6.5 Documentation & README
**Czas**: 1 dzień  
**Priorytet**: 🟠 WYSOKI

**Plik**: `README.md`

- [ ] Sekcje:
  - Description
  - Features
  - Requirements
  - Installation
  - Configuration
  - Usage
  - Screenshots (opcjonalnie)
  - Troubleshooting
  - Contributing
  - License

- [ ] Dokumentacja kodu:
  - Docblocks na wszystkich metodach
  - Param descriptions
  - Return type descriptions

---

## 📅 Timeline Podsumowanie

| Faza | Opis | Dni | Koniec |
|------|------|-----|--------|
| 1 | Strukturyzacja | 3 | Dzień 3 |
| 2 | Backend | 8-10 | Dzień 13 |
| 3 | Frontend | 6-8 | Dzień 21 |
| 4 | Admin Panel | 5-7 | Dzień 28 |
| 5 | CI/CD & Deploy | 3-4 | Dzień 32 |
| 6 | Testing & QA | 5-7 | Dzień 39 |
| **RAZEM** | | **30-39 dni** | |

**Szacunek**: 4-5 tygodni przy pracy full-time

---

## 🛠️ Zmienne Środowiskowe & Konfiguracja

### Setup Lokalny (Development)

**`.env` (nie commitować!)**
```
WORDPRESS_DB_HOST=localhost
WORDPRESS_DB_NAME=wp_ihumbak
WORDPRESS_DB_USER=root
WORDPRESS_DB_PASSWORD=root

RECAPTCHA_SITE_KEY=your_site_key_here
RECAPTCHA_SECRET_KEY=your_secret_key_here

PLUGIN_VERSION=1.0.0
PLUGIN_ADMIN_EMAIL=admin@localhost
```

### GitHub Secrets (Do ustawienia)

W `Settings` → `Secrets and variables` → `Actions`:

```
RECAPTCHA_SECRET_KEY_PROD=xxx
RECAPTCHA_SITE_KEY_PROD=xxx
```

Używane w workflows do deployment.

---

## 📋 Definicje Done

### Każda faza to "done" kiedy:

1. ✅ Kod napisany i zacommitowany
2. ✅ Testy przechodzą (PHPUnit + linting)
3. ✅ Pull Request utworzony i zareviewed
4. ✅ Dokumentacja zaktualizowana
5. ✅ Brak console errors/warnings
6. ✅ Security checklist passed

---

## 🚀 Deployment Process

### Automatyczny Release (via GitHub Actions)

1. Developer tworzy tag: `git tag -a v1.0.0 -m "Version 1.0.0"`
2. Push tag: `git push origin v1.0.0`
3. GitHub Actions:
   - Buduje CSS (TailwindCSS)
   - Kompiluje assets
   - Tworzy ZIP release
   - Publikuje GitHub Release
   - Dołącza ZIP do release
4. ✅ Release gotowy do pobrania/instalacji

### Ręczny Test Release

```bash
# Symuluj GitHub Actions lokalnie
npm run build
mkdir -p release/ihumbak-chat
cp -r includes/ admin/ public/ assets/ languages/ ihumbak-chat.php README.md release/ihumbak-chat/
cd release && zip -r ihumbak-chat-v1.0.0.zip ihumbak-chat/
```

---

## 📚 Dodatkowe Zasoby

- [WordPress Plugin Development](https://developer.wordpress.org/plugins/)
- [TailwindCSS Docs](https://tailwindcss.com/)
- [GitHub Actions Docs](https://docs.github.com/en/actions)
- [WordPress REST API](https://developer.wordpress.org/rest-api/)
- [reCAPTCHA v3 Docs](https://developers.google.com/recaptcha/docs/v3)

---

## Koniec Planu Prac

**Status**: ✅ Gotowy do realizacji  
**Ostatnia aktualizacja**: 14 listopada 2025  
**Wersja**: 1.0
