<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    use HasFactory;

    protected $table = 'stocks';
    protected $primaryKey = 'id';
    protected $fillable = ['name','quantity','description'];
    public $timestamps = false;
}
