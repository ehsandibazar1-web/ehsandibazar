<?php

namespace App\Providers;
use App\Model\Permission;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        foreach ($this->getPermissions() as $permission) {
            Gate::define($permission->name , function ($user) use($permission){
                return $user->hasRole($permission->roles);
            });
        }
    }

    protected function getPermissions()
    {
        // Guard against boot before migrations have run (e.g. fresh install,
        // running `migrate`, or an unavailable database during CLI commands).
        try {
            if (! Schema::hasTable('permissions')) {
                return collect();
            }
        } catch (QueryException $e) {
            return collect();
        }

        return Permission::with('roles')->get();
    }
}
