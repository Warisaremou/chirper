<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('avatar', function ($expression): string {
            return "<?php echo strtoupper(implode('', array_map(function(\$w){ return substr(\$w,0,1); }, array_filter(preg_split('/\\s+/', {$expression}))))); ?>";
        });
    }
}
