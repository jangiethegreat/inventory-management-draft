<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeployedItem extends Model
{
    use HasFactory;
    protected $table = 'deployed_items';
    protected $fillable = ['receiver_name', 'sender_name', 'item_details'];
    public $timestamps = false;
}
