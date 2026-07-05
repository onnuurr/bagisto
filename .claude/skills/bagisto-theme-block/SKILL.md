---
name: bagisto-theme-block
description: Use whenever adding a new homepage/section content block ("blok") to Bagisto's Theme Customization feature (Admin > Settings > Themes), or extending an existing block's options. Trigger on requests like "tema özelleştirmesine yeni blok ekle", "yeni sayfa bloğu ekle", "add a new theme customization block type", "add homepage section", "extend theme carousel block". Do NOT use for CMS static pages (that's packages/Webkul/CMS) or for brand-new unrelated packages (see the bagisto-module skill for that).
---

# Bagisto Theme Customization blocks — apply exactly, don't improvise

There is only **one** entity — `ThemeCustomization` (`packages/Webkul/Theme/src/Models/ThemeCustomization.php`)
with columns `type`, `name`, `sort_order`, `status`, `channel_id`, `theme_code`, and a
per-locale translatable `options` JSON blob. Every "block" (image carousel, product
carousel, category carousel, static content, footer links, services content) is just
a different `type` value with a different shape inside `options`. **A new block type
never means a new table/model.**

Block types are registered in `Webkul\Theme\ThemeBlockRegistry` (a container singleton,
`packages/Webkul/Theme/src/ThemeBlockRegistry.php`), seeded with the six core types by
`ThemeServiceProvider::registerCoreThemeBlocks()`. The Admin controller, the create-modal
dropdown, the edit-form dispatcher, and the storefront homepage loop all read from this
registry instead of hardcoding a list — so registering a new type in one place is enough
to make it selectable, editable, and (if it has a `shop_view`) rendered on the storefront.
**You almost never need to touch `ThemeController`, `index.blade.php`, `edit.blade.php`,
or `home/index.blade.php` for a new type** — you only add a `register()` call plus the
type's own view/partial files.

Read `packages/Webkul/Theme/src/ThemeBlockRegistry.php` and the
`registerCoreThemeBlocks()` method in
`packages/Webkul/Theme/src/Providers/ThemeServiceProvider.php` first — together they are
the canonical reference and show the exact shape of a block definition.

## 0. Decide the shape before writing code

Two flavors of block exist, and they render completely differently on the storefront:

- **Repeatable / ordered** (`image_carousel`, `product_carousel`, `category_carousel`,
  `static_content`) — the admin can create many rows of this type; the storefront loops
  over ALL active `ThemeCustomization` rows for the channel/theme and, for each row,
  `@includeWhen`s the block's registered `shop_view`
  (`Shop/src/Resources/views/home/index.blade.php`).
- **Singleton / layout slot** (`footer_links`, `services_content`) — conceptually only one
  active row per channel/theme matters; a specific layout partial injects the repository
  directly and fetches it with `findOneWhere(['type' => ..., 'channel_id' => ...,
  'theme_code' => ...])`, independent of the homepage loop. These register `shop_view =>
  null` in the registry. See
  `Shop/src/Resources/views/components/layouts/footer/index.blade.php:7-32` and
  `.../services.blade.php:7-27`.

Pick whichever matches the feature request — a "new homepage section" is repeatable, a
"new footer/header widget" is a singleton slot.

## 1. Add the type constant — `packages/Webkul/Theme/src/Models/ThemeCustomization.php`

```php
/**
 * <Description> precision.
 *
 * @var string
 */
public const <NEW_TYPE_CONST> = '<new_type_key>'; // snake_case, unique
```

Put it next to the existing six constants. The string value is what's stored in the
`type` column and is the registry key.

## 2. Register the block — wherever the code that owns it boots

If the block belongs to core, add it in
`ThemeServiceProvider::registerCoreThemeBlocks()`. If it's added by a separate/plugin
package, register it from that package's own `ServiceProvider::boot()` instead — do not
edit the Theme package for a non-core block:

```php
app(\Webkul\Theme\ThemeBlockRegistry::class)->register(ThemeCustomization::<NEW_TYPE_CONST>, [
    'label' => 'admin::app.settings.themes.create.type.<new-type-slug>',
    'admin_view' => 'admin::settings.themes.edit.<new-type-slug>',
    'shop_view' => 'shop::home.blocks.<new-type-slug>', // null for a singleton/layout-slot block
    'has_upload' => false, // true if the block stores uploaded images via ThemeCustomizationRepository::uploadImage()
]);
```

This one call is what makes the type appear in the create-modal dropdown (`index.blade.php`
reads `ThemeBlockRegistry::all()`), pass `ThemeController` validation (`in:` list is built
from `ThemeBlockRegistry::types()`), and get its `admin_view`/`shop_view` included
automatically by `edit.blade.php` / `home/index.blade.php`.

If the new type needs uploaded images, also mirror the existing rule in
`ThemeController::store()`: `core()->getRequestedLocaleCode().'.options.*.image' =>
'image|extensions:jpeg,jpg,png,svg,webp'`.

## 3. Add the admin edit-form partial

Create `Admin/src/Resources/views/settings/themes/edit/<new-type-slug>.blade.php` — the
file at the `admin_view` path you registered. Copy the shape of an existing partial that
matches your data complexity:
- Simple key/value fields (title, sort, limit, single toggle) → copy
  `edit/category-carousel.blade.php` or `edit/product-carousel.blade.php`.
- Repeatable image/slide list with upload + delete → copy `edit/image-carousel.blade.php`.
- Rich HTML/CSS free text → copy `edit/static-content.blade.php`.
- Repeatable non-image cards (icon + title + description) → copy `edit/services-content.blade.php`.

The pattern in every partial is the same: a Blade `<script type="text/x-template" id="v-<name>-template">`
holding the markup, registered as a Vue component in a sibling
`<script type="module">app.component('v-<name>', {...})</script>` block, with
`data()` seeded from `@json($theme->translate($currentLocale->code)['options'] ?? null)`
and every field's `name` attribute written as `{{ $currentLocale->code }}[options][<key>]`
so it lands in `request()->input($locale)['options'][<key>]` server-side. Use
`x-admin::shimmer.settings.themes.<slug>` as the loading placeholder if one exists, or
reuse `x-admin::shimmer.settings.themes.category-carousel` as a generic fallback.

## 4. Wire server-side option handling — `Theme/src/Repositories/ThemeCustomizationRepository.php`

`update()` special-cases two things, both driven off the registry (no per-type
`in_array` list to edit):
- `static_content` sanitizes `options.html` via `Purify` and `options.css` via
  `sanitizeStaticCss()` — copy this `if ($data['type'] == 'static_content')` block (or
  add a similar `elseif`) if the new type accepts raw HTML/CSS.
- Any type registered with `'has_upload' => true` is excluded from the normal
  translated-attribute save and instead goes through `uploadImage()`, which loops
  `options` entries and either stores an uploaded file as webp under
  `theme/<id>/<random>.webp` or passes through already-saved image refs. Setting
  `has_upload` in the registry entry (step 2) is enough — no code here to touch unless
  `uploadImage()`'s per-item shape (`service_icon` vs `image`+`link`+`title`) doesn't fit
  your new type's options, in which case extend the `foreach ($data[$locale]['options']
  as $image)` loop.

## 5. Render it on the storefront — `packages/Webkul/Shop/src/Resources/views/`

**Repeatable block** → create the `shop_view` partial you registered, e.g.
`Shop/src/Resources/views/home/blocks/<new-type-slug>.blade.php`. It's included with the
current `$data` (`$customization->options`) already in scope — no `@case`/`@switch` to
edit:

```blade
<x-shop::<existing-or-new-component>
    :title="$data['title'] ?? ''"
    ...
/>
```

Reuse an existing Blade component (`x-shop::carousel`, `x-shop::products.carousel`,
`x-shop::categories.carousel`) if the new block is visually similar; only add a new
component under `Shop/src/Resources/views/components/` if the markup is genuinely new.

**Singleton/layout-slot block** → register `'shop_view' => null` (step 2) and follow
`footer/index.blade.php` / `services.blade.php`: inject the repository directly in the
layout partial that needs it and fetch by type:

```blade
@inject('themeCustomizationRepository', 'Webkul\Theme\Repositories\ThemeCustomizationRepository')

@php
    $customization = $themeCustomizationRepository->findOneWhere([
        'type' => '<new_type_key>',
        'status' => 1,
        'channel_id' => core()->getCurrentChannel()->id,
        'theme_code' => core()->getCurrentChannel()->theme,
    ]);
@endphp

@if ($customization?->options)
    @foreach ($customization->options as $item)
        ...
    @endforeach
@endif
```

## 6. Translations — every locale, no exceptions

Add these keys under `admin::app.settings.themes.*` in **every** directory under
`packages/Webkul/Admin/src/Resources/lang/` (run `ls packages/Webkul/Admin/src/Resources/lang`
for the current locale list — don't hardcode a count):
- `create.type.<new-type-slug>` — the `label` you registered; shown in the create-modal dropdown.
- `edit.<new-type-slug>` / `edit.<new-type-slug>-description` — heading + helper text
  used in the edit partial, plus any field-specific labels you introduced.

Add any new shop-facing strings (e.g. `aria-label`) under `shop::app.home.index.*` in
`packages/Webkul/Shop/src/Resources/lang/`, same rule — all locales.

Verify with `php artisan bagisto:translations:check`.

## 7. Non-negotiable validation checklist

1. Model constant added (`ThemeCustomization.php`).
2. Block registered once via `ThemeBlockRegistry::register()` — from `ThemeServiceProvider`
   for core blocks, or from the owning package's own provider otherwise. This single call
   drives the create-modal dropdown, `ThemeController` validation, the edit-form include,
   and (if `shop_view` is set) the homepage render — nothing else to wire by hand.
3. Admin edit partial created at the registered `admin_view` path.
4. Storefront `shop_view` partial created (repeatable blocks) — or `shop_view: null` plus
   an `@inject` + `findOneWhere` layout partial (singleton/layout-slot blocks).
5. Repository touched only if the type needs HTML/CSS sanitization (extend the
   `static_content` branch) or its upload shape doesn't fit the existing `uploadImage()`
   loop — otherwise `has_upload: true` in the registry entry is sufficient.
6. Translation keys added in **every** locale directory, both Admin and (if applicable) Shop.
7. `vendor/bin/pint --dirty` clean.
8. `php artisan bagisto:translations:check` clean.
9. Manually verify in the browser: create a row of the new type in Admin > Settings >
   Themes, fill the edit form, save, then load the storefront page that should render it.

## 8. When unsure, copy a real example instead of guessing

- Simple field-based block, no upload: `category_carousel` /
  `edit/category-carousel.blade.php` / `home/blocks/category-carousel.blade.php`.
- Repeatable image upload block: `image_carousel` / `edit/image-carousel.blade.php`.
- Raw HTML/CSS block with sanitization: `static_content` / `edit/static-content.blade.php`.
- Singleton layout-slot block: `footer_links` (footer) or `services_content` (services
  strip) — see their respective Shop layout partials, not the homepage loop.
- The registry entries for all six core blocks:
  `ThemeServiceProvider::registerCoreThemeBlocks()`.
