# Panth Performance Optimizer

[![Magento 2.4.4 - 2.4.8](https://img.shields.io/badge/Magento-2.4.4%20--%202.4.8-orange)]()
[![PHP 8.1 - 8.4](https://img.shields.io/badge/PHP-8.1%20--%208.4-blue)]()
[![License: Proprietary](https://img.shields.io/badge/License-Proprietary-red)]()

**Actionable frontend performance optimizations** for Magento 2 stores
running Hyva or Luma themes. Improves Core Web Vitals scores by
deferring third-party scripts, preventing CLS, optimizing font loading,
and lazy-loading iframes — all configurable from the admin panel with
zero code changes required.

---

## Features

- **Defer Third-Party Scripts** — Automatically adds `defer` to
  third-party `<script>` tags (analytics, chat widgets, social embeds)
  to reduce main-thread blocking. Configurable domain exclusion list.
- **font-display: swap** — Injects a global `font-display: swap` rule
  to prevent Flash of Invisible Text (FOIT) while custom fonts load.
- **Alpine.js CLS Prevention** — Injects `[x-cloak]{display:none!important}`
  to prevent Alpine.js elements from flashing unstyled content before
  initialization. Essential for Hyva storefronts.
- **Auto Image Dimensions** — Client-side script that sets missing
  `width`/`height` attributes on images to reduce Cumulative Layout
  Shift (CLS).
- **Iframe Lazy Loading** — Defers loading of below-the-fold iframes
  (YouTube, Vimeo, Google Maps, etc.) via IntersectionObserver until
  they scroll into view.

---

## Installation

### Composer (recommended)

```bash
composer require mage2kishan/module-performance-optimizer
bin/magento module:enable Panth_PerformanceOptimizer
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

### Manual zip

1. Download the extension package zip
2. Extract to `app/code/Panth/PerformanceOptimizer`
3. Run the same `module:enable ... cache:flush` commands above

> **Dependency:** This module requires `mage2kishan/module-core` (Panth_Core).
> Composer installs it automatically.

---

## Requirements

| | Required |
|---|---|
| Magento | 2.4.4 — 2.4.8 (Open Source / Commerce / Cloud) |
| PHP | 8.1 / 8.2 / 8.3 / 8.4 |
| Panth_Core | ^1.0 |

---

## Configuration

Open **Stores > Configuration > Panth Extensions > Performance Optimizer**.

| Group | Setting | Default | Description |
|---|---|---|---|
| General | Enable Performance Optimizer | Yes | Master toggle for all optimizations |
| Script Optimization | Defer Third-Party Scripts | Yes | Auto-defer external scripts |
| Script Optimization | Excluded Domains | (empty) | Domains to skip (one per line) |
| Font Optimization | Add font-display: swap | Yes | Prevent FOIT |
| Layout Stability | Add x-cloak Style | Yes | Alpine.js CLS prevention |
| Layout Stability | Set Image Dimensions | Yes | Auto-set missing width/height |
| Iframe Lazy Loading | Enable Iframe Lazy Loading | Yes | Lazy-load below-the-fold iframes |

All settings are configurable per store view.

---

## Support

| Channel | Contact |
|---|---|
| Email | kishansavaliyakb@gmail.com |
| Website | https://kishansavaliya.com |
| WhatsApp | +91 84012 70422 |

---

## License

Proprietary — see `LICENSE.txt`. Distribution is restricted to the
Adobe Commerce Marketplace.

---

## About the developer

Built and maintained by **Kishan Savaliya** — https://kishansavaliya.com.
Builds high-quality, security-focused Magento 2 extensions and themes
for both Hyva and Luma storefronts.
