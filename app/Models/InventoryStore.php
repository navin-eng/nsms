<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryStore extends Model
{
    protected $fillable = ['name', 'code', 'description'];
}
