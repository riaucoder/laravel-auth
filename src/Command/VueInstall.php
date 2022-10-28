<?php

namespace Riaucoder\LaravelAuth\Command;

use Illuminate\Filesystem\Filesystem;

trait VueInstall
{

    protected function installWithVue() {

        // Install inertia laravel packages
        $this->requireComposerPackages('inertiajs/inertia-laravel:^0.6.3', 'laravel/sanctum:^2.8', 'tightenco/ziggy:^1.0');
        $this->line('Install composer packages completed');

        // Install NPM Package
        $this->updateNodePackages(function ($packages) {
            return [
                    '@inertiajs/inertia' => '^0.11.0',
                    '@inertiajs/inertia-vue3' => '^0.6.0',
                    '@inertiajs/progress' => '^0.2.7',
                    '@tailwindcss/forms' => '^0.5.2',
                    '@vitejs/plugin-vue' => '^3.0.0',
                    '@kyvg/vue3-notification' => '^2.4.1',
                    '@mdi/js' => '^7.0.96',
                    'autoprefixer' => '^10.4.2',
                    'postcss' => '^8.4.6',
                    'tailwindcss' => '^3.1.0',
                    'vue' => '^3.2.31',
                    'daisyui' => '^2.24.0',
                    'path' => '^0.12.7',
                ] + $packages;
        });

        $this->line('Install npm packages completed');

        // Controllers...
        copy(__DIR__.'/../../stubs/Vue/Http/Controllers/HomeController.stub', app_path('Http/Controllers/HomeController.php'));
        copy(__DIR__.'/../../stubs/Vue/Http/Controllers/DashboardController.stub', app_path('Http/Controllers/DashboardController.php'));

        (new Filesystem)->ensureDirectoryExists(app_path('Http/Controllers/Auth'));
        copy(__DIR__.'/../../stubs/Vue/Http/Controllers/Auth/AuthenticatedSessionController.stub', app_path('Http/Controllers/Auth/AuthenticatedSessionController.php'));
        copy(__DIR__.'/../../stubs/Vue/Http/Controllers/Auth/ConfirmablePasswordController.stub', app_path('Http/Controllers/Auth/ConfirmablePasswordController.php'));
        copy(__DIR__.'/../../stubs/Vue/Http/Controllers/Auth/EmailVerificationNotificationController.stub', app_path('Http/Controllers/Auth/EmailVerificationNotificationController.php'));
        copy(__DIR__.'/../../stubs/Vue/Http/Controllers/Auth/EmailVerificationPromptController.stub', app_path('Http/Controllers/Auth/EmailVerificationPromptController.php'));
        copy(__DIR__.'/../../stubs/Vue/Http/Controllers/Auth/NewPasswordController.stub', app_path('Http/Controllers/Auth/NewPasswordController.php'));
        copy(__DIR__.'/../../stubs/Vue/Http/Controllers/Auth/PasswordResetLinkController.stub', app_path('Http/Controllers/Auth/PasswordResetLinkController.php'));
        copy(__DIR__.'/../../stubs/Vue/Http/Controllers/Auth/RegisteredUserController.stub', app_path('Http/Controllers/Auth/RegisteredUserController.php'));
        copy(__DIR__.'/../../stubs/Vue/Http/Controllers/Auth/VerifyEmailController.stub', app_path('Http/Controllers/Auth/VerifyEmailController.php'));

        $this->line('Copying Auth Controllers completed');

        // Requests...
        (new Filesystem)->ensureDirectoryExists(app_path('Http/Requests/Auth'));
        copy(__DIR__.'/../../stubs/Vue/Http/Requests/Auth/LoginRequest.stub', app_path('Http/Requests/Auth/LoginRequest.php'));

        // Middleware...
        $this->installMiddlewareAfter('SubstituteBindings::class', '\App\Http\Middleware\HandleInertiaRequests::class');
        copy(__DIR__.'/../../stubs/Vue/Http/Middleware/HandleInertiaRequests.stub', app_path('Http/Middleware/HandleInertiaRequests.php'));

        $this->line('Install composer packages completed');

        // Views...
        copy(__DIR__.'/../../stubs/Vue/resources/views/app.blade.stub', resource_path('views/app.blade.php'));

        // Components + Pages...
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Components'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Layouts'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Pages'));
        (new Filesystem)->ensureDirectoryExists(resource_path('js/Shared'));

        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/Vue/resources/js/Components', resource_path('js/Components'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/Vue/resources/js/Layouts', resource_path('js/Layouts'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/Vue/resources/js/Pages', resource_path('js/Pages'));
        (new Filesystem)->copyDirectory(__DIR__.'/../../stubs/Vue/resources/js/Shared', resource_path('js/Shared'));

        $this->line('Copying vue component, layout and pages completed');

//        $this->installTests();

        // Tailwind / Vite... etc
        copy(__DIR__.'/../../stubs/Vue/postcss.config.js', base_path('postcss.config.js'));
        copy(__DIR__.'/../../stubs/vue/tailwind.config.js', base_path('tailwind.config.js'));
        copy(__DIR__.'/../../stubs/vue/jsconfig.json', base_path('jsconfig.json'));
        copy(__DIR__.'/../../stubs/vue/vite.config.js', base_path('vite.config.js'));
        copy(__DIR__.'/../../stubs/Vue/resources/css/app.css', resource_path('css/app.css'));
        copy(__DIR__.'/../../stubs/Vue/resources/js/app.js', resource_path('js/app.js'));

        $this->line('Copying postcss, tailwind config, vite config etc completed');

        copy(__DIR__.'/../../stubs/Vue/routes/web.stub', base_path('routes/web.php'));
        $this->replaceInFile('/home', '/dashboard', app_path('Providers/RouteServiceProvider.php'));

        $this->runCommands(['npm install', 'npm run build']);

        $this->components->info('Laravel auth using tailwindcss with DaisyUI scaffolding installed successfully.');

        return 1;
    }

}
