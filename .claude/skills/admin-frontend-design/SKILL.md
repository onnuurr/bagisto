---
name: admin-frontend-design
description: Use whenever redesigning, restyling, or writing new UI for the Bagisto admin panel (the `Webkul/Admin` package) — a new admin theme, a redesigned dashboard/settings page, a new admin component, dark-mode-aware styling. Trigger on "admin panelini yeniden tasarla", "yönetici paneli tasarımını değiştir", "admin temasını yenile", "redesign the admin panel", "restyle the admin dashboard", "add a new admin component". This skill documents the existing Blade + Tailwind + single-Vue-instance + dark-mode architecture and the theme-registry mechanism, so a redesign extends it instead of inventing a new stack.
---

# Admin panel frontend architecture — extend it, don't replace the stack

The admin panel is **server-rendered Blade + Tailwind CSS 3**, dark-mode aware
(`darkMode: 'class'`), with **one single global Vue 3 instance per page** for
interactivity — same underlying pattern as the storefront (see
`shop-frontend-design` skill), but a separate app, separate Tailwind config,
separate token set, and its own Vite build. Never assume the two share styling —
Admin intentionally uses a neutral, functional palette instead of the storefront's
brand colors.

## 0. First decide: reskin the existing theme, or register a new admin theme?

Same mechanism as the storefront, mirrored for admin in `config/themes.php` under
`themes.admin` (currently only `default`, resolved via `themes.admin-default`).
Unlike the storefront, admin theme selection is **not per-channel** — the
`Theme` middleware picks `themes.admin` when the request URL contains
`config('app.admin_url').'/'` (see `Webkul\Theme\Themes::loadThemes()`), always
resolving to whatever `admin-default` points to. To ship a genuinely swappable
admin theme you'd add a new code under `themes.admin` with its own
`assets_path`/`views_path`/`vite` block and repoint `admin-default` at it — there
is currently no per-user/per-tenant admin theme switcher, so treat a "new admin
theme" as a global config change, not a runtime-selectable option, unless you also
build that selection UI.

For most redesign requests, **reskin in place**: edit
`packages/Webkul/Admin/src/Resources/{assets,views}` directly — this is the
existing single admin theme.

## 1. Current design tokens

Defined in `packages/Webkul/Admin/tailwind.config.js`:

```js
theme: {
    container: { center: true, screens: { "2xl": "1920px" }, padding: { DEFAULT: "16px" } },
    screens: { sm: "525px", md: "768px", lg: "1024px", xl: "1240px", "2xl": "1920px" },
    extend: {
        colors: {
            darkGreen: "#40994A",
            darkBlue: "#0044F2",
            darkPink: "#F85156",
        },
        fontFamily: {
            inter: ["Inter"],     // body font
            icon: ["icomoon"],    // icon font family
        },
    },
},
darkMode: "class",
```

Note admin does **not** define a `navyBlue`/brand primary the way Shop does — most
UI relies on stock Tailwind palette (`blue-600`, `gray-*`, `slate-*`, `rose-*`,
`indigo-*`) directly in views, plus the three semantic accents above
(`darkGreen`/`darkBlue`/`darkPink` — success/info/danger-ish accents, check actual
usages with `grep -rn "darkGreen\|darkBlue\|darkPink"` before assuming semantics).
If a redesign introduces a brand primary color for admin, add it to this
`colors` block and prefer it over scattering new hex literals through views.

Reusable button classes in `assets/css/app.css`: `.primary-button` and
`.secondary-button` (the latter is `border-2 border-blue-600 ... dark:border-gray-400
dark:bg-gray-800 dark:text-white` — **every** new/changed component class must
carry a `dark:` variant, this is non-negotiable for admin, unlike Shop which has
no dark mode at all).

**Dark mode mechanism**: toggled via a `dark_mode` cookie, applied as a class on
`<html>` in `components/layouts/index.blade.php`:
`class="{{ request()->cookie('dark_mode') ?? 0 ? 'dark' : '' }}"`. Any new
component must be styled for both states using Tailwind's `dark:` variant — don't
ship admin UI that only looks right in light mode.

## 2. Page & layout composition

Master shell: `components/layouts/index.blade.php`. Other layout pieces:
`components/layouts/header/index.blade.php` (top bar), `components/layouts/sidebar/index.blade.php`
(main nav — iterates `menu()->getItems('admin')`, which aggregates every package's
`Config/menu.php` + ACL; don't hardcode nav items in the Blade sidebar template
itself), `components/layouts/tabs.blade.php`
(shared tabbed-page pattern used across settings/catalog edit screens),
`components/layouts/anonymous.blade.php` (login/forgot-password/reset-password —
no sidebar/header chrome).

Key directories under `packages/Webkul/Admin/src/Resources/views/`:
`components/` (shared component library — `button`, `form`, `table`, `datagrid`,
`tree` (category tree), `charts` (dashboard/reporting), `star-rating`, `seo`,
`catalog`, `products`, `media`, `modal`, `drawer`, `dropdown`, `tabs`, `accordion`,
`quantity-changer`, `shimmer`), then one directory per admin section:
`dashboard/`, `catalog/{attributes,categories,families,products}`, `sales/*`,
`customers/*`, `marketing/*`, `reporting/*`, `settings/*`, `cms/`.

## 3. Component authoring pattern — identical shape to Shop

Same three-part pattern as documented in the `shop-frontend-design` skill
(`<v-x {{ $attributes }}>` root tag, `@pushOnce('scripts')` with a
`text/x-template` script + `app.component(...)` registration against the single
global `window.app`). Compare `Admin/.../components/button/index.blade.php`
against `Shop/.../components/button/index.blade.php` — they're nearly identical,
confirming this is the house style, not a Shop-only convention. Reuse it exactly
for any new admin component; don't introduce `.vue` SFCs.

`DataGrid` components (`components/datagrid/`) back nearly every listing page —
a redesigned listing screen should still render through the existing DataGrid
Blade/Vue component wired to a PHP `DataGrid` class
(`DataGrids/<Feature>/<Entity>DataGrid.php`), not a hand-built table, to keep
sorting/filtering/mass-actions/ACL gating working.

## 4. Icon system

Admin uses an icon font under the `icon` font family (`icomoon`), same
`.icon-*:before { content: "\eXXX"; }` pattern as the storefront, defined in
`Admin/src/Resources/assets/css/app.css`. No icomoon source project is checked
into the repo here either — treat the existing codepoint list as the closed set
of available glyphs; use inline SVG for anything new rather than inventing
`.icon-*` classes with no backing glyph.

## 5. Asset/build workflow

```bash
cd packages/Webkul/Admin
npm install
npm run dev      # Vite dev server, hot_file at public/admin-default-vite.hot
npm run build     # production build -> public/themes/admin/default/build
```
Entry points fixed in `Admin/vite.config.js`:
`src/Resources/assets/css/app.css` and `src/Resources/assets/js/app.js`.

## 6. Non-negotiable checklist before calling a redesign done

1. `vendor/bin/pint --dirty` — style check.
2. `cd packages/Webkul/Admin && npm run build` succeeds.
3. **Every** new/changed component renders correctly in both light and dark mode
   (toggle the `dark_mode` cookie or the in-app dark-mode switch and check).
4. New/changed strings go through `trans('admin::app...')`, added to every locale
   under `Admin/src/Resources/lang/*`, verified with
   `php artisan bagisto:translations:check`.
5. Never edit generated output in `public/themes/admin/default/build/`.
6. Listing pages stay backed by a `DataGrid` class + ACL checks
   (`bouncer()->hasPermission(...)`) — a redesign must not bypass permission
   gating that existed on the page before.
7. Don't touch `packages/Webkul/Shop` for an "admin redesign" ask — keep the two
   design systems (and their token sets) separate; see `shop-frontend-design`.

## 7. When unsure, copy a real example instead of guessing

- Full page + tabs pattern: any `catalog/products/edit.blade.php` alongside
  `components/layouts/tabs.blade.php`.
- Component shape: `components/button/index.blade.php`, `components/datagrid/*`.
- Dashboard/chart UI: `components/charts/*`, `views/dashboard/*`.
- Layout/shell + sidebar/menu wiring: `components/layouts/index.blade.php`,
  `components/layouts/sidebar/index.blade.php`.
- New-theme registration shape: `config/themes.php` (`themes.admin.default` block).
