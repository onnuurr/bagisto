---
name: bagisto-theme
description: Use whenever creating a brand-new Bagisto storefront theme, or integrating/porting an existing HTML commerce template into Bagisto, so the result plugs into Bagisto's Webkul\Theme view-finder/Vite system exactly instead of forking the Shop package. Trigger on requests like "yeni tema oluştur", "yeni theme yap", "elimizdeki html temayı entegre et", "html template'i bagisto'ya entegre et", "create a new theme", "integrate this HTML theme", "port this template to Bagisto".
---

# Bagisto Theme Creation & HTML-Template Integration

Themes in Bagisto are **not** a plugin/upload system. A theme is: one entry in
`config/themes.php` + a Blade view-namespace that gets searched *before*
`Webkul\Shop`'s own `shop::` views, falling back to Shop for anything you don't
override. Read `packages/Webkul/Shop/src/Resources/views` and
`config/themes.php` before starting — they are the ground truth.

## 0. How theme resolution actually works (read this first)

- `Webkul\Theme\Providers\ThemeServiceProvider` replaces Laravel's `view.finder`
  singleton with `ThemeViewFinder` (`packages/Webkul/Theme/src/ThemeViewFinder.php`).
  Every `view('shop::foo.bar')` call goes through it.
- `packages/Webkul/Shop/src/Http/Middleware/Theme.php` reads `$channel->theme`
  on each request and calls `themes()->set($code)`.
- `ThemeViewFinder::addThemeNamespacePaths()` prepends the active theme's own
  namespace hint paths in front of `shop`'s hint paths. If your theme doesn't
  have the file, resolution falls through to Shop's `shop::` view — **this is
  why you only create the Blade files you actually change**, never a full copy
  of the Shop package.
- `Theme::getViewPaths()` walks the `parent` chain (child paths first), so set
  `'parent' => 'default'` to get that fallback behavior.
- Each theme needs its **own Vite build** (own `hot_file` + `build_directory`).
  The `@bagistoVite(...)` Blade directive resolves assets from the *active*
  theme's vite config at render time — a shared build across themes doesn't work.
- Admin's channel "Design" tab (`Settings → Channels → edit → Theme` dropdown,
  `packages/Webkul/Admin/src/Resources/views/settings/channels/create.blade.php:216`)
  is populated directly from `config('themes.shop')` — adding the config entry
  is the *only* registration step; there is no separate admin form to fill in.

## 1. Scaffold a new theme package

```
packages/Webkul/<ThemeName>/
├── src/
│   ├── Providers/<ThemeName>ServiceProvider.php
│   └── Resources/
│       ├── views/
│       │   ├── components/          # x-<code>:: overrides (layouts, header, footer, ...)
│       │   ├── home/index.blade.php # only files you actually override
│       │   └── ...
│       └── assets/
│           ├── css/app.css
│           └── js/app.js
├── vite.config.js
└── package.json
```

`<ThemeName>` is a PascalCase package name (e.g. `MarketTheme`); the theme
**code** used everywhere else (config key, view namespace, `channels.theme`
value) is a short lowercase slug (e.g. `market`) — keep them consistent but
they don't have to be identical strings.

```php
// src/Providers/<ThemeName>ServiceProvider.php
namespace Webkul\<ThemeName>\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class <ThemeName>ServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'market'); // must equal views_namespace below

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'market');
    }
}
```

Register in `bootstrap/providers.php`. No `config/concord.php` entry unless the
theme introduces its own Eloquent models (it normally doesn't).

If the theme should be a standalone composer package: add
`packages/Webkul/<ThemeName>/composer.json` (psr-4 `Webkul\<ThemeName>\`) and
the matching entry to root `composer.json` → `autoload.psr-4`, then
`composer dump-autoload`. Skip this if it only needs Blade/asset files.

## 2. Register the theme — `config/themes.php`

```php
'shop' => [
    'default' => [ ... ],           // never touch this block

    'market' => [
        'name'             => 'Market Theme',
        'assets_path'      => 'public/themes/shop/market',
        'views_path'       => 'packages/Webkul/MarketTheme/src/Resources/views',
        'views_namespace'  => 'market',   // must equal the loadViewsFrom namespace above
        'parent'           => 'default',  // unmatched views fall back to Shop's shop:: views
        'vite' => [
            'hot_file'                  => 'market-vite.hot',
            'build_directory'           => 'themes/shop/market/build',
            'package_assets_directory'  => 'src/Resources/assets',
        ],
    ],
],
```

Do this and the theme instantly appears in the admin Channel "Design" dropdown
— no further wiring.

## 3. Vite build (own hot file, own build dir — never share Shop's)

```js
// packages/Webkul/MarketTheme/vite.config.js — copy Shop's shape, change 3 values
laravel({
    hotFile: "../../../public/market-vite.hot",
    publicDirectory: "../../../public",
    buildDirectory: "themes/shop/market/build",
    input: [
        "src/Resources/assets/css/app.css",
        "src/Resources/assets/js/app.js",
    ],
    refresh: true,
})
```
`cd packages/Webkul/MarketTheme && npm install && npm run build` (or `npm run dev` for HMR).
The layout's `@bagistoVite([...])` call must reference the same two entry files.

## 4. Activate per channel

Admin → Settings → Channels → edit channel → Design tab → pick the new theme
from the dropdown → Save. Writes `market` into `channels.theme`; the `theme`
middleware picks it up on the next storefront request. Run
`php artisan optimize:clear` after any config change.

## 5. Integrating an existing HTML commerce template (the common case)

Goal: reskin Bagisto with a purchased/existing static HTML template **without**
losing Bagisto's dynamic behavior (cart, wishlist, compare, ajax add-to-cart,
mini-search, checkout steps). Do NOT try to drop in the template's own JS
wholesale — most of that interactivity in Bagisto is wired through Vue
components mounted from `Shop/src/Resources/assets/js/app.js` and Blade data
passed to `x-shop::` components. Treat this as **markup/CSS replacement**, not
a rewrite.

Steps:

1. Follow §1–§4 to scaffold `packages/Webkul/<ThemeName>` and register it —
   do this first so you have a live theme slot to iterate in (`npm run dev`
   + assign the theme to a test channel).
2. Inventory the HTML template's pages and map each to Bagisto's real Blade
   entry points — don't guess names, open the corresponding Shop view first:
   - Homepage → `Shop/src/Resources/views/home/index.blade.php`
   - Header/footer/nav → `Shop/src/Resources/views/components/layouts/{header,footer}/*`
   - Category/listing → `Shop/src/Resources/views/categories/*`
   - Product page → `Shop/src/Resources/views/products/*`
   - Cart/checkout → `Shop/src/Resources/views/checkout/*`
   - Account pages → `Shop/src/Resources/views/customers/*`
3. For each page you're re-skinning: copy the **existing Shop Blade file**
   into your theme at the identical relative path, then replace only the
   static markup/classes with the HTML template's markup, keeping every Blade
   directive, `{{ }}`/`{!! !!}` expression, `@foreach`, `x-shop::` component
   usage, form `action`/`route()` call, and `view_render_event()` hook intact.
   This is the safe way to "port" a template: markup changes, data wiring
   doesn't.
4. Never blanket-copy the whole Shop package into your theme — only the files
   whose look actually changes. Everything else correctly falls through to
   `shop::` (the `parent: 'default'` chain from §0).
5. Port the template's CSS into `Resources/assets/css/app.css` (Tailwind
   config lives in the theme package if you need custom design tokens — copy
   `Shop/tailwind.config.js` and extend, don't replace, its content globs so
   Shop's own utility classes used inside unswapped views keep working).
6. Port only template JS that adds *new* UI behavior the template needs
   (e.g. a custom slider). Wire it as an additional entry/import inside the
   theme's `app.js` — do not remove Shop's Vue app bootstrap or the existing
   cart/wishlist/compare/quick-view components unless you are reimplementing
   that behavior yourself.
7. If the template's homepage has editable sections (hero banners, featured
   categories/products, footer link columns), prefer wiring them to Bagisto's
   existing **Theme Customization** content blocks
   (`Webkul\Theme\Models\ThemeCustomization`, admin **Settings → Themes**)
   instead of hardcoding them in Blade — this keeps the "swap banners without
   a deploy" admin workflow that merchants expect. Look at how
   `shop::home.index` currently renders these blocks via
   `ThemeCustomizationRepository` and mirror that in your override.
8. Iterate with `npm run dev` in the theme package + the test channel set to
   your theme; visually diff each converted page against both the HTML
   template and the stock Bagisto page to make sure no functional element
   (add-to-cart, quantity stepper, filters, pagination, checkout steps) was
   dropped.

## 6. Validation checklist (run before calling it done)

1. `php artisan optimize:clear` after every `config/themes.php` change.
2. `npm run build` inside the theme package — confirm the manifest/hot file
   names match what's declared in `config/themes.php`.
3. Switch a test channel to the new theme in admin and click through:
   home, category, product, cart, checkout, account — confirm nothing 404s
   and no Shop JS component (cart drawer, wishlist, compare, search) broke.
4. Switch the channel back to `default` — confirm the site is unaffected
   (proves you didn't edit anything inside `Webkul\Shop` itself).
5. `vendor/bin/pint --dirty` if you added any PHP (ServiceProvider, etc.).
6. If you added new translation strings, add them to **every locale** under
   the relevant package's `Resources/lang/*` and run
   `php artisan bagisto:translations:check`.
7. Never edit files under `packages/Webkul/Shop` or `public/themes/shop/default/build`
   to achieve the reskin — everything belongs in the new theme package.

## 7. When unsure, copy the real mechanism instead of guessing

- Theme registry shape: `config/themes.php`.
- View-finder/theme-switch mechanics: `packages/Webkul/Theme/src/{Theme.php,Themes.php,ThemeViewFinder.php}`,
  `packages/Webkul/Shop/src/Http/Middleware/Theme.php`.
- Vite wiring: `packages/Webkul/Shop/vite.config.js` + `@bagistoVite` usage in
  `packages/Webkul/Shop/src/Resources/views/components/layouts/index.blade.php`.
- Admin activation UI: `packages/Webkul/Admin/src/Resources/views/settings/channels/create.blade.php`
  (theme dropdown) + `edit.blade.php` (same pattern).
