# Specyfikacja Pluginu: iHumbak Chat - Quick Admin Communication

## 1. Przegląd Projektu

Plugin umożliwia użytkownikom witryny szybką komunikację z administratorem za pośrednictwem **floating widget** w formie chat bubble. Formularz jest prosty i intuicyjny, zawierający jedynie pole na email oraz treść wiadomości.

---

## 2. Wymagania Funkcjonalne

### 2.1 Interfejs Użytkownika (Frontend)

#### Floating Widget
- **Typ**: Chat bubble/floating action button
- **Pozycja**: Stała pozycja na ekranie (dolny-prawy róg, konfigurowalnie)
- **Wygląd**: 
  - Ikona czatu w stanie zamkniętym
  - Gładka animacja rozwinięcia
  - Responsywny na wszystkich rozdzielczościach

#### Formularz Wiadomości
- **Pola formularza**:
  - Email (wymagane, walidacja email)
  - Treść wiadomości (wymagane, min. 5 znaków)
  - reCAPTCHA (Google Invisible reCAPTCHA v3)

- **Funkcjonalność**:
  - Walidacja kliencka
  - Wysyłanie AJAX
  - Animacja ładowania
  - Komunikaty powodzenia/błędu
  - Opcja zamknięcia widgetu

- **Responsive Design**:
  - Desktop: Pełny rozmiar formularza
  - Tablet: Dostosowany rozmiar
  - Mobile: Pełna szerokość z paddingiem, zmieniona pozycja (bez "fixed" w bardzo wąskich ekranach)

### 2.2 Funkcjonalność Backendu

#### Wysyłanie Wiadomości
1. **Odbór danych z AJAX request**
   - POST endpoint: `/wp-json/ihumbak-chat/v1/send-message`
   - Walidacja nonce (bezpieczeństwo WP)
   - Dekodowanie JSON payload

2. **Przetwarzanie**
   - Sanityzacja danych wejściowych
   - Walidacja formatu email
   - Sprawdzenie reCAPTCHA
   - Ochrona przed spamem (np. rate limiting)

3. **Zapis w Bazie Danych**
   - Utworzenie custom post type `ihumbak_message`
   - Przechowywanie: email, treść, IP, timestamp, status
   - Opcjonalnie: user-agent, referer

4. **Wysłanie Emaila**
   - Email do administratora (adres z ustawień pluginu)
   - Szablon emaila z danymi wiadomości
   - Opcja potwierdzenia/przeczytania

---

## 3. Baza Danych

### 3.1 Custom Post Type
```
CPT: ihumbak_message
- Post Title: Skrót wiadomości lub "Wiadomość od [email]"
- Post Content: Pełna treść wiadomości
- Post Status: publish (archiwalne), pending (nowe)
- Post Type: ihumbak_message
```

### 3.2 Post Metadata
```
Meta keys:
- ihumbak_email: email nadawcy
- ihumbak_ip: adres IP klienta
- ihumbak_user_agent: user agent
- ihumbak_referer: URL strony, z której wysłano wiadomość
- ihumbak_read: boolean (czy admin przeczytał)
- ihumbak_responded: boolean (czy admin odpowiedział)
```

### 3.3 Opcje Pluginu (Settings)
```
Options:
- ihumbak_admin_email: email administratora (domyślnie admin@site)
- ihumbak_recaptcha_site_key: klucz publiczny reCAPTCHA
- ihumbak_recaptcha_secret_key: klucz tajny reCAPTCHA
- ihumbak_spam_limit: limit wiadomości na IP w ciągu godziny
- ihumbak_enable_notifications: włącz powiadomienia email
- ihumbak_widget_position: pozycja widgetu (bottom-right, bottom-left, top-right, top-left)
- ihumbak_widget_color: kolor główny widgetu
```

---

## 4. Bezpieczeństwo

### 4.1 Walidacja i Sanityzacja
- **Email**: `sanitize_email()` + walidacja regex WP
- **Treść**: `wp_kses_post()` lub `sanitize_textarea_field()`
- **IP/User-Agent**: `sanitize_text_field()`

### 4.2 Autoryzacja
- Weryfikacja nonce WP (`wp_verify_nonce()`)
- Brak wymogu zalogowania (dostępne dla wszystkich)

### 4.3 Spam Protection
- **Google reCAPTCHA v3**: Invisible integration
  - Wymagany token w każdym requestcie
  - Weryfikacja na backendzie
  - Ustawiana próg ufności (domyślnie 0.5)

- **Rate Limiting**:
  - Limit wiadomości na IP (domyślnie 5 wiadomości/godzina)
  - Przechowywanie ostatniego timestamp wysłania w transiencie WP
  - HTTP 429 Too Many Requests przy przekroczeniu

### 4.4 Ochrona Danych
- Szyfrowanie emaila w bazie (opcjonalnie)
- HTTPS wymagane dla reCAPTCHA
- Zasada prywatności: brak wysyłania IP do stron trzecich (poza reCAPTCHA)

---

## 5. Panel Administracyjny

### 5.1 Menu
- **Lokalizacja**: Nowy element menu "iHumbak Chat" w lewym pasku
- **Podstrony**:
  1. Wiadomości (lista)
  2. Ustawienia

### 5.2 Lista Wiadomości
- **Tabela** z kolumnami:
  - Email nadawcy
  - Skrót treści
  - Data wysłania
  - Status (przeczytano/brak)
  - Akcje (Czytaj, Odśmiecz, Usuń)

- **Filtry**:
  - Po statusie (przeczytane/nieprzeczytane)
  - Po dacie (ostatnie 7 dni, miesiąc, custom)
  - Po emailu (wyszukiwanie)

- **Bulk Actions**:
  - Oznacz jako przeczytane
  - Usuwanie zbiorcze

### 5.3 Podgląd Wiadomości
- **Modal/strona osobna** z pełną treścią
- Automatyczne oznaczenie jako przeczytane
- Możliwość odpowiedzi email (forward/reply template)
- Metadane: IP, timestamp, user-agent, referer

### 5.4 Ustawienia
- **Formularz konfiguracji**:
  - Email administratora
  - Klucze reCAPTCHA (Site Key, Secret Key)
  - Limit rate limitingu (wiadomości/godzina)
  - Pozycja widgetu (select)
  - Kolor główny widgetu (color picker)
  - Włączenie powiadomień email
  - Tekst welcome message (customizable)

- **Przycisk testowania reCAPTCHA**

---

## 6. Frontend - Wygląd (TailwindCSS)

### 6.1 Chat Bubble (Icon)
```
- Kształt: koło/zaokrąglony kwadrat
- Rozmiar: 60px x 60px (desktop), 50px x 50px (mobile)
- Kolor: Konfigurowalny (domyślnie #007bff - niebieski)
- Ikona: SVG chat/message icon
- Cień: Delikatny drop shadow
- Animacja: pulse/bounce przy załadowaniu strony
```

### 6.2 Formularz (Expanded State)
```
- Szerokość: 350px (desktop), 300px (tablet), ~100vw - 20px (mobile < 480px)
- Wysokość: ~500px (responsive height)
- Pozycja: Stała, dolny-prawy róg (bottom: 90px, right: 20px)
- Tło: Biały box z zaokrąglonymi rogami (border-radius-lg)
- Cień: drop-shadow-lg
- Animacja: fade-in + slide-up 300ms
```

### 6.3 Elementy Formularza
- **Header**: 
  - Tytuł "Get in Touch" (customizable)
  - Przycisk zamknięcia (X)
  - Tło: Kolor główny widgetu

- **Body**:
  - Pole email: placeholder "your@email.com", validacja real-time
  - Pole treści: textarea, placeholder "Your message...", counter znaków
  - reCAPTCHA badge (dół)

- **Footer**:
  - Przycisk "Send": bg-primary, hover state, loading state (spinner)
  - Stan disabled podczas wysyłania
  - Tekst "Sending..." podczas żądania

### 6.4 Komunikaty
- **Sukces**: Zielony toast/banner, "Message sent successfully! 🎉"
- **Błąd**: Czerwony toast/banner, "Error: Please try again later"
- **Validation**: Żółty komunikat pod polem z błędem
- **Rate limit**: Ostrzeżenie: "Too many messages. Please try again later."

### 6.5 Responsive Breakpoints
- **Desktop (1024px+)**: Pełny formularz, pozycja fixed
- **Tablet (640px-1023px)**: Zmniejszona szerokość, dostosowana pozycja
- **Mobile (< 640px)**: Formularz może zajmować pełną szerokość, wyśrodkowany

---

## 7. Struktura Pluginu

```
wp-ihumbak-chat/
├── docs/
│   └── SPECIFICATION.md
├── includes/
│   ├── class-ihumbak-chat.php (main plugin class)
│   ├── class-ihumbak-messages.php (CPT registration)
│   ├── class-ihumbak-settings.php (admin settings)
│   ├── class-ihumbak-rest-api.php (REST endpoints)
│   ├── class-ihumbak-security.php (validation, sanitization)
│   └── class-ihumbak-email.php (email notifications)
├── admin/
│   ├── pages/
│   │   ├── messages-list.php
│   │   ├── message-view.php
│   │   └── settings.php
│   └── css/
│       └── admin-style.css
├── public/
│   ├── js/
│   │   └── ihumbak-widget.js
│   ├── css/
│   │   └── ihumbak-widget.css (TailwindCSS)
│   └── templates/
│       └── widget.html
├── assets/
│   └── icons/
│       └── chat-icon.svg
├── languages/
│   └── ihumbak-chat.pot (i18n)
├── ihumbak-chat.php (main plugin file)
└── README.md
```

---

## 8. Integracje Zewnętrzne

### 8.1 Google reCAPTCHA
- **Wersja**: v3
- **Endpoint weryfikacji**: `https://www.google.com/recaptcha/api/siteverify`
- **Timeout**: 5 sekund
- **Error handling**: Fallback na manualną walidację

---

## 9. API Endpoints

### 9.1 Send Message
```
POST /wp-json/ihumbak-chat/v1/send-message
Headers:
  - Content-Type: application/json
  - X-WP-Nonce: [nonce_value]

Request Body:
{
  "email": "user@example.com",
  "message": "Hello admin!",
  "recaptcha_token": "[g-recaptcha-response]"
}

Response 200:
{
  "success": true,
  "message": "Message sent successfully"
}

Response 400:
{
  "success": false,
  "message": "Validation error"
}

Response 429:
{
  "success": false,
  "message": "Too many requests"
}
```

---

## 10. Instalacja i Aktywacja

### 10.1 Wymagania
- WordPress 5.9+
- PHP 7.4+
- MySQL 5.7+

### 10.2 Installer Hook
- Crear tabela custom (jeśli potrzebna, opcjonalnie dla rate limiting)
- Zarejestrowanie CPT
- Opcjonalne: import domyślnych ustawień

### 10.3 Deinstaller Hook
- Usunięcie CPT (bezpiecznie, bez usuwania postów)
- Czyszczenie options pluginu

---

## 11. Instrukcje Konfiguracji

1. Zainstaluj i aktywuj plugin
2. Przejdź do "iHumbak Chat" → "Settings"
3. Wprowadź email administratora
4. Uaktualnij klucze reCAPTCHA (z console.cloud.google.com)
5. Skonfiguruj dodatkowe opcje (pozycja, kolor, limit)
6. Zapisz zmiany
7. Widget powinien pojawić się na frontendu

---

## 12. Przyszłe Rozszerzenia (Out of Scope)

- [ ] Integracja z Slack
- [ ] Multiple language support (i18n pełne)
- [ ] Attachment support
- [ ] Konwersacja (threading)
- [ ] Admin replies
- [ ] Analytics/statistics

---

## Koniec Specyfikacji

**Ostatnia aktualizacja**: 14 listopada 2025  
**Wersja**: 1.0  
**Status**: Gotowa do implementacji
