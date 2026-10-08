# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

ClProperty — a RadiusTheme real-estate WordPress theme (PHP 7.4+, namespace `radiustheme\ClProperty`) built on top of the **Classified Listing** plugin family. There is no build step, linter, or test suite: CSS and JS in `assets/` are hand-edited and served as-is.

## Commands

- `npm run zip` — packages the theme into `dist/clproperty.<Version>.zip` (version read from `style.css`; excludes `.git`, `scripts/`, `dist/`, `CLAUDE.md`, etc.).
- Local site: http://clproperty.local/ (Local by Flywheel). `~/Local Sites/clproperty/app/public/wp-content/themes/clproperty` is a symlink to this repo, so edits are live immediately. The sibling plugins are symlinked the same way from `../clproperty-core` and `~/GithubProjects/classified-listing/*`.

## Related repos (separate git repos, may be on different branches)

- `../clproperty-core` — companion plugin, see below.
- `classified-listing`, `-pro`, `-store`, `-toolkits`, `rtcl-agent` — the listing plugins this theme depends on. Theme code guards on `class_exists( 'Rtcl' )` / `class_exists( 'WooCommerce' )`; keep cross-plugin calls guarded the same way.
- Required/bundled plugins and their expected versions are declared in `inc/tgm-config.php`.

## Core plugin (`../clproperty-core`)

Separate git repo, versioned in step with the theme (`Version:` header in `clproperty-core.php`; bundled via `inc/tgm-config.php`). It depends on theme classes (`radiustheme\ClProperty\Helper`, `RDTheme`, `Listing_Functions`), so it only works with this theme active.

- **Bootstrap:** `clproperty-core.php` loads modules on `after_setup_theme`: `inc/` (hooks, shortcodes, social share, Yelp review when enabled), `widgets/` (classic WP widgets — only if `RT_FRAMEWORK_VERSION` is defined), `elementor/init.php` (only if Elementor loaded). The demo importer loads on `plugins_loaded`.
- **Elementor widgets:** registered in `elementor/init.php` as `dirname => ClassName`; listing widgets (`rt-properties`, `listing-search`, `agent`, …) are only registered when Classified Listing is active. Each widget is `elementor/<dir>/class.php` (controls + `render()` picking a `view-N.php` by layout) extending `Custom_Widget_Base` (`elementor/base.php`). `rt_template()` lets the theme override any view at `elementor-custom/<dir>/view-N.php` (child theme first). The Elementor widget name (e.g. `rt-properties-type-tab`, used in page markup as `elementor-widget-<name>`) differs from the directory name — grep `get_name`/the class to map one to the other.
- **Widget styling/JS is in the theme**, not the plugin: `clproperty/assets/css/styles.css` (+ `css-rtl/`) and `clproperty/assets/js/main.js`. The plugin's own `assets/` only holds pannellum (360° view) and TweenMax.
- **Listing card meta in widgets** (`rt-properties/view-*.php`) comes from `RtclPro\Controllers\Hooks\TemplateHooks::loop_item_listable_fields()`. With the Form Builder active, that prints only fields of the listing's form (`_rtcl_form_id` post meta → `wp_rtcl_forms` row) that are flagged for archive view. Empty meta usually means the listing's form ID doesn't exist, not a template bug.
- **Demo importer** (`demo-importer/`, namespace `RT\CLPropertyCore\DemoImporter`, runs on the Easy Demo Importer plugin): `config.php` lists demo variants, required plugins and extra files; `src/Handlers/RtclHandler.php` truncates `wp_rtcl_forms` and re-inserts forms from `demo-files/rtcl-form.json`, then imports `rtcl-options.json`, pages and listing types. Listings in `demo-files/demo-content/content.xml` must reference form IDs present in `rtcl-form.json` (currently only ID 5). `config.php` points at `demo-files/demo-content.zip`, which is not in the repo — only the unzipped `demo-content/` is.

## Architecture

- **Bootstrap:** `functions.php` → `inc/constants.php`, `inc/helper.php`, `inc/includes.php`. `includes.php` loads every other `inc/` module and conditionally loads `classified-listing/custom/functions.php` + `shortcode.php` when Rtcl is active. The licence updater (`inc/lib/updater`) is skipped when `RT_DEBUG` is defined.
- **Child-theme overridable loading:** always load files via `Helper::requires()`, `Helper::get_css()/get_js()/get_img()`, `Helper::get_template_part()` — they check the child theme first.
- **Theme options:** Customizer settings in `inc/customizer/settings/*.php` with defaults in `customizer-default-data.php`; merged values are exposed as `RDTheme::$options` (`inc/rdtheme.php`). Per-page layout state (header/footer style, banner, padding…) is also resolved into `RDTheme` static props in `inc/layout-settings.php`.
- **Dynamic CSS:** colours/typography from options are emitted inline by `inc/dynamic-styles/common.php` and `frontend.php` (hooked from `inc/scripts.php`).
- **Classified Listing template overrides:** `classified-listing/` mirrors the plugin's template paths (the plugin picks these up automatically). Theme-only listing templates live in `classified-listing/custom/` and are loaded with `Helper::get_custom_listing_template()`; store equivalents use `classified-listing/store/custom/` via `get_custom_store_template()`.
- **Assets:** `inc/scripts.php` registers/enqueues everything. Main stylesheets go through `Helper::get_maybe_rtl_css()`, which serves `assets/css-rtl/<file>.css` on RTL sites. **`assets/css/` and `assets/css-rtl/` are separate hand-maintained copies** — any change to `styles.css`/`default.css` must be mirrored (with left/right flipped) in `css-rtl/`. The RTL copy is not identical line-for-line, so search for the matching selector instead of assuming the same position.
- **Front-end JS:** `assets/js/main.js` is one jQuery IIFE. Initialisation is split between `document.ready` (`rdtheme_content_ready_scripts`), `window.load` (`rdtheme_content_load_scripts`, `isSelect2`, `clpropertyIonRangeSlider`) and an Elementor `frontend/element_ready` hook for the editor preview — new widget JS usually needs to be called from both the load path and the Elementor hook. Localised data is available as `ClPropertyObj` (e.g. `ClPropertyObj.ajaxUrl`).

## Changelog

`readme.txt` holds the theme changelog in a loose format (`Version: X ( UNRELEASE )` / `ChangeLog:` / tab-indented `Fixed:`/`Added:` lines), newest block at the top. Add user-visible changes to the topmost UNRELEASE block; don't bump `Version:` in `style.css` while a block is unreleased.
