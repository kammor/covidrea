<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // only authorize service editing when the service actually belongs to the logged-in user
        // or to the admin
        Gate::define('edit-service', function ($user, $service) {
            $authorized = $user->services()->get()->contains($service->id) || $service->etablissement->user_id == $user->id;

            Log::info('Is user '.$user->id.' authorized to edit service '.$service->id.' => '.$authorized);

            return $authorized;
        });

        // only authorize deleting a service when the user is the admin of the etablissement
        Gate::define('delete-service', function ($user, $service) {
            $authorized = $service->etablissement->user_id == $user->id;

            Log::info('Is user '.$user->id.' authorized to delete service '.$service->id.' => '.$authorized);

            return $authorized;
        });

        // only authorize etablissement editing when the logged-in user is responsible for this etablissement
        Gate::define('edit-etablissement', function ($user, $etablissement) {
            $authorized = $etablissement->user_id == $user->id;
            Log::info('Is user '.$user->id.' authorized to edit etablissement '.$etablissement->id.' => '.$authorized);

            return $authorized;
        });

        // only authorize user to acess the invitation page if he is responsible for at least one establishment
        Gate::define('invite', function ($user) {
            $authorized = $user->hasEtablissement();
            Log::info('Is user '.$user->id.' authorized to invite colleagues (because he is responsible for at least one establishment => '. $authorized);
            return $authorized;
        });

        // only authorize a user to add a service in an etablissement when he is the administrator
        Gate::define('create-service', function ($user, $etablissement) {
            $authorized = $etablissement->user_id == $user->id;
            Log::info('Is user '.$user->id.' authorized to create an etablissement '.$etablissement->id.' => '.$authorized);

            return $authorized;
        });
    }
}
