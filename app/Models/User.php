<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model 
{
    protected $table = "users";

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'password',
        'status',
        'created_at',
        'updated_at'
    ];
}
