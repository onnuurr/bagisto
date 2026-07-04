---
name: shop-frontend-design
description: Use whenever redesigning, restyling, rebranding, or writing new UI for the Bagisto storefront (the `Webkul/Shop` package) — a new theme, a new page layout, a new component, new brand colors/fonts, a homepage rebuild. Trigger on "storefront'u yeniden tasarla", "shop temasını değiştir", "vitrin tasarımını yenile", "yeni ürün sayfası tasarımı", "redesign the storefront", "restyle the shop theme", "create a new shop theme", "add a new shop component". This skill documents the existing Blade + Tailwind + single-Vue-instance architecture and the theme-override mechanism, so a redesign extends it instead of inventing a new stack (React, Vue SFCs, a second CSS framework, per-component bundlers).
---

# Shop (storefront) frontend architecture — extend it, don't replace the stack

The storefront is **server-rendered Blade + Tailwind CSS 3**, with **one single global
Vue 3 instance per page** for interactivity (no `.vue` SFCs, no `<script setup>`, no
component-level bundling). Every redesign — a full re-theme or a single new section —
must fit this shape. Read this skill fully before touching
`packages/Webkul/Shop/src/Resources/` or `config/themes.php`.

## 0. First decide: reskin the existing theme, or register a new theme?

Bagisto supports **multiple storefront themes per channel** via `config/themes.php` +
`packages/Webkul/Theme/src/Themes.php`. The active theme is resolved per request in
`Shop/src/Http/Middleware/Theme.php`: it reads `core()->getCurrentChannel()->theme`,
falls back to `config('themes.shop-default')` (currently `'default'`) if the channel
has no theme or the code isn't registered.

- **Reskin in place** (fastest, affects every channel using `default`): edit
  `packages/Webkul/Shop/src/Resources/assets/{css,js}` and
  `packages/Webkul/Shop/src/Resources/views/**` directly. This is what the current
  custom brand (see tokens below) already did — there's no separate override
  folder in use.
- **Register a genuinely new theme** (keeps the old design available on other
  channels, or you want a clean non-destructive redesign): add a new entry under
  `themes.shop` in `config/themes.php` with its own `assets_path`, `views_path`
  (Blade view root that overrides the package's views on a matching-path basis —
  see `Theme::set()` / `ThemeViewFinder.php`), and its own `vite.build_directory` +
  `hot_file`. Point a channel's `theme` column at the new code to activate it.
  `resources/themes/<code>/views` is the conventional override root (currently
  empty/gitignored for `default` — nothing overrides package views today).
- Either way, **never** invent a parallel Node/React/Nuxt app. The Vite entry point
  must stay `src/Resources/assets/{css/app.css,js/app.js}` per theme so
  `@bagistoVite(...)` in the layout keeps working.

## 1. Current design tokens (this fork's brand — treat as the example to follow or replace)

Defined in `packages/Webkul/Shop/tailwind.config.js`:

```js
colors: {
    navyBlue: "#060C3B",      // primary brand color (buttons, links, selection)
    lightOrange: "#F6F2EB",   // soft background/section color
    darkGreen: "#40994A",
    darkBlue: "#0044F2",
    darkPink: "#F85156",
},
fontFamily: {
    poppins: ["Poppins", "sans-serif"],   // body font
    dmserif: ["DM Serif Display", "serif"], // display/heading font
},
```
Custom breakpoints beyond Tailwind defaults: `sm:525px`, plus numeric ones
`1180`, `1060`, `991`, `868` (used for storefront-specific layout squeezes —
grep `max-1180:` / `1180:` style usages before adding another one-off breakpoint).
Container max width caps at `1440px` (`2xl` screen), with `90px` side padding.

Google Fonts are loaded directly in the `<head>` in
`components/layouts/index.blade.php` (`Poppins` + `DM Serif Display`) — if you
change `fontFamily`, update that `<link>` too, they are not bundled locally.

Reusable brand button classes live in `assets/css/app.css` under `@layer components`:
`.primary-button` (solid `bg-navyBlue`, white text, `rounded-xl`) and
`.secondary-button` (outlined, `text-navyBlue`). Prefer reusing/adjusting these two
classes over hand-rolling button styles inline — every CTA in the theme should
resolve to one of them.

A full **redesign** = changing this token block (colors/fonts/breakpoints) +
the two button classes + the Google Fonts `<link>`, then letting Tailwind utility
classes across the Blade views pick up the new tokens (most views use tokens like
`bg-navyBlue`/`font-poppins` directly rather than hardcoded hex, so a token swap
propagates broadly — grep for the old hex/color name to find stragglers first).

## 2. Page & layout composition

Master shell: `components/layouts/index.blade.php` (`<x-shop::layouts>`). Renders,
in order: flash messages, confirm modal, `<x-shop::layouts.header>` (unless
`hasHeader=false`), GDPR cookie banner, `<main id="main">{{ $slot }}</main>`,
`<x-shop::layouts.services>` (trust badges strip, unless `hasFeature=false`),
`<x-shop::layouts.footer>`. Every top-level page view wraps its content in this
component and sets `<x-slot:title>`.

Extension points to prefer over editing the shell directly:
`view_render_event('bagisto.shop.layout.<head|body|content>.<before|after>')` —
these fire in the layout and let a listener inject markup without modifying
`components/layouts/index.blade.php`.

The homepage (`views/home/index.blade.php`) is data-driven: it loops
`$customizations` (Admin → Settings → Themes) and switches on
`$customization->type` (`IMAGE_CAROUSEL`, `STATIC_CONTENT`, product carousels,
etc.). A homepage redesign usually means adding a new `@case` here plus a matching
Blade component under `views/components/`, not rewriting the whole file.

Key directories under `packages/Webkul/Shop/src/Resources/views/`:
`components/` (shared Blade component library — `button`, `form`, `products`,
`carousel`, `modal`, `drawer`, `dropdown`, `tabs`, `accordion`, `datagrid`,
`quantity-changer`, `range-slider`, `image-zoomer`, `shimmer` loading skeletons),
`home/`, `products/{view,prices}`, `categories/`, `checkout/{cart,onepage}`,
`customers/account/`, `cms/`, `search/`, `compare/`, `errors/`.

## 3. Component authoring pattern — copy this shape exactly

Every interactive Blade component follows the same three-part shape (see
`components/button/index.blade.php`):

```blade
{{-- 1. The custom element the Blade component renders --}}
<v-my-thing {{ $attributes }}></v-my-thing>

@pushOnce('scripts')
    {{-- 2. Its template, as a non-executing script tag, ID-matched to the tag name --}}
    <script type="text/x-template" id="v-my-thing-template">
        <div>
            @{{ someState }}  {{-- @{{ }} escapes Blade so Vue sees {{ }} --}}
        </div>
    </script>

    {{-- 3. Registration on the single global app instance from assets/js/app.js --}}
    <script type="module">
        app.component('v-my-thing', {
            template: '#v-my-thing-template',
            props: { /* ... */ },
            data() { return {}; },
            methods: { /* ... */ },
        });
    </script>
@endPushOnce
```

Rules that keep this consistent:
- `@pushOnce('scripts')` (not `@push`) so the template/registration is emitted once
  even if the component is used many times on one page.
- Never create a `.vue` single-file component for storefront work — there is no
  SFC compilation step wired into `Shop/vite.config.js` for views; everything
  mounts through `window.app` in `assets/js/app.js`.
- Global plugins (`axios`, event emitter `$emitter`, `shop` helpers, `vee-validate`,
  `flatpickr`) and the `v-debounce` directive are already registered in `app.js` —
  reuse `this.$emitter.emit(...)` / `this.$axios` inside new components rather than
  importing fresh libraries.
- Form fields go through `components/form/control-group/*` (`index`, `label`,
  `control`, `error`) for consistent label/error/spacing — build new form UI on top
  of that wrapper, don't hand-roll label/error markup per field.

## 4. Icon system

Icons are a **custom icon font** (`bagisto-shop.woff`, `@font-face` in
`assets/css/app.css`), with each glyph declared as `.icon-<name>:before { content:
"\eXXX"; }`. There is **no icomoon source project checked into this repo** — the
codepoint list in `app.css` is the only source of truth for existing glyphs.
Adding a brand-new icon that isn't already in that list requires either:
(a) regenerating the font via icomoon.io (or similar) with the new glyph and
updating the `.woff` + the `content:` codepoints, or (b) — preferred for a
redesign, since it needs no font rebuild — using an inline `<svg>` instead of
extending the icon font. Don't invent new `.icon-*` classes that reference
codepoints not present in the actual font file.

## 5. Asset/build workflow

```bash
cd packages/Webkul/Shop
npm install
npm run dev      # dev server via Vite, hot_file at public/shop-default-vite.hot
npm run build     # production build -> public/themes/shop/default/build
```
Entry points are fixed in `Shop/vite.config.js`:
`src/Resources/assets/css/app.css` and `src/Resources/assets/js/app.js`. If you
register a second theme, its own `vite.config.js`/build directory must match what
you declared for it in `config/themes.php`.

## 6. Non-negotiable checklist before calling a redesign done

1. `vendor/bin/pint --dirty` — PHP/Blade style (Pint also lints `.blade.php`).
2. `cd packages/Webkul/Shop && npm run build` succeeds with no Tailwind/Vite errors.
3. New/changed user-facing strings go through `trans('shop::app...')`, added to
   **every** locale under `Shop/src/Resources/lang/*`, verified with
   `php artisan bagisto:translations:check`.
4. Never edit generated output in `public/themes/shop/default/build/` — only
   the `src/Resources` sources.
5. Don't touch `packages/Webkul/Admin` for a "storefront redesign" ask — Admin has
   its own, differently-tokened design system (see the `admin-frontend-design`
   skill) and the two must not be conflated.
6. If you changed brand tokens (colors/fonts), grep the view tree for the old
   hardcoded hex/color name to make sure no view bypassed the token and needs a
   manual update.

## 7. When unsure, copy a real example instead of guessing

- Full page composition: `views/home/index.blade.php` + `views/products/view/index.blade.php`.
- Component shape: `components/button/index.blade.php`, `components/products/carousel.blade.php`.
- Form UI: `components/form/control-group/*`.
- Layout/shell + extension points: `components/layouts/index.blade.php`.
- New-theme registration shape: `config/themes.php` (`themes.shop.default` block).
