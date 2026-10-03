# Panth Performance Optimizer - User Guide

This guide is for store administrators who want to configure and get the
most out of the Performance Optimizer extension.

---

## Table of contents

1. [Installation](#1-installation)
2. [Verifying the module is active](#2-verifying-the-module-is-active)
3. [Configuration](#3-configuration)
4. [How each optimization works](#4-how-each-optimization-works)
5. [Troubleshooting](#5-troubleshooting)
6. [CLI reference](#6-cli-reference)

---

## 1. Installation

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

> **Dependency:** This module requires `mage2kishan/module-core`
> (Panth_Core). Composer installs it automatically.

---

## 2. Verifying the module is active

```bash
bin/magento module:status Panth_PerformanceOptimizer
# Module is enabled
```

You should also see a **Performance Optimizer** entry under the
**Panth Infotech** admin sidebar menu.

---

## 3. Configuration

Navigate to **Stores > Configuration > Panth Extensions > Performance
Optimizer**.

### General Settings

| Setting | Default | Description |
|---|---|---|
| **Enable Performance Optimizer** | Yes | Master on/off toggle. When disabled, no optimizations are injected into the frontend. |

### Font Optimization

| Setting | Default | Description |
|---|---|---|
| **Add font-display: swap** | Yes | Adds `font-display: swap` on the server to `@font-face` rules in inline `<style>` blocks of the page body that do not set `font-display`. Fonts declared in external CSS files are not changed. |

### Layout Stability (CLS Prevention)

| Setting | Default | Description |
|---|---|---|
| **Add x-cloak Style** | Yes | Injects `[x-cloak]{display:none!important}` in the page head. This prevents Alpine.js components from flashing unstyled content before Alpine initializes. Essential for Hyva storefronts. |
| **Set Image Dimensions** | Yes | Adds `width` and `height` attributes on the server to images that are missing them, using the size of the image file under `pub/`, reducing Cumulative Layout Shift (CLS). |

### Iframe Lazy Loading

| Setting | Default | Description |
|---|---|---|
| **Enable Iframe Lazy Loading** | Yes | Adds `loading="lazy"` on the server to iframes that have no `loading` attribute, so the browser loads them when they are near the viewport. |

---

## 4. How each optimization works

### Script deferral

Not offered, and the former settings are no longer shown in the admin. Scripts in the page HTML run when the browser reaches them, so they cannot be deferred afterwards from JavaScript, and adding `defer` on the server could make inline scripts run before the library they use.

### font-display: swap

Inline `<style>` blocks in the page body are checked on the server. Each `@font-face` rule without `font-display` gets `font-display:swap;`. Fonts in external stylesheets need `font-display` set in the theme CSS.

### x-cloak style

Alpine.js uses the `x-cloak` attribute to hide elements until Alpine
initializes. Without a matching CSS rule, those elements briefly flash
their raw template markup. This optimization injects that CSS rule
in the page head, before any cloaked element is parsed.

### Image dimensions

Before the page is sent, `<img>` tags without `width`/`height` get the size of the image file read from `pub/`. If one of the two is already a number, the other is calculated from the aspect ratio. Images inside `<script>`, `<template>`, `<noscript>`, `<textarea>` and HTML comments are not changed.

### Iframe lazy loading

Before the page is sent, `<iframe>` tags without a `loading` attribute get `loading="lazy"`. The browser then loads them when they come near the viewport.

---

## 5. Troubleshooting

| Symptom | Cause | Fix |
|---|---|---|
| No optimizations on the frontend | Module disabled or full-page cache stale | Check `Stores > Configuration > Panth Extensions > Performance Optimizer > General > Enable`. Flush cache with `bin/magento cache:flush`. |
| Fonts still flash invisible | The font is declared in an external stylesheet or loaded via JavaScript | Set `font-display: swap` in the theme's `@font-face` rules |
| Images still cause CLS | The image is served from another host, is not under `pub/`, or its `src` is set by JavaScript | Add `width`/`height` directly in the template |

---

## 6. CLI reference

```bash
# Verify module status
bin/magento module:status Panth_PerformanceOptimizer

# Enable / disable
bin/magento module:enable  Panth_PerformanceOptimizer
bin/magento module:disable Panth_PerformanceOptimizer

# Flush cache after config changes
bin/magento cache:flush
```

---

## Support

For all questions, bug reports, or feature requests:

- **Email:** kishansavaliyakb@gmail.com
- **Website:** https://kishansavaliya.com
- **WhatsApp:** +91 84012 70422
