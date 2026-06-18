<!-- SEO Meta -->
<!--
  Title: Magento 2 Performance Optimizer: Script Deferral, Font-Display Swap, CLS Prevention, Iframe Lazy Loading | Panth Infotech
  Description: Panth Performance Optimizer is a Magento 2 extension that improves Core Web Vitals with admin-configurable defer for third-party scripts, font-display: swap, x-cloak CLS prevention, automatic image dimensions, and iframe lazy loading. Zero template edits. Works on Magento 2.4.4 to 2.4.8 and PHP 8.1 to 8.4. Built by Top Rated Plus Magento developer Kishan Savaliya.
  Keywords: magento 2 performance optimizer, magento 2 core web vitals, magento 2 defer scripts, magento 2 font display swap, magento 2 cls prevention, magento 2 lazy loading iframes, magento 2 page speed, hyva performance, luma performance, magento 2 lcp optimization
  Author: Kishan Savaliya (Panth Infotech)
  Canonical: https://kishansavaliya.com/magento-2-performance-optimizer.html
-->

# Magento 2 Performance Optimizer: Script Deferral, Font-Display Swap, CLS Prevention and Iframe Lazy Loading (Hyva + Luma)

[![Magento 2.4.4 - 2.4.8](https://img.shields.io/badge/Magento-2.4.4%20--%202.4.8-orange?logo=magento&logoColor=white)](https://magento.com)
[![PHP 8.1 - 8.4](https://img.shields.io/badge/PHP-8.1%20--%208.4-blue?logo=php&logoColor=white)](https://php.net)
[![Hyva + Luma](https://img.shields.io/badge/Themes-Hyva%20%2B%20Luma-14b8a6)](https://www.hyva.io)
[![Live Demo & Details](https://img.shields.io/badge/Live%20Demo%20%26%20Details-magento--2--performance--optimizer-0D9488?style=flat)](https://kishansavaliya.com/magento-2-performance-optimizer.html)
[![Packagist](https://img.shields.io/badge/Packagist-mage2kishan%2Fmodule--performance--optimizer-orange?logo=packagist&logoColor=white)](https://packagist.org/packages/mage2kishan/module-performance-optimizer)
[![Upwork Top Rated Plus](https://img.shields.io/badge/Upwork-Top%20Rated%20Plus-14a800?logo=upwork&logoColor=white)](https://www.upwork.com/freelancers/~016dd1767321100e21)
[![Website](https://img.shields.io/badge/Website-kishansavaliya.com-0D9488)](https://kishansavaliya.com)

> **Fix Core Web Vitals on your Magento 2 store without touching a single template.** Panth Performance Optimizer adds five admin-configurable frontend optimizations: third-party script deferral, font-display swap, Alpine.js CLS prevention, automatic image dimensions, and iframe lazy loading. Every setting is a checkbox. Works on both **Hyva** and **Luma** storefronts.

**Product page:** [kishansavaliya.com/magento-2-performance-optimizer.html](https://kishansavaliya.com/magento-2-performance-optimizer.html)

---

## Quick Answer

**What is Panth Performance Optimizer?** It is a Magento 2 extension that improves Core Web Vitals and page speed by injecting five frontend optimizations from admin configuration, with no theme edits or template changes needed.

**What does it add to my store?**

- **Script deferral** that adds `defer` to third-party scripts (analytics, chat widgets, pixels) to cut main-thread blocking.
- **font-display: swap** to stop custom fonts from blocking text rendering (fixes FOIT).
- **x-cloak CSS injection** to prevent Alpine.js elements from flashing raw markup before hydration (essential for Hyva).
- **Auto image dimensions** that add missing `width` and `height` attributes to reduce CLS.
- **Iframe lazy loading** that defers YouTube, Maps, and similar embeds until they scroll into view.

**Which themes are supported?** Both **Hyva** and **Luma**. The x-cloak optimization is especially useful for Hyva stores.

**What does it need?** Magento 2.4.4 to 2.4.8, PHP 8.1 to 8.4, and the free `mage2kishan/module-core` package.

---

## Need Custom Magento 2 Development?

> **Get a free quote for your project in 24 hours** for custom modules, Hyva themes, performance work, M1 to M2 migrations, and Adobe Commerce Cloud.

<p align="center">
  <a href="https://kishansavaliya.com/get-quote">
    <img src="https://img.shields.io/badge/Get%20a%20Free%20Quote%20%E2%86%92-Reply%20within%2024%20hours-DC2626?style=for-the-badge" alt="Get a Free Quote" />
  </a>
</p>

<table>
<tr>
<td width="50%" align="center">

### Kishan Savaliya
**Top Rated Plus on Upwork**

[![Hire on Upwork](https://img.shields.io/badge/Hire%20on%20Upwork-Top%20Rated%20Plus-14a800?style=for-the-badge&logo=upwork&logoColor=white)](https://www.upwork.com/freelancers/~016dd1767321100e21)

100% Job Success • 10+ Years Magento Experience
Adobe Certified • Hyva Specialist

</td>
<td width="50%" align="center">

### Panth Infotech Agency
**Magento Development Team**

[![Visit Agency](https://img.shields.io/badge/Visit%20Agency-Panth%20Infotech-14a800?style=for-the-badge&logo=upwork&logoColor=white)](https://www.upwork.com/agencies/1881421506131960778/)

Custom Modules • Theme Design • Migrations
Performance • SEO • Adobe Commerce Cloud

</td>
</tr>
</table>

**Visit our website:** [kishansavaliya.com](https://kishansavaliya.com) &nbsp;|&nbsp; **Get a quote:** [kishansavaliya.com/get-quote](https://kishansavaliya.com/get-quote)

---

## Table of Contents

- [Who Is It For](#who-is-it-for)
- [Key Features](#key-features)
- [Compatibility](#compatibility)
- [Installation](#installation)
- [Configuration](#configuration)
- [How It Works](#how-it-works)
- [FAQ](#faq)
- [Support](#support)
- [About Panth Infotech](#about-panth-infotech)
- [Quick Links](#quick-links)

---

## Who Is It For

- **Hyva storefronts** that need the x-cloak CSS fix applied early so Alpine.js components do not flash raw markup on load.
- **Stores with third-party scripts** (Google Tag Manager, live chat, Facebook Pixel, analytics) that are blocking the main thread.
- **Merchants aiming for better Core Web Vitals scores** who want LCP, CLS, and INP improvements without touching templates.
- **Stores with embedded videos and maps** where YouTube, Vimeo, or Google Maps iframes are adding large payloads on initial load.
- **Any Magento store on Luma or Hyva** where custom fonts are causing Flash of Invisible Text during page load.

---

## Key Features

### Defer Third-Party Scripts
- **Automatically adds `defer`** to every `<script>` tag loading from a domain other than your storefront.
- **Excluded Domains** field lets you whitelist payment gateways or scripts that need to run synchronously.
- Reduces Total Blocking Time (TBT) and improves INP without manual script changes.

### Font-Display Swap
- **Injects a global `font-display: swap` rule** so browsers show fallback text immediately instead of hiding it while custom fonts download.
- Eliminates Flash of Invisible Text (FOIT) across all CSS-loaded fonts on every page.
- Improves LCP by letting browsers paint text faster.

### x-cloak CLS Prevention
- **Injects `[x-cloak]{display:none!important}`** into the page head early in the critical path.
- Prevents Alpine.js template markup from flashing before hydration on Hyva storefronts.
- A single checkbox that every Hyva store should turn on.

### Auto Image Dimensions
- **Client-side script adds missing `width` and `height` attributes** to `<img>` elements that do not have them.
- Reads `naturalWidth` and `naturalHeight` for already-loaded images, attaches a `load` listener for images still loading.
- Reduces CLS without any template edits.

### Iframe Lazy Loading
- **Moves the `src` of below-the-fold iframes** to `data-lazy-src` on DOMContentLoaded.
- Uses `IntersectionObserver` with a 200px root margin to restore the `src` just before the iframe scrolls into view.
- Cuts initial page weight from YouTube, Google Maps, Vimeo, and social widgets.

### Admin-Friendly
- **Every optimization is its own checkbox** in Stores > Configuration > Panth Extensions > Performance Optimizer.
- No template edits, no layout XML changes, no code modifications needed.
- All settings respect Magento's scope hierarchy (default, website, store view).
- Fully translatable via standard Magento `__()` strings.

---

## Compatibility

| Requirement | Versions Supported |
|---|---|
| Magento Open Source | 2.4.4, 2.4.5, 2.4.6, 2.4.7, 2.4.8 |
| Adobe Commerce | 2.4.4, 2.4.5, 2.4.6, 2.4.7, 2.4.8 |
| Adobe Commerce Cloud | 2.4.4 to 2.4.8 |
| PHP | 8.1.x, 8.2.x, 8.3.x, 8.4.x |
| Hyva Theme | 1.0+ (native support, recommended) |
| Luma Theme | Native support |
| Required Dependency | `mage2kishan/module-core` (free) |

---

## Installation

### Composer Installation (Recommended)

```bash
composer require mage2kishan/module-performance-optimizer
bin/magento module:enable Panth_Core Panth_PerformanceOptimizer
bin/magento setup:upgrade
bin/magento setup:di:compile
bin/magento setup:static-content:deploy -f
bin/magento cache:flush
```

### Manual Installation via ZIP

1. Download the latest release from [Packagist](https://packagist.org/packages/mage2kishan/module-performance-optimizer) or from the [product page](https://kishansavaliya.com/magento-2-performance-optimizer.html).
2. Extract it to `app/code/Panth/PerformanceOptimizer/` in your Magento install.
3. Make sure `Panth_Core` is installed too (required dependency).
4. Run the commands above starting from `bin/magento module:enable`.

### Verify Installation

```bash
bin/magento module:status Panth_PerformanceOptimizer
# Expected: Module is enabled
```

After install, open:
```
Admin -> Stores -> Configuration -> Panth Extensions -> Performance Optimizer
```

---

## Configuration

Go to **Stores -> Configuration -> Panth Extensions -> Performance Optimizer**.

| Setting | Group | Default | Description |
|---|---|---|---|
| Enable Performance Optimizer | General Settings | Yes | Master toggle. When disabled, no optimizations are injected. |
| Defer Third-Party Scripts | Script Optimization | Yes | Adds `defer` to third-party `<script>` tags to reduce main-thread blocking. |
| Excluded Domains | Script Optimization | (empty) | One domain per line. Scripts from these domains will not be deferred. |
| Add font-display: swap | Font Optimization | Yes | Injects a global CSS rule that forces `font-display: swap` on all `@font-face` declarations. |
| Add x-cloak Style | Layout Stability (CLS Prevention) | Yes | Injects `[x-cloak]{display:none!important}` to prevent Alpine.js elements flashing on Hyva. |
| Set Image Dimensions | Layout Stability (CLS Prevention) | Yes | Adds missing `width` and `height` attributes to images to reduce CLS. |
| Enable Iframe Lazy Loading | Iframe Lazy Loading | Yes | Defers below-the-fold iframes using IntersectionObserver until they scroll into view. |

After changing any setting, flush the full-page cache:

```bash
bin/magento cache:flush
```

---

## How It Works

1. When a page loads, the module reads your configuration and injects only the optimizations you have enabled.
2. **Script deferral** uses a `MutationObserver` to catch both inline and dynamically injected third-party scripts and sets `defer = true`.
3. **Font swap** adds a `<style>` block early in the `<head>` with `@font-face { font-display: swap !important }`.
4. **x-cloak** injects `[x-cloak]{display:none!important}` before Alpine.js initializes so hidden-until-ready elements never flash.
5. **Image dimensions** runs after page load, reads `naturalWidth` and `naturalHeight`, and sets the attributes on any `<img>` that was missing them.
6. **Iframe lazy loading** moves below-fold iframe `src` values to `data-lazy-src` on DOMContentLoaded, then an `IntersectionObserver` with 200px margin restores them as they approach the viewport.

Each optimization is independent. You can enable or disable any one of them without affecting the others.

---

## FAQ

### Do I need to edit any templates?

No. Every optimization is admin-configurable and injected automatically. Enable the module, set your checkboxes, flush cache, and the changes take effect on your live store.

### Will deferring scripts break my payment gateway or chat widget?

No, as long as you add the domain to the **Excluded Domains** field. Scripts from excluded domains are left untouched. For the vast majority of analytics, pixels, and chat tools, `defer` is safe with no side effects.

### Does this work on Hyva?

Yes. Hyva is where the x-cloak optimization matters most. Every Hyva store should enable it. The module also works on Luma with no extra setup.

### Can I disable individual optimizations?

Yes. Each of the five optimizations has its own toggle. You can combine them any way you like without affecting the others.

### Does this replace my CDN or full-page cache?

No. Performance Optimizer focuses on frontend render-path fixes that CDN and FPC cannot address. Use it alongside your caching setup.

### Is it compatible with Google Analytics, GTM, and Facebook Pixel?

Yes. These scripts handle `defer` correctly. If you ever see an issue with a specific script, add its domain (for example `googletagmanager.com`) to the Excluded Domains list.

### Does it support multi-store setups?

Yes. All settings respect Magento's scope hierarchy (default, website, store view), so you can configure optimizations differently per store.

### Will it help my SEO rankings?

Core Web Vitals (LCP, CLS, INP) are confirmed Google ranking signals. Improvements to these metrics can positively affect your organic search visibility.

### Does Panth Performance Optimizer need Panth Core?

Yes. `mage2kishan/module-core` is a free, required dependency that Composer installs for you automatically.

---

## Support

| Channel | Contact |
|---|---|
| Product Page | [kishansavaliya.com/magento-2-performance-optimizer.html](https://kishansavaliya.com/magento-2-performance-optimizer.html) |
| Email | kishansavaliyakb@gmail.com |
| Website | [kishansavaliya.com](https://kishansavaliya.com) |
| WhatsApp | +91 84012 70422 |
| GitHub Issues | [github.com/mage2sk/module-performance-optimizer/issues](https://github.com/mage2sk/module-performance-optimizer/issues) |
| Upwork (Top Rated Plus) | [Hire Kishan Savaliya](https://www.upwork.com/freelancers/~016dd1767321100e21) |
| Upwork Agency | [Panth Infotech](https://www.upwork.com/agencies/1881421506131960778/) |

Response time: 1-2 business days.

### Need Custom Magento Development?

Looking for **custom Magento module development**, **Hyva theme work**, **Core Web Vitals consulting**, or **performance tuning**? Get a free quote in 24 hours:

<p align="center">
  <a href="https://kishansavaliya.com/get-quote">
    <img src="https://img.shields.io/badge/%F0%9F%92%AC%20Get%20a%20Free%20Quote-kishansavaliya.com%2Fget--quote-DC2626?style=for-the-badge" alt="Get a Free Quote" />
  </a>
</p>

<p align="center">
  <a href="https://www.upwork.com/freelancers/~016dd1767321100e21">
    <img src="https://img.shields.io/badge/Hire%20Kishan-Top%20Rated%20Plus-14a800?style=for-the-badge&logo=upwork&logoColor=white" alt="Hire on Upwork" />
  </a>
  &nbsp;&nbsp;
  <a href="https://www.upwork.com/agencies/1881421506131960778/">
    <img src="https://img.shields.io/badge/Visit-Panth%20Infotech%20Agency-14a800?style=for-the-badge&logo=upwork&logoColor=white" alt="Visit Agency" />
  </a>
  &nbsp;&nbsp;
  <a href="https://kishansavaliya.com/magento-2-performance-optimizer.html">
    <img src="https://img.shields.io/badge/View%20Product%20Page-magento--2--performance--optimizer-0D9488?style=for-the-badge" alt="View Product Page" />
  </a>
</p>

---

## About Panth Infotech

Built and maintained by **Kishan Savaliya** ([kishansavaliya.com](https://kishansavaliya.com)), a **Top Rated Plus** Magento developer on Upwork with 10+ years of eCommerce experience.

**Panth Infotech** is a Magento 2 development agency that builds high quality, security focused extensions and themes for both Hyva and Luma storefronts. The extension suite covers SEO, performance, checkout, product presentation, customer engagement, and store management, with each module built to MEQP standards and tested across Magento 2.4.4 to 2.4.8.

Browse the full extension catalog on our [Magento extensions page](https://kishansavaliya.com/magento-extensions.html) or on [Packagist](https://packagist.org/packages/mage2kishan/).

---

## Quick Links

| Resource | Link |
|---|---|
| **Product Page** | [magento-2-performance-optimizer.html](https://kishansavaliya.com/magento-2-performance-optimizer.html) |
| **Packagist** | [mage2kishan/module-performance-optimizer](https://packagist.org/packages/mage2kishan/module-performance-optimizer) |
| **GitHub** | [mage2sk/module-performance-optimizer](https://github.com/mage2sk/module-performance-optimizer) |
| **Website** | [kishansavaliya.com](https://kishansavaliya.com) |
| **Free Quote** | [kishansavaliya.com/get-quote](https://kishansavaliya.com/get-quote) |
| **Upwork (Top Rated Plus)** | [Hire Kishan Savaliya](https://www.upwork.com/freelancers/~016dd1767321100e21) |
| **Upwork Agency** | [Panth Infotech](https://www.upwork.com/agencies/1881421506131960778/) |
| **Email** | kishansavaliyakb@gmail.com |
| **WhatsApp** | +91 84012 70422 |

---

<p align="center">
  <strong>Ready to improve your Magento 2 Core Web Vitals?</strong><br/>
  <a href="https://kishansavaliya.com/magento-2-performance-optimizer.html">
    <img src="https://img.shields.io/badge/%F0%9F%9A%80%20See%20Performance%20Optimizer%20%E2%86%92-Product%20Page%20%26%20Details-DC2626?style=for-the-badge" alt="See Performance Optimizer" />
  </a>
</p>

---

**SEO Keywords:** magento 2 performance optimizer, magento 2 core web vitals, magento 2 defer scripts, magento 2 defer third-party scripts, magento 2 font display swap, magento 2 cls prevention, magento 2 cumulative layout shift fix, magento 2 lazy loading iframes, magento 2 iframe lazy load, magento 2 image dimensions, magento 2 x-cloak hyva, magento 2 alpine cloak, magento 2 lcp optimization, magento 2 inp optimization, magento 2 tbt reduction, magento 2 render blocking scripts, magento 2 performance module, magento 2 speed optimization extension, magento 2 page speed, hyva performance optimizer, luma performance optimizer, panth performance optimizer, magento 2 page speed insights, magento 2 seo core web vitals, magento 2.4.8 performance, php 8.4 magento performance, hire magento performance developer, top rated plus magento freelancer, kishan savaliya magento, panth infotech magento, mage2kishan, mage2sk, magento 2 hyva performance optimization, magento 2 luma performance tuning, magento 2 frontend optimization, magento 2 lighthouse optimization
