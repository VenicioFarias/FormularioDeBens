<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Algema extends Model
{
    protected $table = 'algemas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'marca',
        'tipo',
        'num_serie',
        'form_id'
    ];
    public $timestamps = false;
}
