<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sistemas extends Model
{
    use HasFactory;
    protected $connection = 'mysql_user';
    protected $table ='Sistemas';

    protected $fillable = [
        'sistema',
    ];
    public $timestamps = false;

}
