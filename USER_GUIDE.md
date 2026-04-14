# Panth Performance Optimizer — User Guide

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

### Script Optimization

| Setting | Default | Description |
|---|---|---|
| **Defer Third-Party Scripts** | Yes | Automatically adds the `defer` attribute to third-party `<script>` tags to reduce main-thread blocking. |
| **Excluded Domains** | (empty) | One domain per line. Scripts loaded from these domains will not be deferred. Useful for payment gateways or scripts that must execute immediately. |

### Font Optimization

| Setting | Default | Description |
|---|---|---|
| **Add font-display: swap** | Yes | Injects a global CSS rule that forces `font-display: swap` on all `@font-face` declarations, preventing Flash of Invisible Text (FOIT). |

### Layout Stability (CLS Prevention)

| Setting | Default | Description |
|---|---|---|
| **Add x-cloak Style** | Yes | Injects `[x-cloak]{display:none!important}` into the page head. This prevents Alpine.js components from flashing unstyled content before Alpine initializes. Essential for Hyva storefronts. |
| **Set Image Dimensions** | Yes | Runs a client-side script that adds `width` and `height` attributes to images that are missing them, reducing Cumulative Layout Shift (CLS). |

### Iframe Lazy Loading

| Setting | Default | Description |
|---|---|---|
| **Enable Iframe Lazy Loading** | Yes | Defers loading of iframes that are below the viewport fold using IntersectionObserver. The iframe `src` is replaced with `data-lazy-src` and restored when the iframe scrolls within 200px of the viewport. |

---

## 4. How each optimization works

### Script deferral

A MutationObserver watches the DOM for new `<script>` tags. Any script
whose `src` points to a domain other than your store's hostname (and is
not in the excluded list) gets `defer = true`. Existing third-party
scripts are also patched on `DOMContentLoaded`.

### font-display: swap

A `<style>` block is injected with `@font-face{font-display:swap!important}`.
This is a global override — all fonts on the page will show a fallback
font immediately while the custom font downloads, then swap in once
ready.

### x-cloak style

Alpine.js uses the `x-cloak` attribute to hide elements until Alpine
initializes. Without a matching CSS rule, those elements briefly flash
their raw template markup. This optimization injects that CSS rule
early in the page so it takes effect before Alpine runs.

### Image dimensions

After the page loads, a script finds all `<img>` elements without
explicit `width`/`height` attributes. For already-loaded images, it
reads `naturalWidth` / `naturalHeight` and sets the attributes
immediately. For images still loading, it attaches a `load` event
listener.

### Iframe lazy loading

On `DOMContentLoaded`, all `<iframe>` elements whose bounding rect
places them below the viewport are converted to lazy-load: their `src`
is moved to `data-lazy-src`, and an IntersectionObserver (with 200px
root margin) restores the `src` when the iframe nears the viewport.

---

## 5. Troubleshooting

| Symptom | Cause | Fix |
|---|---|---|
| No optimizations on the frontend | Module disabled or full-page cache stale | Check `Stores > Configuration > Panth Extensions > Performance Optimizer > General > Enable`. Flush cache with `bin/magento cache:flush`. |
| Third-party script breaks after deferral | The script requires synchronous execution | Add the script's domain to **Excluded Domains** |
| Fonts still flash invisible | Browser does not support the `font-display` CSS descriptor, or the font is loaded via JavaScript | Check browser compatibility; the optimization only works for CSS-loaded fonts |
| Images still cause CLS | Images load before the script runs (rare on fast connections) | The client-side dimension setter is best-effort; for critical above-the-fold images, add `width`/`height` directly in templates |

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
