<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model 
{
    protected $table = "doctors";

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_initial',
        'last_name',
        'email',
        'birth_date',
        'contact_number',
        'specialty',
        'experience_year',
        'supporting_documents',
        'created_at',
        'updated_at'
    ];
}
