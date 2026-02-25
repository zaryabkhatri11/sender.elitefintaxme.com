<?php

namespace App\Providers;

use App\Models\Module;
use App\Models\NotificationUser;
use App\Observers\ModuleObserver;
use App\Observers\NotificationObserver;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;   // 👈 ADD THIS
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        App::setLocale('en');
        Schema::defaultStringLength(191);

        // 👇 ADD THIS BLOCK
        try {
            DB::connection()->getDoctrineSchemaManager()
                ->getDatabasePlatform()
                ->registerDoctrineTypeMapping('enum', 'string');
        } catch (\Exception $e) {
            // ignore if doctrine not installed
        }

        Module::observe(ModuleObserver::class);
        NotificationUser::observe(NotificationObserver::class);
    }

    public function register()
    {
        //
    }
}