<?php

namespace Webkul\Theme;

class ThemeBlockRegistry
{
    /**
     * Registered theme customization block definitions, keyed by `type`.
     *
     * @var array<string, array{label: string, admin_view: ?string, shop_view: ?string, has_upload: bool}>
     */
    protected array $blocks = [];

    /**
     * Register a theme customization block type.
     *
     * `admin_view` is included in the theme edit form when a row of this type
     * is opened. `shop_view` is included by the storefront homepage loop for
     * every active row of this type; leave it null for blocks rendered by a
     * dedicated layout slot instead (e.g. footer, services strip).
     */
    public function register(string $type, array $definition): void
    {
        $this->blocks[$type] = array_merge([
            'label' => $type,
            'admin_view' => null,
            'shop_view' => null,
            'has_upload' => false,
        ], $definition);
    }

    /**
     * Get a single block definition.
     */
    public function get(string $type): ?array
    {
        return $this->blocks[$type] ?? null;
    }

    /**
     * Get all registered block definitions.
     */
    public function all(): array
    {
        return $this->blocks;
    }

    /**
     * Get all registered block type keys.
     */
    public function types(): array
    {
        return array_keys($this->blocks);
    }

    /**
     * Whether a type is registered.
     */
    public function has(string $type): bool
    {
        return isset($this->blocks[$type]);
    }
}
