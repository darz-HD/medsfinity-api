<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pharmacy extends Model 
{
    protected $table = "pharmacies";

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_initial',
        'last_name',
        'email',
        'contact_number',
        'pharmacy_name',
        'licence_number',
        'country',
        'street_address',
        'state',
        'city',
        'postal_code',
        'website_name ',
        'price_list ',
        'created_at',
        'updated_at'
    ];
    
}
