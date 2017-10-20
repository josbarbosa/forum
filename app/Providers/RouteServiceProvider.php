<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace'  => $this->namespace,
        ], function () {
            foreach (\File::allFiles(base_path() . '/routes/web/') as $partial) {
                /**
                 * ========
                 * Using partial route files for better organization
                 * ========
                 *
                 * Registering partial route files fires a bug when unit tests are running and require_once is used
                 * With every new test, a completely new application instance and thus router is used
                 * So using require_once the routes are no longer registered, the first test will succeed but the next ones will fail
                 * The main routes files are included but partials are not, since PHP correctly considers them already included
                 * Is important not define any functions/classes in these partial files
                 * All route files declared as partials are inside a specific folder "web" to avoid any issues
                 *
                 */
                require $partial->getPathName();
            }
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api/api.php'));
    }
}
