<?php

namespace App\Providers;

use App\Models\Perfis;
use App\Models\Sistemas;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use App\Models\Perfil;
use App\Models\Usuarios_perfis;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         //'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->registerPolicies();

        // Gate::before(function(User $user) {
        //     //verifica se ele é administrador geral, se for pode TUDO
        //     if ( $user->isAdminGeral() )
        //         return true;
        // });

        // $sistema_id = config('constants.geral.sistema_id');
        // $perfis = Perfil::with('usuarios_perfis')->where('sistema_id', $sistema_id)->get();
        // //dd( $perfis->pluck('usuarios_perfis')->pluck('perfil_id')->filter()->toArray() );
        // //$permissoes = Usuarios_perfil::where('user_id', )->get();

        // foreach( $perfis as $perfil ) :
        //     Gate::define($perfil->perfil, function(User $user) use ($perfil) {
        //         return $user->meuPerfil($perfil->id);
        //     });
        // endforeach;


        }


}
