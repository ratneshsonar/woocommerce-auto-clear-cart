# WooCommerce Auto Clear Cart

Automatically clears WooCommerce cart and session data at customizable intervals.

## 🔧 Features

- Auto-clears WooCommerce carts and sessions every:
  - 15 minutes
  - 30 minutes
  - 60 minutes
- Admin settings page under **WooCommerce → Auto Clear Cart**
- Option to enable/disable the feature
- Optional logging to a file in `wp-content/uploads/auto-clear-cart-log.txt`
- Automatically deactivates if WooCommerce is not active

## 📁 Installation

1. Download the plugin ZIP file.
2. Go to **Plugins → Add New** in your WordPress dashboard.
3. Click **Upload Plugin** and select the ZIP file.
4. Install and activate the plugin.

## ⚙️ Settings

Navigate to **WooCommerce → Auto Clear Cart** and configure:
- Enable/disable cart clearing
- Choose the clearing interval
- Toggle logging

## 🛡️ Security & Best Practices

- Input sanitization and output escaping implemented
- Secure logging with writable check
- Uses WordPress Settings API and WP-Cron
- Follows WordPress coding standards

## 📦 File Logging

If logging is enabled, logs are written to:
```
wp-content/uploads/auto-clear-cart-log.txt
```

## 🚫 Dependency Check

If WooCommerce is not installed or active, this plugin will:
- Automatically deactivate itself
- Display an admin notice

## 📃 License

This plugin is released under the GPLv2 license.

---

**Developed with ❤️ for WooCommerce users**
