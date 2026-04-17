<!-- SEO Meta -->
<!--
  Title: Magento 2 Performance Optimizer — Defer Scripts, Font-Display Swap, CLS Prevention, Iframe Lazy Loading | Panth Infotech
  Description: Panth Performance Optimizer is a Magento 2 extension that boosts Core Web Vitals with admin-configurable defer for third-party scripts, font-display: swap, x-cloak CLS prevention, automatic image dimensions, and iframe lazy loading. Zero code changes. Compatible with Hyva and Luma, Magento 2.4.4 - 2.4.8, PHP 8.1 - 8.4.
  Keywords: magento 2 performance, magento 2 page speed, magento 2 core web vitals, magento 2 defer scripts, magento 2 font display swap, magento 2 cls prevention, magento 2 lazy loading iframes, magento 2 performance optimizer, hyva performance, luma performance
  Author: Kishan Savaliya (Panth Infotech)
  Canonical: https://github.com/mage2sk/module-performance-optimizer
-->

# Magento 2 Performance Optimizer — Core Web Vitals, Script Deferral, CLS Prevention | Panth Infotech

[![Magento 2.4.4 - 2.4.8](https://img.shields.io/badge/Magento-2.4.4%20--%202.4.8-orange?logo=magento&logoColor=white)](https://magento.com)
[![PHP 8.1 - 8.4](https://img.shields.io/badge/PHP-8.1%20--%208.4-blue?logo=php&logoColor=white)](https://php.net)
[![Hyva & Luma](https://img.shields.io/badge/Theme-Hyva%20%26%20Luma-8B5CF6)]()
[![Core Web Vitals](https://img.shields.io/badge/Core%20Web%20Vitals-Optimized-00C853)]()
[![Packagist](https://img.shields.io/badge/Packagist-mage2kishan%2Fmodule--performance--optimizer-orange?logo=packagist&logoColor=white)](https://packagist.org/packages/mage2kishan/module-performance-optimizer)
[![Upwork Top Rated Plus](https://img.shields.io/badge/Upwork-Top%20Rated%20Plus-14a800?logo=upwork&logoColor=white)](https://www.upwork.com/freelancers/~016dd1767321100e21)
[![Panth Infotech Agency](https://img.shields.io/badge/Agency-Panth%20Infotech-14a800?logo=upwork&logoColor=white)](https://www.upwork.com/agencies/1881421506131960778/)
[![Get a Quote](https://img.shields.io/badge/Get%20a%20Quote-Free%20Estimate-DC2626)](https://kishansavaliya.com/get-quote)

> **Panth Performance Optimizer** is an admin-configurable Magento 2 extension that improves Core Web Vitals and page speed out of the box — **defer third-party scripts**, **font-display: swap**, **x-cloak CLS prevention**, **automatic image dimensions**, and **iframe lazy loading**. Every optimization is a toggle in the admin — no theme edits, no template changes, no code changes required. Works with both **Hyva** and **Luma** storefronts across Magento 2.4.4 - 2.4.8.

Slow storefronts lose customers. Google's Core Web Vitals (LCP, CLS, INP) directly affect SEO rankings and conversion rate. Panth Performance Optimizer bundles the five highest-impact frontend optimizations into a single lightweight module so you can ship measurable speed gains without touching a single template file. Enable the module, flip the toggles that match your stack, flush cache — that's it.

---

## 🚀 Need Custom Magento 2 Performance Work?

> **Get a free Core Web Vitals audit + quote in 24 hours** — LCP, CLS, INP diagnostics, critical CSS, image pipeline, Hyva migration, caching strategy, and full page-speed tuning.

<p align="center">
  <a href="https://kishansavaliya.com/get-quote">
    <img src="https://img.shields.io/badge/Get%20a%20Free%20Quote%20%E2%86%92-Reply%20within%2024%20hours-DC2626?style=for-the-badge" alt="Get a Free Quote" />
  </a>
</p>

<table>
<tr>
<td width="50%" align="center">

### 🏆 Kishan Savaliya
**Top Rated Plus on Upwork**

[![Hire on Upwork](https://img.shields.io/badge/Hire%20on%20Upwork-Top%20Rated%20Plus-14a800?style=for-the-badge&logo=upwork&logoColor=white)](https://www.upwork.com/freelancers/~016dd1767321100e21)

100% Job Success • 10+ Years Magento Experience
Adobe Certified • Hyva Specialist

</td>
<td width="50%" align="center">

### 🏢 Panth Infotech Agency
**Magento Performance Team**

[![Visit Agency](https://img.shields.io/badge/Visit%20Agency-Panth%20Infotech-14a800?style=for-the-badge&logo=upwork&logoColor=white)](https://www.upwork.com/agencies/1881421506131960778/)

Core Web Vitals • Hyva Migration • Caching
Critical CSS • Image Pipeline • CDN Tuning

</td>
</tr>
</table>

**Visit our website:** [kishansavaliya.com](https://kishansavaliya.com) &nbsp;|&nbsp; **Get a quote:** [kishansavaliya.com/get-quote](https://kishansavaliya.com/get-quote)

---

## Table of Contents

- [Why Performance Optimizer](#why-performance-optimizer)
- [Key Features](#key-features)
- [How Each Optimization Works](#how-each-optimization-works)
- [Compatibility](#compatibility)
- [Installation](#installation)
- [Configuration](#configuration)
- [Measuring Impact](#measuring-impact)
- [Troubleshooting](#troubleshooting)
- [FAQ](#faq)
- [Support](#support)
- [About Panth Infotech](#about-panth-infotech)
- [Quick Links](#quick-links)

---

## Why Performance Optimizer

Most Magento 2 stores suffer from the same five frontend performance problems:

1. **Render-blocking third-party scripts** — chat widgets, analytics, pixels, A/B testing tools load synchronously and freeze the main thread
2. **Flash of Invisible Text (FOIT)** — custom fonts block text rendering for hundreds of milliseconds
3. **Alpine x-cloak flash** — on Hyva themes, components render their raw template before Alpine hydrates
4. **Images without width/height** — the single biggest cause of Cumulative Layout Shift (CLS) in Magento storefronts
5. **Heavy iframes above/below the fold** — YouTube embeds, maps, and social widgets download megabytes before the user needs them

Panth Performance Optimizer fixes all five with zero template edits. Every fix is a single admin toggle. Every toggle is safe to disable.

---

## Key Features

### Defer Third-Party Scripts
- Automatically adds `defer` to every `<script>` loaded from a domain other than your storefront
- **Excluded Domains** list lets you whitelist payment gateways or scripts that require synchronous execution
- Uses a `MutationObserver` to catch scripts added dynamically after page load
- Reduces Total Blocking Time (TBT) and improves INP

### Font-Display: swap
- Injects a global `@font-face { font-display: swap !important }` rule
- Eliminates FOIT across every custom font on every page
- Improves LCP by letting browsers paint text with fallback fonts immediately

### x-cloak CLS Prevention
- Injects `[x-cloak]{display:none!important}` into `<head>` early in the critical path
- Prevents raw Alpine.js template markup from flashing before hydration
- **Essential for Hyva storefronts** — a single toggle that every Hyva store should enable

### Automatic Image Dimensions
- Client-side script sets missing `width`/`height` attributes on `<img>` tags
- Reads `naturalWidth` / `naturalHeight` immediately for loaded images, attaches `load` listener for the rest
- Reduces CLS without requiring any template edits

### Iframe Lazy Loading
- Below-the-fold iframes have their `src` swapped to `data-lazy-src` on `DOMContentLoaded`
- `IntersectionObserver` with a 200px root margin restores the `src` just before the iframe enters the viewport
- Saves megabytes of YouTube, Maps, and social widget payload on initial page load

### Admin-Friendly
- **Every optimization is a checkbox** in `Stores → Configuration → Panth Extensions → Performance Optimizer`
- No theme edits, no template overrides, no layout XML required
- Safe to toggle individually — disable any single optimization without affecting the others
- Fully translatable via standard Magento `__()` strings

### MEQP Compliant
- Passes Adobe's Magento Extension Quality Program
- Zero severity-10 violations
- Uses only Magento framework classes — no third-party dependencies

---

## How Each Optimization Works

### 1. Script deferral
A `MutationObserver` watches the DOM for new `<script>` nodes. If the `src` attribute points to a domain that is not your storefront's hostname and is not in the **Excluded Domains** list, `defer = true` is applied. Existing third-party scripts are also patched on `DOMContentLoaded`.

### 2. font-display: swap
A `<style>` block is injected early in the page containing `@font-face { font-display: swap !important }`. Every CSS-loaded font on the page now shows a fallback immediately and swaps in the custom face once it finishes downloading.

### 3. x-cloak style
Alpine.js marks hidden-until-ready elements with `x-cloak`. Without a matching CSS rule those elements briefly flash their raw template markup. Performance Optimizer injects the CSS rule early enough that it takes effect before Alpine initializes.

### 4. Image dimensions
After the page loads, a script scans every `<img>` element without explicit `width`/`height` attributes. For already-loaded images it reads `naturalWidth` / `naturalHeight` and sets both attributes synchronously; for images still loading it attaches a `load` listener and fills them in when the image resolves.

### 5. Iframe lazy loading
On `DOMContentLoaded`, every `<iframe>` whose bounding rect sits below the viewport has its `src` moved to `data-lazy-src`. An `IntersectionObserver` with a 200px root margin restores the `src` as soon as the iframe comes within scroll range.

---

## Compatibility

| Requirement | Versions Supported |
|---|---|
| Magento Open Source | 2.4.4, 2.4.5, 2.4.6, 2.4.7, 2.4.8 |
| Adobe Commerce | 2.4.4, 2.4.5, 2.4.6, 2.4.7, 2.4.8 |
| Adobe Commerce Cloud | 2.4.4 — 2.4.8 |
| PHP | 8.1.x, 8.2.x, 8.3.x, 8.4.x |
| Hyva Theme | 1.0+ (fully supported, recommended) |
| Luma Theme | Native support |
| Required Dependency | `mage2kishan/module-core` (free) |

Tested on:
- Magento 2.4.8-p4 with PHP 8.4 on Hyva 1.3
- Magento 2.4.7 with PHP 8.3 on Luma
- Magento 2.4.6 with PHP 8.2 on Hyva 1.2

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

1. Download the latest release ZIP from [Packagist](https://packagist.org/packages/mage2kishan/module-performance-optimizer) or the [Adobe Commerce Marketplace](https://commercemarketplace.adobe.com)
2. Extract to `app/code/Panth/PerformanceOptimizer/`
3. Ensure `Panth_Core` is also installed (required dependency)
4. Run the same commands as above starting from `bin/magento module:enable`

### Verify Installation

```bash
bin/magento module:status Panth_PerformanceOptimizer
# Expected output: Module is enabled
```

Then navigate to:
```
Admin → Stores → Configuration → Panth Extensions → Performance Optimizer
```

---

## Configuration

All settings live at **Stores → Configuration → Panth Extensions → Performance Optimizer**.

### General Settings

| Setting | Default | Description |
|---|---|---|
| Enable Performance Optimizer | Yes | Master toggle. When disabled, no optimizations are injected into the frontend. |

### Script Optimization

| Setting | Default | Description |
|---|---|---|
| Defer Third-Party Scripts | Yes | Automatically adds the `defer` attribute to third-party `<script>` tags. |
| Excluded Domains | (empty) | One domain per line. Scripts loaded from these domains will not be deferred. Useful for payment gateways. |

### Font Optimization

| Setting | Default | Description |
|---|---|---|
| Add font-display: swap | Yes | Injects a global CSS rule that forces `font-display: swap` on all `@font-face` declarations. |

### Layout Stability (CLS Prevention)

| Setting | Default | Description |
|---|---|---|
| Add x-cloak Style | Yes | Injects `[x-cloak]{display:none!important}` into the page head. Essential for Hyva storefronts. |
| Set Image Dimensions | Yes | Client-side script adds `width` and `height` to images missing them. |

### Iframe Lazy Loading

| Setting | Default | Description |
|---|---|---|
| Enable Iframe Lazy Loading | Yes | Defers loading of below-the-fold iframes using `IntersectionObserver`. |

After changing any setting, flush the full-page cache:

```bash
bin/magento cache:flush
```

---

## Measuring Impact

We recommend benchmarking before and after enabling the module using:

- **Google PageSpeed Insights** — [pagespeed.web.dev](https://pagespeed.web.dev)
- **Chrome DevTools → Lighthouse** — run against the homepage, category, product, and cart pages
- **Chrome DevTools → Performance** panel — record a page load and check Total Blocking Time, LCP, CLS
- **Web Vitals Chrome extension** — real-time LCP/CLS/INP overlay while browsing

Typical gains on a default Luma storefront with several third-party scripts:
- **LCP**: 0.3–0.8s improvement (font-display + script deferral)
- **CLS**: 0.05–0.15 improvement (image dimensions + x-cloak)
- **TBT / INP**: 100–400ms improvement (script deferral + iframe lazy loading)

Actual numbers vary by theme, hosting, third-party stack, and CDN configuration.

---

## Troubleshooting

| Issue | Cause | Resolution |
|---|---|---|
| No optimizations visible on frontend | Module disabled or full-page cache stale | Enable in Core Settings, then run `bin/magento cache:flush` |
| Third-party script stops working after deferral | Script requires synchronous execution | Add the script's domain to **Excluded Domains** |
| Fonts still flash invisible | Font loaded via JavaScript, not CSS | The `font-display` descriptor only applies to CSS-loaded fonts |
| Images still cause CLS above the fold | Images load before the client-side script runs | For critical above-the-fold images, add `width`/`height` directly in templates |
| Iframe never loads | Iframe was marked lazy but `IntersectionObserver` never fires | Verify the iframe is actually scrollable into view; disable iframe lazy loading if your layout keeps iframes in a hidden container |
| Admin section missing | ACL not refreshed or `Panth_Core` not installed | Install `mage2kishan/module-core`, log out and back in to admin |

---

## FAQ

### Do I need to edit any templates?

No. Every optimization is admin-configurable and injected automatically. This is the whole point of the module — ship measurable speed gains without code changes.

### Will this break my payment gateway or chat widget?

No, as long as you add the domain to the **Excluded Domains** list. Scripts from excluded domains are left untouched. Default behaviour is to defer all third-party scripts, which is safe for the overwhelming majority of use cases.

### Does this work on Hyva?

Yes, and Hyva is where the `x-cloak` optimization really shines. Every Hyva store should enable this module.

### Does this replace my CDN or caching?

No. Performance Optimizer complements CDN and full-page cache — it focuses on frontend render-path optimizations that CDN/cache cannot fix.

### Can I disable individual optimizations?

Yes. Every optimization is a separate toggle. Disabling one does not affect the others.

### Is this compatible with Google Analytics / GTM / Facebook Pixel?

Yes. These scripts tolerate `defer` gracefully. If you see any issue, add the domain (e.g. `googletagmanager.com`) to Excluded Domains.

### Does it support multi-store setups?

Yes. All settings respect Magento's scope hierarchy (default → website → store view).

### Will it impact SEO?

Positively. Core Web Vitals are a confirmed Google ranking factor. Improvements to LCP, CLS, and INP directly improve search rankings.

### Is the source code available?

Yes — on GitHub at [github.com/mage2sk/module-performance-optimizer](https://github.com/mage2sk/module-performance-optimizer).

---

## Support

| Channel | Contact |
|---|---|
| Email | kishansavaliyakb@gmail.com |
| Website | [kishansavaliya.com](https://kishansavaliya.com) |
| WhatsApp | +91 84012 70422 |
| GitHub Issues | [github.com/mage2sk/module-performance-optimizer/issues](https://github.com/mage2sk/module-performance-optimizer/issues) |
| Upwork (Top Rated Plus) | [Hire Kishan Savaliya](https://www.upwork.com/freelancers/~016dd1767321100e21) |
| Upwork Agency | [Panth Infotech](https://www.upwork.com/agencies/1881421506131960778/) |

Response time: 1-2 business days.

### 💼 Need Custom Magento Performance Work?

Looking for **Core Web Vitals consulting**, **critical CSS extraction**, **Hyva migration**, **image pipeline tuning**, or **full Lighthouse-100 projects**? Get a free quote in 24 hours:

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
  <a href="https://kishansavaliya.com">
    <img src="https://img.shields.io/badge/Visit%20Website-kishansavaliya.com-0D9488?style=for-the-badge" alt="Visit Website" />
  </a>
</p>

---

## License

Panth Performance Optimizer is distributed under a proprietary license — see `LICENSE.txt`.

---

## About Panth Infotech

Built and maintained by **Kishan Savaliya** — [kishansavaliya.com](https://kishansavaliya.com) — a **Top Rated Plus** Magento developer on Upwork with 10+ years of eCommerce performance experience.

**Panth Infotech** is a Magento 2 development agency specializing in high-performance storefronts, Hyva migrations, and Core Web Vitals projects. Our extension suite covers SEO, performance, checkout, product presentation, customer engagement, and store management — over 34 modules built to MEQP standards and tested across Magento 2.4.4 to 2.4.8.

Browse the full extension catalog on the [Adobe Commerce Marketplace](https://commercemarketplace.adobe.com) or [Packagist](https://packagist.org/packages/mage2kishan/).

---

## Quick Links

- 🌐 **Website:** [kishansavaliya.com](https://kishansavaliya.com)
- 💬 **Get a Quote:** [kishansavaliya.com/get-quote](https://kishansavaliya.com/get-quote)
- 👨‍💻 **Upwork Profile (Top Rated Plus):** [upwork.com/freelancers/~016dd1767321100e21](https://www.upwork.com/freelancers/~016dd1767321100e21)
- 🏢 **Upwork Agency:** [upwork.com/agencies/1881421506131960778](https://www.upwork.com/agencies/1881421506131960778/)
- 📦 **Packagist:** [packagist.org/packages/mage2kishan/module-performance-optimizer](https://packagist.org/packages/mage2kishan/module-performance-optimizer)
- 🐙 **GitHub:** [github.com/mage2sk/module-performance-optimizer](https://github.com/mage2sk/module-performance-optimizer)
- 🛒 **Adobe Marketplace:** [commercemarketplace.adobe.com](https://commercemarketplace.adobe.com)
- 📧 **Email:** kishansavaliyakb@gmail.com
- 📱 **WhatsApp:** +91 84012 70422

---

<p align="center">
  <strong>Ready to hit Lighthouse 90+ on your Magento 2 store?</strong><br/>
  <a href="https://kishansavaliya.com/get-quote">
    <img src="https://img.shields.io/badge/%F0%9F%9A%80%20Get%20Started%20%E2%86%92-Free%20Quote%20in%2024h-DC2626?style=for-the-badge" alt="Get Started" />
  </a>
</p>

---

**SEO Keywords:** magento 2 performance, magento 2 page speed, magento 2 core web vitals, magento 2 defer scripts, magento 2 defer third-party scripts, magento 2 font display swap, magento 2 cls prevention, magento 2 cumulative layout shift fix, magento 2 lazy loading iframes, magento 2 iframe lazy load, magento 2 image dimensions, magento 2 x-cloak hyva, magento 2 alpine cloak, magento 2 lighthouse optimization, magento 2 lcp optimization, magento 2 inp optimization, magento 2 tbt reduction, magento 2 render blocking scripts, magento 2 performance module, magento 2 speed optimization extension, hyva performance optimizer, luma performance optimizer, panth performance optimizer, magento 2 page speed insights, magento 2 seo core web vitals, magento 2.4.8 performance, php 8.4 magento performance, hire magento performance developer, top rated plus magento freelancer, kishan savaliya magento, panth infotech magento, mage2kishan, mage2sk, magento 2 hyva performance optimization, magento 2 luma performance tuning, magento 2 critical css, magento 2 frontend optimization, magento 2 lighthouse 100
