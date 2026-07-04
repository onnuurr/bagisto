<?php

namespace Webkul\Accounting\Repositories;

use Webkul\Core\Eloquent\Repository;

class SettingRepository extends Repository
{
    /**
     * In-memory cache of loaded settings, keyed by name.
     *
     * @var array|null
     */
    protected static $cache;

    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return 'Webkul\Accounting\Contracts\Setting';
    }

    /**
     * Get a setting value by name.
     */
    public function getValue(string $name, $default = null)
    {
        if (static::$cache === null) {
            static::$cache = $this->model->pluck('value', 'name')->all();
        }

        return static::$cache[$name] ?? $default;
    }

    /**
     * Set (create or update) a setting value by name.
     */
    public function setValue(string $name, $value): void
    {
        $this->model->updateOrCreate(['name' => $name], ['value' => $value]);

        static::$cache = null;
    }
}
