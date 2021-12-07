<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Armas extends Model
{
    protected $table = 'armas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'modelo',
        'num_serie',
        'calibre',
        'carregadores',
        'form_id'

    ];
    public $timestamps = false;
}
