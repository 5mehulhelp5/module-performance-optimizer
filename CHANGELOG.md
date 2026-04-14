# Changelog

All notable changes to this extension are documented here. The format
is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/).

## [1.0.0] — Initial release

### Added
- **Defer Third-Party Scripts** — Automatically adds `defer` to
  third-party `<script>` tags (analytics, chat widgets, social embeds)
  to reduce main-thread blocking. Uses MutationObserver to catch
  dynamically injected scripts. Configurable domain exclusion list.
- **font-display: swap** — Injects a global CSS rule that forces
  `font-display: swap` on all `@font-face` declarations, preventing
  Flash of Invisible Text (FOIT).
- **Alpine.js CLS Prevention** — Injects
  `[x-cloak]{display:none!important}` to prevent Alpine.js elements
  from flashing unstyled content before initialization.
- **Auto Image Dimensions** — Client-side script that sets missing
  `width`/`height` attributes on images to reduce Cumulative Layout
  Shift (CLS).
- **Iframe Lazy Loading** — Defers loading of below-the-fold iframes
  via IntersectionObserver until they scroll into view. 200px root
  margin for smooth pre-loading.
- **Admin configuration** under `Stores > Configuration > Panth
  Extensions > Performance Optimizer` with per-feature toggles and
  per-store-view scope.
- **ACL resources** for granular admin permission control.

### Compatibility
- Magento Open Source / Commerce / Cloud 2.4.4 — 2.4.8
- PHP 8.1, 8.2, 8.3, 8.4
- Hyva and Luma themes

---

## Support

For all questions, bug reports, or feature requests:

- **Email:** kishansavaliyakb@gmail.com
- **Website:** https://kishansavaliya.com
- **WhatsApp:** +91 84012 70422
