<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;
    protected $connection = 'mysql_user';
    protected $table = 'perfis';
    protected $primaryKey = 'id';
    protected $fillable = [
        'perfil',
        'sistema_id',
        'created_at',
    ];
    public $timestamps = false;

    public function sistemas(){

        //return $this->hasOne(related:'App\Models\Sistemas',foreignKey:'id',localKey:'sistema_id');
        //return $this->belongsToMany(Perfis::class);
    }

}
