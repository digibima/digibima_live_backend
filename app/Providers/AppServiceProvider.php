<?php

namespace App\Providers;
use App\Models\ConfigModel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\{Session, Auth, DB,Gate,Config};

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
        //  $oConfigMaster = ConfigModel::where('key', getconstant('CONFIG.TESTMASTER))->first();
        //  $oConfigHealth = ConfigModel::where('key', getconstant('CONFIG.TESTHEALTH))->first();
        //  $oConfigMotor = ConfigModel::where('key', getconstant('CONFIG.TESTMOTOR))->first();
        // if ($oConfig) {
        //     Config::set('database.mysql_motor.database', $oConfigMotor->db);
        //     Config::set('database.mysql_motor.username', $oConfigMotor->username);
        //     Config::set('database.mysql_motor.password', $oConfigMotor->password);  
        //     Config::set('database.mysql_health.database', $oConfigHealth->db);
        //     Config::set('database.mysql_health.username', $oConfigHealth->username);
        //     Config::set('database.mysql_health.password', $oConfigHealth->password);  
        //     Config::set('database.mysql_master.database', $oConfigMaster->db);
        //     Config::set('database.mysql_master.username', $oConfigMaster->username);
        //     Config::set('database.mysql_master.password', $oConfigMaster->password);  
        // }
    }
}
