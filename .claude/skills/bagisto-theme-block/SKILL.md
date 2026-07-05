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
never means a new table/model** — it means touching the same ~6 places every existing
type touches. Skipping one of them is the most common mistake (e.g. adding the model
constant but forgetting the controller's `in:` whitelist, so saving 422s).

Read `packages/Webkul/Theme/src/Models/ThemeCustomization.php` and
`packages/Webkul/Admin/src/Http/Controllers/Settings/ThemeController.php` first — they
are short and are the canonical reference.

## 0. Decide the shape before writing code

Two flavors of block exist, and they render completely differently on the storefront:

- **Repeatable / ordered** (`image_carousel`, `product_carousel`, `category_carousel`,
  `static_content`) — the admin can create many rows of this type; the storefront
  loops over ALL active `ThemeCustomization` rows for the channel/theme and switches
  on `type` in the loop (`Shop/src/Resources/views/home/index.blade.php:38-88`).
- **Singleton / layout slot** (`footer_links`, `services_content`) — conceptually only
  one active row per channel/theme matters; a specific layout partial injects the
  repository directly and fetches it with `findOneWhere(['type' => ..., 'channel_id' => ...,
  'theme_code' => ...])`, independent of the homepage loop. See
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

Put it next to the existing six constants (lines 36-71). The string value is what's
stored in the `type` column and is what Blade's `@switch ($customization->type)` /
`@case ($customization::<NEW_TYPE_CONST>)` matches against.

## 2. Whitelist it in the Admin controller — `Admin/src/Http/Controllers/Settings/ThemeController.php`

Both `store()` (line 59) and `update()` (line 97) validate `type` against a hardcoded
`in:` list. **Add the new key to both lines**, or creating/saving a row of the new type
fails validation with a 422:

```php
'type' => 'required|in:product_carousel,category_carousel,static_content,image_carousel,footer_links,services_content,<new_type_key>',
```

If the new type needs uploaded images, mirror the existing rule at line 48:
`core()->getRequestedLocaleCode().'.options.*.image' => 'image|extensions:jpeg,jpg,png,svg,webp'`.

## 3. Add it to the "create" modal — `Admin/src/Resources/views/settings/themes/index.blade.php`

Two things in this one file:
- The `<option>` list is driven by a plain JS object `themeTypes` (around line 200-208)
  keyed by the type string, valued by a translated label:
  ```js
  <new_type_key>: "@lang('admin::app.settings.themes.create.type.<new-type-slug>')",
  ```
- Nothing else here needs to change — the `<select name="type">` (line ~111) already
  iterates `v-for="(type, key) in themeTypes"`.

## 4. Add the edit-form partial

**a)** Register it in `Admin/src/Resources/views/settings/themes/edit.blade.php`
(around lines 88-106), alongside the existing `@includeWhen`s:

```blade
<!-- <Block-Name> Template -->
@includeWhen($theme->type === '<new_type_key>', 'admin::settings.themes.edit.<new-type-slug>')
```

**b)** Create `Admin/src/Resources/views/settings/themes/edit/<new-type-slug>.blade.php`.
Copy the shape of an existing partial that matches your data complexity:
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

## 5. Wire server-side option handling — `Theme/src/Repositories/ThemeCustomizationRepository.php`

`update()` (line 28) special-cases two things by `$data['type']`:
- `static_content` sanitizes `options.html` via `Purify` and `options.css` via
  `sanitizeStaticCss()` (lines 32-41) — copy this if the new type accepts raw HTML/CSS.
- `image_carousel` / `services_content` are excluded from the normal translated-attribute
  save and instead go through `uploadImage()` (lines 44-52, 92-146), which loops
  `options` entries and either stores an uploaded file as webp under
  `theme/<id>/<random>.webp` or passes through already-saved image refs. **Add the new
  type to the `in_array(..., ['image_carousel', 'services_content'])` checks in both
  places** if it involves file uploads; otherwise the default `parent::update()` path
  (plain JSON save of the translated `options`) is enough and you don't need to touch
  this file at all.

## 6. Render it on the storefront — `packages/Webkul/Shop/src/Resources/views/`

**Repeatable block** → add a `@case` to the switch in `home/index.blade.php` (lines 42-87):

```blade
@case ($customization::<NEW_TYPE_CONST>)
    <x-shop::<existing-or-new-component>
        :title="$data['title'] ?? ''"
        ...
    />

    @break
```

Reuse an existing Blade component (`x-shop::carousel`, `x-shop::products.carousel`,
`x-shop::categories.carousel`) if the new block is visually similar; only add a new
component under `Shop/src/Resources/views/components/` if the markup is genuinely new.

**Singleton/layout-slot block** → follow `footer/index.blade.php` / `services.blade.php`:
inject the repository directly in the partial that needs it and fetch by type:

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

## 7. Translations — every locale, no exceptions

Add these keys under `admin::app.settings.themes.*` in **every** directory under
`packages/Webkul/Admin/src/Resources/lang/` (run `ls packages/Webkul/Admin/src/Resources/lang`
for the current locale list — don't hardcode a count):
- `create.type.<new-type-slug>` — label shown in the create-modal dropdown.
- `edit.<new-type-slug>` / `edit.<new-type-slug>-description` — heading + helper text
  used in the edit partial, plus any field-specific labels you introduced.

Add any new shop-facing strings (e.g. `aria-label`) under `shop::app.home.index.*` in
`packages/Webkul/Shop/src/Resources/lang/`, same rule — all locales.

Verify with `php artisan bagisto:translations:check`.

## 8. Non-negotiable validation checklist

1. Model constant added (`ThemeCustomization.php`).
2. Both `in:` whitelists updated (`ThemeController::store()` AND `::update()`) — the
   single most common miss.
3. `themeTypes` JS object updated in `index.blade.php` create modal.
4. `@includeWhen` + new edit partial added in `edit.blade.php`.
5. Repository `update()`/`uploadImage()` touched ONLY if the type needs file uploads or
   HTML/CSS sanitization — otherwise leave it alone.
6. Storefront rendering added: `home/index.blade.php` `@case` for repeatable blocks, or
   an `@inject` + `findOneWhere` partial for singleton/layout-slot blocks.
7. Translation keys added in **every** locale directory, both Admin and (if applicable) Shop.
8. `vendor/bin/pint --dirty` clean.
9. `php artisan bagisto:translations:check` clean.
10. Manually verify in the browser: create a row of the new type in Admin > Settings >
    Themes, fill the edit form, save, then load the storefront page that should render it.

## 9. When unsure, copy a real example instead of guessing

- Simple field-based block, no upload: `category_carousel` /
  `edit/category-carousel.blade.php`.
- Repeatable image upload block: `image_carousel` / `edit/image-carousel.blade.php`.
- Raw HTML/CSS block with sanitization: `static_content` / `edit/static-content.blade.php`.
- Singleton layout-slot block: `footer_links` (footer) or `services_content` (services
  strip) — see their respective Shop layout partials, not the homepage loop.
