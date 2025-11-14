# iHumbak Chat - Quick Admin Communication

A WordPress plugin that provides a floating chat widget for quick and easy communication between website visitors and administrators.

## 🚀 Features

- **Floating Chat Widget** - Non-intrusive bubble widget that expands into a contact form
- **Simple Contact Form** - Email and message fields with real-time validation
- **reCAPTCHA v3 Integration** - Spam protection using Google's invisible reCAPTCHA
- **Rate Limiting** - Configurable rate limiting to prevent spam
- **Admin Dashboard** - Manage messages in WordPress admin panel
- **Email Notifications** - Get notified when new messages arrive
- **Fully Customizable** - Configure widget position, color, and text
- **Responsive Design** - Works seamlessly on desktop, tablet, and mobile
- **Built with TailwindCSS** - Modern, clean design

## 📋 Requirements

- WordPress 5.9 or higher
- PHP 7.4 or higher
- MySQL 5.7 or higher

## 🔧 Installation

### From GitHub Release

1. Download the latest release ZIP file from the [Releases page](https://github.com/michalstaniecko/wp-ihumbak-chat/releases)
2. Log in to your WordPress admin panel
3. Navigate to **Plugins** → **Add New** → **Upload Plugin**
4. Choose the downloaded ZIP file and click **Install Now**
5. Click **Activate Plugin**

### Manual Installation

1. Clone or download this repository
2. Copy the `ihumbak-chat` folder to your WordPress `wp-content/plugins/` directory
3. Log in to your WordPress admin panel
4. Navigate to **Plugins** and activate **iHumbak Chat**

## ⚙️ Configuration

After activation:

1. Go to **iHumbak Chat** → **Settings** in your WordPress admin
2. Configure the following:
   - **Admin Email**: Where messages will be sent
   - **reCAPTCHA Keys**: Get your keys from [Google reCAPTCHA](https://www.google.com/recaptcha/admin)
   - **Widget Position**: Choose where the widget appears (bottom-right, bottom-left, etc.)
   - **Widget Color**: Customize the primary color
   - **Rate Limit**: Set maximum messages per hour per IP
3. Click **Save Changes**

## 🎨 Usage

### Frontend

The chat widget will automatically appear on all public pages of your website. Visitors can:

1. Click the chat bubble to open the form
2. Enter their email address
3. Type their message
4. Click **Send**

### Admin Panel

View and manage messages:

1. Go to **iHumbak Chat** → **Messages**
2. See all received messages with status indicators
3. Click on a message to view full details
4. Mark messages as read or delete them

## 🛠️ Development

### Prerequisites

- Node.js 18+ and npm
- Composer
- WordPress development environment

### Setup

1. Clone the repository:
   ```bash
   git clone https://github.com/michalstaniecko/wp-ihumbak-chat.git
   cd wp-ihumbak-chat
   ```

2. Install dependencies:
   ```bash
   npm install
   composer install
   ```

3. Build CSS:
   ```bash
   npm run build
   ```

### Development Scripts

- `npm run dev` - Watch mode for CSS development
- `npm run build` - Build production CSS
- `composer run phpcs` - Run PHP CodeSniffer
- `composer run phpcbf` - Auto-fix PHP code style
- `composer run test` - Run PHPUnit tests

## 🤝 Contributing

We welcome contributions! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details on:

- Code of conduct
- Development workflow
- Coding standards
- Pull request process

## 📝 License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## 🐛 Bug Reports

Found a bug? Please open an issue on [GitHub Issues](https://github.com/michalstaniecko/wp-ihumbak-chat/issues) with:

- Clear description of the problem
- Steps to reproduce
- Expected vs actual behavior
- WordPress and PHP versions
- Screenshots if applicable

## 📚 Documentation

For detailed documentation, see the [docs](docs/) folder:

- [Specification](docs/SPECIFICATION.md) - Detailed technical specification
- [Work Plan](docs/WORKPLAN.md) - Development phases and timeline

## 🌟 Support

If you find this plugin helpful, please consider:

- ⭐ Starring the repository on GitHub
- 🐛 Reporting bugs
- 💡 Suggesting new features
- 🤝 Contributing code

## 👥 Authors

- **Michał Staniecko** - [@michalstaniecko](https://github.com/michalstaniecko)

## 📜 Changelog

### Version 1.0.0 (Coming Soon)

- Initial release
- Floating chat widget
- Admin dashboard
- Email notifications
- reCAPTCHA v3 integration
- Rate limiting
- Customizable settings

---

Made with ❤️ for WordPress
