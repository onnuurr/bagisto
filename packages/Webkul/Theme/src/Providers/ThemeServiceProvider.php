<?php

namespace Webkul\Theme\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Webkul\Theme\Models\ThemeCustomization;
use Webkul\Theme\ThemeBlockRegistry;
use Webkul\Theme\ThemeViewFinder;
use Webkul\Theme\ViewRenderEventManager;

class ThemeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/../Http/helpers.php';

        $this->app->singleton('view.finder', function ($app) {
            return new ThemeViewFinder(
                $app['files'],
                $app['config']['view.paths'],
                null
            );
        });

        $this->app->singleton(ViewRenderEventManager::class);

        $this->app->singleton(ThemeBlockRegistry::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        Blade::directive('bagistoVite', function ($expression) {
            return "<?php echo themes()->setBagistoVite({$expression})->toHtml(); ?>";
        });

        $this->registerCoreThemeBlocks();
    }

    /**
     * Register the built-in theme customization block types.
     *
     * Other packages may register additional block types the same way, from
     * their own service provider's `boot()`:
     *
     *     app(ThemeBlockRegistry::class)->register('testimonial_carousel', [...]);
     *
     * @return void
     */
    protected function registerCoreThemeBlocks()
    {
        $registry = $this->app->make(ThemeBlockRegistry::class);

        $registry->register(ThemeCustomization::IMAGE_CAROUSEL, [
            'label' => 'admin::app.settings.themes.create.type.image-carousel',
            'admin_view' => 'admin::settings.themes.edit.image-carousel',
            'shop_view' => 'shop::home.blocks.image-carousel',
            'has_upload' => true,
        ]);

        $registry->register(ThemeCustomization::PRODUCT_CAROUSEL, [
            'label' => 'admin::app.settings.themes.create.type.product-carousel',
            'admin_view' => 'admin::settings.themes.edit.product-carousel',
            'shop_view' => 'shop::home.blocks.product-carousel',
        ]);

        $registry->register(ThemeCustomization::CATEGORY_CAROUSEL, [
            'label' => 'admin::app.settings.themes.create.type.category-carousel',
            'admin_view' => 'admin::settings.themes.edit.category-carousel',
            'shop_view' => 'shop::home.blocks.category-carousel',
        ]);

        $registry->register(ThemeCustomization::STATIC_CONTENT, [
            'label' => 'admin::app.settings.themes.create.type.static-content',
            'admin_view' => 'admin::settings.themes.edit.static-content',
            'shop_view' => 'shop::home.blocks.static-content',
        ]);

        $registry->register(ThemeCustomization::FOOTER_LINKS, [
            'label' => 'admin::app.settings.themes.create.type.footer-links',
            'admin_view' => 'admin::settings.themes.edit.footer-links',
        ]);

        $registry->register(ThemeCustomization::SERVICES_CONTENT, [
            'label' => 'admin::app.settings.themes.create.type.services-content',
            'admin_view' => 'admin::settings.themes.edit.services-content',
            'has_upload' => true,
        ]);
    }
}
