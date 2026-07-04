---
name: bagisto-module
description: Use whenever adding a new package/module, a new Eloquent entity, or new admin/shop CRUD screens to this Bagisto codebase, so the result matches the existing Concord/Repository/Proxy module architecture exactly instead of inventing a new pattern. Trigger on requests like "yeni modül ekle", "yeni entity/tablo ekle", "admin'e CRUD ekle", "add a new package", "extend Bagisto with X feature", "yeni özellik ekle".
---

# Bagisto Module Architecture — apply exactly, don't improvise

This repo already has ~40 packages under `packages/Webkul/`. Every new feature must
be shaped like the existing ones. Read `AGENTS.md` and `CLAUDE.md` at the repo root
first — they hold the canonical reference; this skill turns that reference into a
step-by-step build recipe with real file templates.

## 0. First, decide where the code goes

Three placement patterns coexist. Picking the wrong one is the most common mistake.

**A) Domain package** (`Category`, `Customer`, `Tax`, `CMS`, `Product`, ...)
Holds ONLY: Models, Contracts, Proxies, Repositories, Migrations, Factories,
Providers, Observers, Notifications. **No controllers, no routes, no admin views**
— verify by checking `packages/Webkul/Customer/src` or `packages/Webkul/Tax/src`:
neither has an `Http/` or `Routes/` directory.

**B) Admin / Shop packages** — hold ALL controllers, routes, Blade views,
DataGrids, ACL/menu config, **for every domain**, grouped in a feature subfolder.
Example for CMS pages: `Admin/src/Http/Controllers/CMS/PageController.php`,
`Admin/src/Routes/cms-routes.php`, `Admin/src/DataGrids/CMS/CMSPageDataGrid.php`,
`Admin/src/Resources/views/cms/*.blade.php`.

**C) Self-contained integration package** (`Paypal`, `Stripe`, `Razorpay`, `PayU`,
`SocialLogin`) — the exception. Bundles its OWN `Http/Controllers`, `Http/routes.php`,
`Config/system.php`, `Resources/{views,lang}` because it's a pluggable
gateway/integration, not a core commerce entity.

Rule of thumb: new **core commerce entity** with a DB table the admin manages →
A + B split. New **pluggable integration** (payment/shipping gateway, OAuth
provider) → C, self-contained.

## 1. New domain entity (pattern A) — exact file set, in order

Every DB-backed entity needs these four, never fewer:

```php
// src/Contracts/<Entity>.php — usually an empty marker interface
namespace Webkul\<Package>\Contracts;

interface <Entity> {}
```

```php
// src/Models/<Entity>.php
namespace Webkul\<Package>\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\<Package>\Contracts\<Entity> as <Entity>Contract;
use Webkul\<Package>\Database\Factories\<Entity>Factory;

class <Entity> extends Model implements <Entity>Contract
{
    use HasFactory;

    protected $table = '<table_name>';

    protected $fillable = [
        // ...
    ];

    protected static function newFactory(): Factory
    {
        return <Entity>Factory::new();
    }
}
```

```php
// src/Models/<Entity>Proxy.php — always this exact shape
namespace Webkul\<Package>\Models;

use Konekt\Concord\Proxies\ModelProxy;

class <Entity>Proxy extends ModelProxy {}
```

```php
// src/Repositories/<Entity>Repository.php
namespace Webkul\<Package>\Repositories;

use Webkul\Core\Eloquent\Repository;

class <Entity>Repository extends Repository
{
    public function model(): string
    {
        return 'Webkul\<Package>\Contracts\<Entity>'; // the Contract, never the Model
    }
}
```

Then wire it up:

5. Migration: `src/Database/Migrations/<timestamp>_create_<table>_table.php`.
6. Factory: `src/Database/Factories/<Entity>Factory.php` (referenced by `newFactory()` above).
7. Register the Model in the package's `Providers/ModuleServiceProvider.php`:
   ```php
   class ModuleServiceProvider extends CoreModuleServiceProvider
   {
       protected $models = [
           <Entity>::class,
       ];
   }
   ```
8. Add `Webkul\<Package>\Providers\ModuleServiceProvider::class` to the `modules`
   array in `config/concord.php`.
9. Package's main `<Package>ServiceProvider.php` (`extends Illuminate\Support\ServiceProvider`):
   `boot()` calls `$this->loadMigrationsFrom(__DIR__.'/../Database/Migrations')`;
   attach observers here too if any (see `Category/src/Providers/CategoryServiceProvider.php`).
10. Add `Webkul\<Package>\Providers\<Package>ServiceProvider::class` to `bootstrap/providers.php`.
11. **Only if this is a brand-new package** (not an existing one): create
    `packages/Webkul/<Package>/composer.json` (`"name": "bagisto/laravel-<package>"`,
    psr-4 `"Webkul\\<Package>\\": "src/"`), add the same PSR-4 mapping to root
    `composer.json` → `autoload.psr-4`, then run `composer dump-autoload`.

## 2. Admin CRUD for that entity (pattern B)

Everything below lives in `packages/Webkul/Admin/src/`, namespaced by feature
folder — never inside the domain package:

- **Controller**: `Http/Controllers/<Feature>/<Entity>Controller.php` — inject
  `<Entity>Repository`, never the Eloquent model directly.
- **Routes**: `Routes/<feature>-routes.php`, required from `Routes/web.php` inside
  the `['admin', NoCacheMiddleware::class]` group with `prefix => config('app.admin_url')`
  (mirror `cms-routes.php` / the `require` block in `web.php`).
- **DataGrid**: `DataGrids/<Feature>/<Entity>DataGrid.php extends Webkul\DataGrid\DataGrid`,
  implement `prepareQueryBuilder()`, `prepareColumns()`, `prepareActions()`,
  `prepareMassActions()`. Gate every action with
  `bouncer()->hasPermission('<feature>.edit')` / `.delete` (see `CMSPageDataGrid.php`).
- **Views**: `Resources/views/<feature>/{index,create,edit}.blade.php`.
- **ACL**: append entries to `Config/acl.php` — a parent `key` (`route`, `sort`) plus
  `.create` / `.edit` / `.delete` children, same shape as the existing `cms` block.
- **Menu**: append a sidebar entry to `Config/menu.php` using the same `key`.
- **Translations**: add `admin::app.<feature>.*` keys to **every locale directory**
  under `Admin/src/Resources/lang/*` (run `ls packages/Webkul/Admin/src/Resources/lang`
  to get the current list — don't hardcode a count, it changes). Never add only `en`.
  Verify with `php artisan bagisto:translations:check`.

## 3. Shop-facing storefront screens

Same split, inside `packages/Webkul/Shop/src/`: `Http/Controllers`,
`Routes/<feature>-routes.php` (middleware `['web','locale','theme','currency']`,
see how `Shop/src/Routes/web.php` requires `customer-routes.php`, `checkout-routes.php`,
etc.), `Resources/views`, `Resources/lang`.

## 4. Self-contained integration package (pattern C)

Copy the shape of `packages/Webkul/Paypal`: own `Http/Controllers`, `Http/routes.php`,
`Config/system.php` (surfaces under Admin's settings tree), own
`Resources/{views,lang}`, own `composer.json`. Register its main ServiceProvider in
`bootstrap/providers.php`. Only add a `config/concord.php` entry if it introduces its
own Eloquent models.

## 5. Non-negotiable validation checklist (mirrors AGENTS.md — run before calling it done)

1. `vendor/bin/pint --dirty` — no style violations.
2. `php artisan test --compact` (or the specific package's test dir) — affected tests pass.
3. `php artisan bagisto:translations:check` — if any translation key changed.
4. No `env()` calls outside `config/*.php`.
5. Every new model has Contract + Model + Proxy + Repository — no exceptions.
6. Every new package is registered in BOTH `bootstrap/providers.php` AND `config/concord.php`.
7. Never edit `vendor/`, `node_modules/`, `public/themes/*/build/`, `storage/`,
   or run destructive composer/package updates without approval.

## 6. When unsure, copy a real example instead of guessing

- Simplest full domain package: `packages/Webkul/Tax`.
- Domain + Admin CRUD split: `packages/Webkul/CMS` (model side) +
  `packages/Webkul/Admin/src/{Http/Controllers/CMS,DataGrids/CMS,Routes/cms-routes.php,Resources/views/cms}` (UI side).
- Self-contained integration package: `packages/Webkul/Paypal`.
