<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuarios_perfis extends Model
{
    //use HasFactory;
    protected $connection = 'mysql_user';
    protected $table = 'usuarios_perfis';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'user_id',
        'perfil_id',
        'created_at',
    ];
    public $timestamps = false;


public function perfis(){

    // return $this->hasOne(Usuarios_perfil::class)->where('perfil_id', $id)->exists();
    // return $this->hasMany(related:'App\Models\Perfil',foreignKey:'id',localKey:'perfil_id');
    // return $this->hasOne(Usuarios_perfis::class, 'id', 'perfil_id');
    //return $this->belongsToMany(Perfis::class);
}



}
