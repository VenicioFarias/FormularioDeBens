<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cidades extends Model
{
    protected $connection = 'Geo';
    protected $table = 'Cidades';
    protected $primaryKey = 'id';
    protected $fillable = [
        'cidade',
        'geo',
        'estado_id',
        'created_at'
    ];
    public $timestamps = false;
}
