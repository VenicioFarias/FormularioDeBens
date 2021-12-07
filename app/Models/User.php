<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;


    protected $connection = 'mysql_user';
    protected $table ='users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pessoa_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function relStatus()
    {

        return $this->hasOne(related:'App\Models\Status',foreignKey:'id_user');

    }

    public function minhaUnidade()
    {

        //  $lotacao= DB::connection('mysql_rh')->table('pessoas')
        // ->leftJoin('lotacoes', 'lotacoes.pessoa_id', '=', 'pessoas.id')
        // ->leftJoin('geo.unidades', 'unidades.id', '=', 'lotacoes.unidade_id')
        // ->select('pessoas.*','lotacoes.*','unidade', 'sigla')->where('pessoa_id',Auth::user()->pessoa_id )->whereNull('lotacoes.a_partir_de_fim');
        $pessoa_id = Auth::user()->pessoa_id;
        //dd($id_servidor);
        $lotacao = DB::connection('mysql_rh')->select("call get_lotacao_atual($pessoa_id)");
        // dd($lotacao);
        return $lotacao;
    }

    public function recuperaPerfil(User $user)
    {

        $perfil= DB::connection('mysql_user')->table('usuarios_perfis')
        ->leftJoin('users', 'users.id', '=', 'usuarios_perfis.user_id')
        ->leftJoin('perfis', 'perfis.id', '=', 'usuarios_perfis.perfil_id')
        ->leftJoin('sistemas','sistemas.id', '=', 'perfis.sistema_id')
        ->select('usuarios_perfis.user_id','perfis.perfil','sistemas.sistema')
        ->where('sistemas.sistema','like','%frequencia%')
        ->where('usuarios_perfis.user_id',$user->id )->get();


        return $perfil;
    }


    public  function hasPermission(Usuarios_perfis $permission)
    {

        return $this ->hasAnyRoles($permission->perfil);

    }

    public function hasAnyRoles($roles)
    {

        if(is_array($roles) || is_object($roles))
        {
            foreach ($roles as $role)
            {
                return $this->roles->contains('name',$role->name);
            }

        }
        return $this->roles->contains('name',$roles);
    }

    // public static function boot()
    // {
    //     parent::boot();
    //     parent::observe(new \App\Observers\LogObserver);
    // }
    public function meuPerfil()
    {
        return $this->hasMany(related:Usuarios_perfis::class, foreignKey:'user_id', localKey:'id' );
    }

}
