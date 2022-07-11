<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model 
{
    protected $table = "merchants";

    protected $fillable = [
        'merchant',
        'key',
        'token',
        'created_at',
        'updated_at'
    ];
    
}
